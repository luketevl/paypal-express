<?php
spl_autoload_register(function ($class_name) {
    include __DIR__."/lib/".$class_name . '.class.php';
});
$ex = new ExpressCheckout();

$data = array(
  'numIdentify' => '323-392032',
  'returnUrl'   => 'http://return',
  'cancelUrl'   => 'http://cancel',
   'products'   => array(
                          array(
                            'name'        => 'My product',
                            'description' => 'best of the best',
                            'price'       => 90.8,
                            'qty'         => 3
                          )
                        )
);

var_dump($ex->expressCheckout($data));


 ?>
