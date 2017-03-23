<?php

  include "methods.php";

  $link = "http://web.dmi.unict.it/Didattica/Laurea%20Magistrale%20in%20Informatica%20LM-18/Calendario%20delle%20Lezioni";

  $xpath = new DOMXPath(getDOM($link));

  $meseAttuale = date('m');

  $semestre = ($meseAttuale > 2 && $meseAttuale < 9) ? 2 : 1;

  $results = $xpath->query("((//tbody)[$semestre])/tr");

  $array = array();
  $labels = ["insegnamento","aula","lunedi","martedi","mercoledi","giovedi","venerdi"];

  if (!is_null($results)) {
    foreach ($results as $element) {

      $singleObject = [];
      $indexLabel = 0;

      $nodes = $element->childNodes;
      foreach ($nodes as $node) {

          if($node->nodeType == 1){
            if (strpos(strtolower($node->nodeValue),'anno') !== false)
              $currentYear = $node->nodeValue;
            else{
              $singleObject[$labels[$indexLabel]] = trim(trim(str_replace(chr(0xC2).chr(0xA0),"",$node->nodeValue), chr(0xC2).chr(0xA0)));
              $indexLabel++;
            }
          }

      }

      if( (count ((array)$singleObject) == sizeof($labels)) && (!is_null($currentYear)) && (!empty($singleObject['insegnamento'])) ){
        $singleObject['anno'] = $currentYear;
        array_push($array, $singleObject);
      }

    }
  }

  if(sizeof($array) > 0){
    $status = array("length" => sizeof($array), "lastupdate" => date("Y-m-d H:i:s"));
    $finalResult = array("status" => $status, "items" => $array);

    print json_encode($finalResult, JSON_PRETTY_PRINT);
    createFolder('result');
    saveJSON('result', 'lezioni_dmi_mag.json', $finalResult);
  }
?>
