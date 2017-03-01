<?php

  include "methods.php";

  $link = "http://web.dmi.unict.it/Didattica/Laurea%20Triennale%20in%20Informatica%20L-31/Calendario%20delle%20Lezioni";

  $xpath = new DOMXPath(getDom($link));
  $results = $xpath->query("//tbody/tr");

  //init array
  $array = array();
  $i = 0;

  $label = ["insegnamento","aula","lunedi","martedi","mercoledi","giovedi","venerdi"];

  if (!is_null($results)) {
    foreach ($results as $element) {

      $subject = [];
      $j = 0;

      $nodes = $element->childNodes;
      foreach ($nodes as $node) {

          if($node->nodeType == 1){

            if (strpos(strtolower($node->nodeValue),'anno') !== false) {
              $currentYear = $node->nodeValue;
            }
            else{
              $subject[$label[$j]] = trim($node->nodeValue, chr(0xC2).chr(0xA0));
              $j++;
            }
          }

      }

      if( (count ((array)$subject) == sizeof($label)) && 
          (!is_null($currentYear)) && 
          (!empty($subject['insegnamento']))
        ){
        
        $subject['anno'] = $currentYear;
        array_push($array, $subject);
      }

      $i++;

    }
  }

  //Print the json
  print json_encode($array);

  $fh = fopen('lezioni.json', 'w+') or die("can't open file");
  $stringData = json_encode($array);
  fwrite($fh, $stringData);
  fclose($fh);

?>
