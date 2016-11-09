
<?php

$link = "http://web.dmi.unict.it/Didattica/Laurea%20Triennale%20in%20Informatica%20L-31/Calendario%20delle%20Lezioni";

libxml_use_internal_errors(true);

// crea una nuova risorsa cURL
$ch = curl_init();

// imposta l'URL e altre opzioni appropriate
curl_setopt($ch, CURLOPT_URL, $link);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// recupera l'URL e lo passa al browser
$html = curl_exec($ch);

$dom = new DOMDocument;
$dom->loadHTML($html);

$xpath = new DOMXPath($dom);
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
