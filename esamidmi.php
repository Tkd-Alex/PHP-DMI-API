<?php

  include "methods.php";

  $link = "http://web.dmi.unict.it/Didattica/Laurea%20Triennale%20in%20Informatica%20L-31/Calendario%20dEsami";

  $xpath = new DOMXPath(getDOM($link));
  $results = $xpath->query("//table[1]/tbody/tr");

  $array = array();
  $labels = ["prima","prima1","seconda","seconda1","terza","terza1","straordinaria","straordinaria1"];
  $currentYear = "Primo anno";

  if (!is_null($results)) {
    foreach ($results as $element) {

      $singleObject = [];
      $session = [];
      $indexLabel = 0;

      $nodes = $element->childNodes;
      foreach ($nodes as $node) {

          if($node->nodeType == 1){
            //print_r($node);
            if (strpos(strtolower($node->nodeValue),'anno') !== false)
              $currentYear = $node->nodeValue;
            else{

              if(sizeof($singleObject) == 0){
                if($node->childNodes->length > 1){
                  $tmpInfo = [];
                  $childs = $node->childNodes;
                  foreach ($childs as $child)
                     if($child->tagName == "span")
                       array_push($tmpInfo, trim(str_replace("\t","", $child->nodeValue), chr(0xC2).chr(0xA0)) );

                  if( strstr($tmpInfo[0], "\n") ){
                    $docenti_materia = explode("\n", trim(str_replace("\t","", $tmpInfo[0]), chr(0xC2).chr(0xA0)) );
                    $singleObject["insegnamento"] = trim($docenti_materia[0], chr(0xC2).chr(0xA0) );
                    $singleObject["docenti"] = trim($docenti_materia[1], chr(0xC2).chr(0xA0) );
                  }else{
                   if($tmpInfo[0]) $singleObject["insegnamento"] = $tmpInfo[0];
                   else $singleObject["insegnamento"] = "";
                   if($tmpInfo[1]) $singleObject["docenti"] = $tmpInfo[1];
                   else $singleObject["docenti"] = "";
                 }
                }else{
                  $docenti_materia = explode("\n", trim(str_replace("\t","", $node->nodeValue), chr(0xC2).chr(0xA0)) );
                  $singleObject["insegnamento"] = trim($docenti_materia[0], chr(0xC2).chr(0xA0) );
                  $singleObject["docenti"] = trim($docenti_materia[1], chr(0xC2).chr(0xA0) );
                }
              }
              else{
                $singleObject[$labels[$indexLabel]] = trim(str_replace("\t","", $node->nodeValue), chr(0xC2).chr(0xA0));
                $indexLabel++;
              }
            }
          }

      }

      if( (!is_null($currentYear)) && (!empty($singleObject['insegnamento'])) ){
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
    saveJSON('result', 'esamidmi.json', $finalResult);
  }

?>
