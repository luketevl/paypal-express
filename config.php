<?php
require __DIR__.'/helpers/helper.php';
date_default_timezone_set(@date_default_timezone_get());

define('PAYPAL_LOCALECODE', 'pt_br'); // Idioma
define('PAYPAL_CURRENCY',   'BRL'); // Idioma
define('PAYPAL_HDRIMG',     ''); // URL COMPLETA da LOGO para checkout PayPAL

define('PAYPAL_CLIENT_ID',      'AYgX0Ik7jbv1QKgDmYdgl3HzPohCfXK2pcHjppbK7Byk1SxNRico2pgZpC1fDnxKOPlr0iY_Z6ZjpYQf');
define('PAYPAL_CLIENT_SECRET',  'EGsZNmC3w2cVKaee5wpn5zeQj6rrQd_5bgAThT2y2OW1eEwMDojP_8dQ5KIgV8FdlpYL03_0NcZcYnth');

define('PAYPAL_SANDBOX', true); // Ambiente a ser utilizado, (true = teste | false = produção)
