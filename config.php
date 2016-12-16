<?php
require __DIR__.'/helpers/helper.php';

define('PAYPAL_USER',       '');     // Usuário
define('PAYPAL_PASSWORD',   ''); // Senha
define('PAYPAL_SIGNATURE',  ''); // Assinatura,chave API
define('PAYPAL_LOCALECODE', 'pt_br'); // Idioma
define('PAYPAL_HDRIMG',     ''); // URL COMPLETA para checkout PayPAL


define('PAYPAL_SANDBOX', true); // Ambiente a ser utilizado, (true = teste | false = produção)
// SANDBOX CONFIG
define('PAYPAL_SANDBOX_USER', 'conta-business_api1.test.com');     // Usuário
define('PAYPAL_SANDBOX_PASSWORD', '1365001380'); // Senha
define('PAYPAL_SANDBOX_SIGNATURE', 'AiPC9BjkCyDFQXbSkoZcgqH3hpacA-p.YLGfQjc0EobtODs.fMJNajCx'); // Assinatura,chave API
