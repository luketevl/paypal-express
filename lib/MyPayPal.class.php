<?php
require __DIR__  . '/../vendor/autoload.php';
require_once __DIR__.'/../config.php';
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\Incentive;
use PayPal\Api\FundingInstrument;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
class MyPayPal{
  public function __construct(){
    $this->apiContext =
    new \PayPal\Rest\ApiContext(
      new \PayPal\Auth\OAuthTokenCredential(PAYPAL_CLIENT_ID, PAYPAL_CLIENT_SECRET)
    );
    // Config
    $this->apiContext->setConfig(
                            array(
                             'mode' => PAYPAL_SANDBOX ? 'sandbox' : 'live',
                             'log.LogEnabled' => true,
                             'log.FileName' => __DIR__.'/../PayPal.log',
                             'log.LogLevel' => 'INFO'
                            )
                          );
    define('PAYMENT_STATE_APPROVED', 'approved');
  }
  
  
  public function createPayment($data = array(), $payPlus = false){
    if(empty($data)) return false;

    // ### Payer
    // A resource representing a Payer that funds a payment
    // For paypal account payments, set payment method
    // to 'paypal'.
    $payer                = new Payer();
    $details              = new Details();
    $transaction          = new Transaction();
    $amount               = new Amount();
    $redirectUrls         = new RedirectUrls();
    $incentive            = new Incentive();
    $fundingInstrument    = new FundingInstrument();

    $payer->setPaymentMethod("paypal");

    if(getVal('urlLogo', $data)){
      $incentive->setLogoImageUrl(PAYPAL_HDRIMG);
    }


    // ### Itemized information
    // (Optional) Lets you specify item wise
    // information
    $arrayItens= array();
    $sumSubTotal = 0;
    foreach ($data['itens'] as $key => $value) {
      $item = new Item();



      $item->setName(getVal('name', $value))
           ->setSku(getVal('cod', $value))
           ->setCurrency('BRL')
           ->setQuantity(getVal('qty', $value))
           ->setPrice(getNumber(getVal('price', $value)));
           $sumSubTotal += getNumber(getVal('price', $value)) * getNumber(getVal('qty', $value));;
      array_push($arrayItens, $item);
    }
    // ### Additional payment details
    // Use this optional field to set additional
    // payment information such as tax, shipping
    // charges etc.

    $details->setShipping(getNumber(getVal('shipping', $data),2))
            ->setTax(getNumber(getVal('tax', $data),2));

    if (count($data['itens']) > 0) {
      $itemList = new ItemList();
      $itemList->setItems($arrayItens);
      $details->setSubtotal($sumSubTotal);
      $transaction->setItemList($itemList);
    }

    // ### Amount
    // Lets you specify a payment amount.
    // You can also specify additional details
    // such as shipping, tax.
    $total = getNumber(getVal('total', $data)) ? getNumber(getVal('shipping', $data)) : $sumSubTotal + getNumber(getVal('shipping', $data)) + getNumber(getVal('tax', $data));
    $amount->setCurrency("BRL")
        ->setTotal($total)
        ->setDetails($details);

    // ### Transaction
    // A transaction defines the contract of a
    // payment - what is the payment for and who
    // is fulfilling it.
    $transaction->setAmount($amount)
        ->setDescription(getVal('description', $data))
        ->setInvoiceNumber(uniqid());

    $fundingInstrument->setIncentive($incentive);
    $return = array('result' => false, 'paymentId' => false);

    try{
      if($payPlus) {
        $data['returnUrl'] = getVal('urlSite', $data);
        $data['cancelUrl'] = getVal('urlSite', $data);
      }
      // ### Redirect urls
      // Set the urls that the buyer must be redirected to after
      // payment approval/ cancellation.
      //$baseUrl = getBaseUrl();
      if(empty(getVal('returnUrl', $data))){
        //$redirectUrls->setReturnUrl("$baseUrl/ExecutePayment.php?success=true");
      }
      else{
        $redirectUrls->setReturnUrl(getVal('returnUrl', $data));
      }
      if(empty(getVal('cancelUrl', $data))){
        //$redirectUrls->setCancelUrl("$baseUrl/ExecutePayment.php?success=false");
      }
      else{
        $redirectUrls->setCancelUrl(getVal('cancelUrl', $data));
      }

      // ### Payment
      // A Payment Resource; create one using
      // the above types and intent set to 'sale'
      $payment = new Payment();
      $payment->setIntent("sale")
          ->setPayer($payer)
          ->setRedirectUrls($redirectUrls)
          ->setTransactions(array($transaction));
      if($payPlus){
        $payment->application_context = array(
              "locale"=> "pt-BR",
              "brand_name"=> getVal('brand_name', $data),
              "shipping_preference"=> getVal('shipping_preference', $data),
              "user_action"=> "continue"
        );
      }

      // For Sample Purposes Only.
      $request = clone $payment;
      // ### Create Payment
      // Create a payment by calling the 'create' method
      // passing it a valid apiContext.
      // (See bootstrap.php for more on `ApiContext`)
      // The return object contains the state and the
      // url to which the buyer must be redirected to
      // for payment approval

      $payment->create($this->apiContext);
        $return['result'] = array(
                                  'urlPay' => $payment->getApprovalLink(),
                                  'paymentId' => $payment->getId()
        );
    }
    catch (Exception $ex) {

      $return = array_merge($return, $this->_getError($ex));

    }

    // ### Get redirect url
    // The API response provides the url that you must redirect
    // the buyer to. Retrieve the url from the $payment->getApprovalLink()
    // method
    return $return;
  }
  
  function executePayment($paymentId= '', $payerId=''){
    $payment = Payment::get($paymentId, $this->apiContext);
   $execution = new PaymentExecution();
   $execution->setPayerId($payerId);
   $return = array('result' => false);
   try {
     $result = $payment->execute($execution, $this->apiContext);
     try {
       $payment = Payment::get($paymentId, $this->apiContext);
       $return['result'] = $payment;
     } catch (Exception $e) {
       $return = array_merge($return, $this->_getError($e));
     }
   } catch (Exception $e) {
      $return = array_merge($return, $this->_getError($e));
   }
   return $return;
  }
  public function isApproved($payment){
    if(is_bool($payment)) return false;
    return $payment->getState() == PAYMENT_STATE_APPROVED;
  }
  private function _getError($param = array()){
    if(count($param) <= 0) return array('error' => false);
    $data   = json_decode($param->getData());
    $return = array(
                    'error'   => $data->details
    );
    return $return;
  }
}
 ?>