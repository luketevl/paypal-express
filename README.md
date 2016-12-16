# Sobre
> Esse modulo faz a comunicação com a **API** do **PayPal** via **REST**

# Configuração
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
> Campos para envio

Name | Type |Value
-----|------|-----
numIdentify | String(alphanumeric) | Identificador do pagamento, _usado para futuramente saber qual pedido é de quem_
returnUrl | String |  URL de **retorno**
cancelUrl | String | URL de **cancelamento**
products  | Array  | _veja tabela de campos de produtos_


> Campos para o array de **produtos**
- Utiliza a seguinte campos da documentação
  - https://developer.paypal.com/docs/classic/api/merchant/GetExpressCheckoutDetails_API_Operation_NVP/

Name | Type |Value
-----|------|-----
name           | String | Nome do item
description    | String | Descrição do item
price          | Double | Preço do item
qty            | Double | Quantidade do item






# Referências
- https://developer.paypal.com/docs/integration/web/accept-paypal-payment/
