
<?php

function getDom($link){
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

  return $dom;
}

$link = "http://web.dmi.unict.it/Dipartimento/Docenti";

$xpath = new DOMXPath(getDom($link));
$results = $xpath->query("//*[@class='lista']//td//a/@href");

$link = array();

if (!is_null($results)) {
  foreach ($results as $element) {
    $nodes = $element->childNodes;
    foreach ($nodes as $node) {
    	array_push($link, $node->nodeValue);        
    }
  }
}

//Print the json 
print_r($link);
//print json_encode($link ,JSON_UNESCAPED_UNICODE);

// chiude la risorsa cURL e libera la memoria
curl_close($ch);

?>
