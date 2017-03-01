<?php

  include "methods.php";

  $link = "http://web.dmi.unict.it/Didattica/Laurea%20Triennale%20in%20Informatica%20L-31/Calendario%20dEsami";

  $xpath = new DOMXPath(getDOM($link));
  $results = $xpath->query("//table[1]/tbody/tr");

  $array = array();
  $labels = ["insegnamento","prima","prima1","seconda","seconda1","terza","terza1","straordinaria","straordinaria1"];

  if (!is_null($results)) {
    foreach ($results as $element) {

      $singleObject = [];
      $session = [];
      $indexLabel = 0;

      $nodes = $element->childNodes;
      foreach ($nodes as $node) {

          if($node->nodeType == 1){
            if (strpos(strtolower($node->nodeValue),'anno') !== false)
              $currentYear = $node->nodeValue;
            else{
              $singleObject[$labels[$indexLabel]] = trim(str_replace("<br>", " | ", $node->nodeValue), chr(0xC2).chr(0xA0));
              $indexLabel++;
              /*
              if($indexLabel!=0){
                echo sizeof($session)
              }else{
                //blabla
              }
              */
            }
          }

      }

      if( (count ((array)$singleObject) == sizeof($labels)) && (!is_null($currentYear)) && (!empty($singleObject['insegnamento'])) ){
        $singleObject['anno'] = $currentYear;
        array_push($array, $singleObject);
      }

    }
  }

  print json_encode($array, JSON_PRETTY_PRINT);

?>
