<?php
spl_autoload_register(function ($class_name) {
    include __DIR__."/lib/".$class_name . '.class.php';
});

$ex = new MyPayPal();
if(count($_GET) > 0){
  $result = $ex->executePayment($_GET['paymentId'], $_GET['PayerID']);
  pre($result['result']->getState());
  pre($ex->isApproved($result['result']));
  pre($result);
}
else{
  $data = array(
    'description' => 'Test PAY',
    'returnUrl' => 'http://localhost:8888/paypal-express/example.php/',
    'cancelUrl' => 'http://localhost:8888/paypal-express/example.php/',
    'shipping' => 0.01,
    'tax'     => 0.02,

    'itens'   => array(
      array(
        'cod' => time(),
        'name' => 'My item',
        'description' => 'My description',
        'price'       => 0.10,
        'qty'         => 3
      )
    )
  );
  pre($ex->createPayment($data));

}
 ?>
