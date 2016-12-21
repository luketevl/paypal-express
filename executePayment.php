<?php
spl_autoload_register(function ($class_name) {
    include __DIR__."/lib/".$class_name . '.class.php';
});

if(count($_GET) > 0){
  $ex = new MyPayPal();
  $result = $ex->executePayment($_GET['paymentId'], $_GET['PayerID']);
  pre($result['result']);
  pre($ex->isApproved($result['result']));
  pre($result);
}
else{
  echo "No data";
} ?>
