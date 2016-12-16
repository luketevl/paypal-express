<?php
class PayPalBase {

  /**
   * Envia uma requisição NVP para uma API PayPal.
   *
   * @param array $requestNvp Define os campos da requisição.
   * @param boolean $sandbox Define se a requisição será feita no sandbox ou no
   *                         ambiente de produção.
   *
   * @return array Campos retornados pela operação da API. O array de retorno poderá
   *               ser vazio, caso a operação não seja bem sucedida. Nesse caso, os
   *               logs de erro deverão ser verificados.
   */
  protected function sendNvpRequest($requestNvp = array(), $sandbox = false){
      //Endpoint da API
      $apiEndpoint  = 'https://api-3t.' . ($sandbox? 'sandbox.': null);
      $apiEndpoint .= 'paypal.com/nvp';
      pre($requestNvp);

      //Executando a operação
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($requestNvp));

      $response = urldecode(curl_exec($curl));
      var_dump(curl_exec($curl));
      curl_close($curl);
      //Tratando a resposta
      $responseNvp = array(
        'results' => array(),
        'erros' => array(),
      );

      if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
          foreach ($matches['name'] as $offset => $name) {
              $responseNvp['results'][$name] = $matches['value'][$offset];
          }
      }

      //Verificando se deu tudo certo e, caso algum erro tenha ocorrido,
      //gravamos um log para depuração.
      if (isset($responseNvp['results']['ACK']) && $responseNvp['results']['ACK'] != 'Success') {
          for ($i = 0; isset($responseNvp['results']['L_ERRORCODE' . $i]); ++$i) {
              $message = sprintf("PayPal NVP %s[%d]: %s\n",
                                 $responseNvp['results']['L_SEVERITYCODE' . $i],
                                 $responseNvp['results']['L_ERRORCODE' . $i],
                                 $responseNvp['results']['L_LONGMESSAGE' . $i]);

              error_log($message);
              $responseNvp['erros'][$i] = $message;
          }
      }

      return $responseNvp;
  }
}
