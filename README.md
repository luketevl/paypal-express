# Sobre
> Esse modulo faz a comunicação com a **API** do **PayPal** via **SDK**

# Pre requisitos
- **php v5.5** ou superior
- **Composer**
- **OpenSSL/1.0.2g**

# Configuração
- **Instale as dependencias**
```shell
composer install
```
- Adicione esse módulo no seu projeto, utilizando o **git submodules**
  - https://git-scm.com/docs/git-submodule
> Exemplo
```git
git submodule add -f http://git.gat:81/sites/structure_v4.git
```
- Configure o arquivo **config.php**
  - Modifique a variável de ambiente (**PAYPAL_SANDBOX**)
    - Produção **false**
    - Teste **true**

#Campos
> Campos para pagamento

Name | Type |Value
-----|------|-----
numIdentify | String(alphanumeric) | Identificador do pagamento, _usado para futuramente saber qual pedido é de quem_
description | String | Descrição do pagamento
returnUrl | String |  URL de **retorno**
cancelUrl | String |  URL de **cancelamento**
products  | Array  | _Opcional_ _veja tabela de campos de itens_
shipping  | Double | _Opcional_ Valor do _frete_
tax       | Double | _Opcional_ Valor do _imposto_
total     | Double | _Opcional_ Valor _total_ incluindo taxas e outros

| Double | Valor do _frete_

> Campos para o array de **itens**
- Utiliza a seguinte campos da documentação
  - https://developer.paypal.com/docs/classic/api/merchant/GetExpressCheckoutDetails_API_Operation_NVP/

Name | Type |Value
-----|------|-----
cod            | String | Código do produto
name           | String | Nome do item
description    | String | Descrição do item
price          | Double | Preço do item
qty            | Int | Quantidade do item




# Observações
- Utilize **ssl version** com _OpenSSL/1.0.2g_
  - **MACOSx** | MAMP 4 possui


# Referências
- https://developer.paypal.com/docs/api/payments/#definition-incentive
- https://developer.paypal.com/docs/integration/direct/express-checkout/create-express-checkout-payments/
- https://developer.paypal.com/docs/classic/api/errors/
- https://developer.paypal.com/docs/integration/web/accept-paypal-payment/
- https://github.com/paypal/PayPal-PHP-SDK
- http://paypal.github.io/PayPal-PHP-SDK/sample
