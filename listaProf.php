
<?php

include "methods.php";

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
