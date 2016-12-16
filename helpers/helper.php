<?php
  // Usado para obter valores de indices de aray que nao sabemos se existe, evitando warning
  if(!function_exists('getVal')){
    function getVal($index = null, $array = null){
      return ($index != null && isset($array) && !empty($array[$index])) ? $array[$index] : '';
    }
  }

  // Usado para obter valores de indices de aray que nao sabemos se existe, evitando warning
  if(!function_exists('replaceNotAlphanumeric')){
    function replaceNotAlphanumeric($string =''){
      return preg_replace("[^:alnum:]", '', $string);
    }
  }

  if(!function_exists('pre')){
    function pre($field= ''){
      echo "<pre>"; print_r($field); echo "</pre>";
    }
  }

  if(!function_exists('getNumber')){
    function getNumber($number= 0){
      try{
        return number_format($number, 2);
      }
      catch (Exception $e) {
          return 0;
      }

    }
  }
?>
