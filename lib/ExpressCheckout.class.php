<?php
require_once __DIR__.'/../config.php';

class ExpressCheckout extends PayPalBase{
  function __construct(){
    define('PAYPAL_URL',          'https://www.paypal.com/cgi-bin/webscr');
    define('PAYPAL_SANDBOX_URL',  'https://www.sandbox.paypal.com/cgi-bin/webscr');

    //Baseado no ambiente, sandbox ou produção, definimos as credenciais, URLs da API.
    $this->user       = PAYPAL_SANDBOX ? PAYPAL_SANDBOX_USER      : PAYPAL_USER;
    $this->password   = PAYPAL_SANDBOX ? PAYPAL_SANDBOX_PASSWORD  : PAYPAL_PASSWORD;
    $this->signature  = PAYPAL_SANDBOX ? PAYPAL_SANDBOX_SIGNATURE : PAYPAL_SIGNATURE;
    $this->paypalURL  = PAYPAL_SANDBOX ? PAYPAL_SANDBOX_URL       : PAYPAL_URL;
  }

  private function _createRequest($data = array()){
    $requestNvp = array(
      'USER'                              => $this->user,
      'PWD'                               => $this->password,
      'SIGNATURE'                         => $this->signature,
      'VERSION'                           => '108.0',
      'METHOD'                            => 'SetExpressCheckout',
      'LOCALECODE'                        => PAYPAL_LOCALECODE,
      'PAYMENTREQUEST_0_PAYMENTACTION'    => 'SALE',
      'PAYMENTREQUEST_0_CURRENCYCODE'     => 'BRL',
    );

    // Verifica se passou a LOGO, caso contrario usa a padrao do Paypal
    if(!empty(PAYPAL_HDRIMG)){
      $requestNvp['HDRIMG'] = PAYPAL_HDRIMG;
    }

    // Validando URL de retorno
    if(!isset($data['returnUrl']) && !empty($data['returnUrl'])){
      $requestNvp['RETURNURL'] = $data['returnUrl'];
    }

    // Validando URL de cancelamento
    if(!isset($data['cancelUrl']) && !empty($data['cancelUrl'])){
      $requestNvp['CANCELURL'] = $data['cancelUrl'];
    }

    $sumPriceItens = 0;
    foreach ($data['products'] as $key => $value) {
        $requestNvp['PAYMENTREQUEST_0_INVNUM'     ]  = replaceNotAlphanumeric(getVal('numIdentify', $data)) | time();
        $requestNvp['L_PAYMENTREQUEST_0_NAME'.$key]  = getVal('name', $value);
        $requestNvp['L_PAYMENTREQUEST_0_DESC'.$key]  = getVal('description', $value);
        $requestNvp['L_PAYMENTREQUEST_0_AMT'.$key ]  = getVal('price', $value);
        $requestNvp['L_PAYMENTREQUEST_0_QTY'.$key ]  = getVal('qty', $value);

        $sumPriceItens += getVal('price', $value) * getVal('qty', $value);
    }
    //Campos da requisição da operação SetExpressCheckout, como ilustrado acima.

      $requestNvp['L_PAYMENTREQUEST_0_ITEMAMT']  = $sumPriceItens;
      $requestNvp['PAYMENTREQUEST_0_AMT']        = $sumPriceItens;
      $requestNvp['PAYMENTREQUEST_0_ITEMAMT']    = $sumPriceItens;
    return $requestNvp;
  }

  public function callRequest($dataRequest = array()){
      //Envia a requisição e obtém a resposta da PayPal
      return $this->sendNvpRequest($dataRequest , PAYPAL_SANDBOX);
  }

  private function _validateReturn($response = array()){
    //Se a operação tiver sido bem sucedida, redirecionamos o cliente para o mbiente de pagamento.
    if (isset($response['results']['ACK']) && $response['results']['ACK'] == 'Success') {
        $query = array(
            'cmd'    => '_express-checkout',
            'token'  => $response['results']['TOKEN']
        );
        $redirectURL = sprintf('%s?%s', $this->paypalURL, http_build_query($query));
        header('Location: ' . $redirectURL);
    }
    else {
        return $response;
    }
  }

  public function expressCheckout($data = array()){
    if(empty($data)) return false;

    $dataRequest  = $this->_createRequest($data);
    $response     = $this->callRequest($dataRequest);
    return $this->_validateReturn($response);

  }
}
