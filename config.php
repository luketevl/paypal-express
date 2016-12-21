<?php
require __DIR__.'/helpers/helper.php';
require __DIR__.'/constants.php';
date_default_timezone_set(@date_default_timezone_get());

define('PAYPAL_SANDBOX', true); // Ambiente a ser utilizado, (true = teste | false = produção)


define('PAYPAL_CLIENT_ID',      '');
define('PAYPAL_CLIENT_SECRET',  '');

define('PAYPAL_LOCALECODE', 'pt_br'); // Idioma
define('PAYPAL_CURRENCY',   'BRL'); // Idioma
define('PAYPAL_HDRIMG',     ''); // URL COMPLETA da LOGO para checkout PayPAL

define('PAYPAL_PAYMENT_METHOD',     PAYPAL_PAYMENT_METHOD_CREDIT_CARD); // URL COMPLETA da LOGO para checkout PayPAL
