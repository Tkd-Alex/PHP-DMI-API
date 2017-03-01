
<?php

$link = "http://web.dmi.unict.it/?q=Docenti/Informazioni%20docenti&key=RlJOR05ONzZNMjBINzkyUA==";

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
$infoDocente = $xpath->query("//div[@id='anagrafica']/node() | //div[@id='anagrafica']//a/@href");
$name = $xpath->query("//h1[@class='pagetitle']");  
$mail = $xpath->query("//a[starts-with(@href, 'mailto')]");

$label = array();
$prof = new stdClass();

if(!is_null($name))
	$prof->Nome = $name[0]->nodeValue;

if(!is_null($mail))
	$prof->Mail = $mail[0]->nodeValue;

if (!is_null($infoDocente)) {
  foreach ($infoDocente as $element) {
    $nodes = $element->childNodes;
    foreach ($nodes as $node) {
    	
    	//print_r($node);

    	if($node->tagName == 'b')
			array_push($label, $node->nodeValue);
    	
    	if($node->nodeName == '#text')
    		if($node->nodeValue != ": "){
    		    	$prof->{end($label)} = trim(str_replace(':', '', $node->nodeValue));
    		    }
    
    }
  }
}

//Print the json 
print_r($prof);

// chiude la risorsa cURL e libera la memoria
curl_close($ch);

?>
