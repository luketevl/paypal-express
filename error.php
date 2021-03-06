<?php
spl_autoload_register(function ($class_name) {
    include __DIR__."/lib/".$class_name . '.class.php';
});

$ex = new MyPayPal();

$data = array(
              'description' => 'Test PAY',
              'cancelUrl' => 'https://cancel',
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
 ?>
