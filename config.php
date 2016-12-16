<?php
require __DIR__.'/helpers/helper.php';
date_default_timezone_set(@date_default_timezone_get());

define('PAYPAL_LOCALECODE', 'pt_br'); // Idioma
define('PAYPAL_CURRENCY',   'BRL'); // Idioma
define('PAYPAL_HDRIMG',     ''); // URL COMPLETA da LOGO para checkout PayPAL

define('PAYPAL_CLIENT_ID',      'AbhqCxPXM1qkaXlwZn8oegNfbOmR3KhDkeWhF6ML4zw2Fz7YYFSr1e9ZFkrNDDmRDkdjFhtBXzMKGOVD');
define('PAYPAL_CLIENT_SECRET',  'EJsu9B-55Z_4c7utpD8R4YTk9I20X1eTA7EUxHREZWOuwAms2FRwfs5Q6GLuC6rsFI8o0-zl51Fj6vx9');

define('PAYPAL_SANDBOX', true); // Ambiente a ser utilizado, (true = teste | false = produção)
