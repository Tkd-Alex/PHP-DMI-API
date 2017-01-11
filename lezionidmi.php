<?php

  include "methods.php";

  $link = "http://web.dmi.unict.it/Didattica/Laurea%20Triennale%20in%20Informatica%20L-31/Calendario%20delle%20Lezioni";

  $xpath = new DOMXPath(getDom($link));
  $results = $xpath->query("//tr");

  //init array
  $array = array();
  $i = 0;

  $label = ["insegnamento","aula","lunedì","martedì","mercoledì","giovedì","venerdì"];

  if (!is_null($results)) {
    foreach ($results as $element) {

      $subject = new stdClass();
      $j = 0;

      $nodes = $element->childNodes;
      foreach ($nodes as $node) {

          if($node->nodeType==1){
            if (strpos(strtolower($node->nodeValue),'anno') !== false) {
              $currentYear = $node->nodeValue;
            }
            else{
              $subject->{$label[$j]} = $node->nodeValue;
              $j++;
            }
          }

      }

      if( (count ((array)$subject) == 7) && ($currentYear != null) ){
        $subject->anno = $currentYear;
        array_push($array, $subject);
      }

      $i++;

    }
  }

  //Print the json
  print json_encode($array,JSON_UNESCAPED_UNICODE);

  // chiude la risorsa cURL e libera la memoria
  curl_close($ch);

?>
