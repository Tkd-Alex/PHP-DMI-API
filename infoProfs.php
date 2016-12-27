
<?php

/*ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);*/

$listaProf = "http://web.dmi.unict.it/Dipartimento/Docenti";

libxml_use_internal_errors(true);

// crea una nuova risorsa cURL
$ch = curl_init();

// imposta l'URL e altre opzioni appropriate
curl_setopt($ch, CURLOPT_URL, $listaProf);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// recupera l'URL e lo passa al browser
$html = curl_exec($ch);

$dom = new DOMDocument;
$dom->loadHTML($html);

$xpath = new DOMXPath($dom);
$results = $xpath->query("//*[@class='lista']//td//a/@href");

$links = array();

if (!is_null($results)) {
  foreach ($results as $element) {
    $nodes = $element->childNodes;
    foreach ($nodes as $node) {
    	array_push($links, $node->nodeValue);        
    }
  }
}

//$links = array_slice($links, 0, 5); 
//print_r($links);
curl_close($ch);

$proffi = array();

foreach ($links as $link){
	
	// crea una nuova risorsa cURL
	$ch = curl_init();

	// imposta l'URL e altre opzioni appropriate
	curl_setopt($ch, CURLOPT_URL, str_replace(" ","%20", "http://web.dmi.unict.it".$link));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); 

	//print_r(curl_getinfo($ch));

	// recupera l'URL e lo passa al browser
	$html = curl_exec($ch);

	$dom = new DOMDocument;
	$dom->loadHTML($html);

	$xpath = new DOMXPath($dom);
	$infoDocente = $xpath->query("//div[@id='anagrafica']/node() | //div[@id='anagrafica']//a[not(starts-with(@href, 'mailto'))]/@href");
	$name = $xpath->query("//h1[@class='pagetitle']");  
	$mail = $xpath->query("//div[@id='anagrafica']//a[starts-with(@href, 'mailto')]");

	$label = array();
	$prof = new stdClass();

	$prof->linkScheda = str_replace(" ","%20", "http://web.dmi.unict.it".$link);

	if(!is_null($name))
		$prof->Nome = $name[0]->nodeValue;

	if(!is_null($mail))
		$prof->Mail = $mail[0]->nodeValue;

	if (!is_null($infoDocente)) {
	  foreach ($infoDocente as $element) {
	    $nodes = $element->childNodes;
	    foreach ($nodes as $node) {
	    	
	    	//print_r($node);

	    	if($node->tagName)
	    		if($node->tagName == 'b')
					array_push($label, $node->nodeValue);
	    	
	    	if($node->nodeName == '#text')
	    		if($node->nodeValue != ": " && $node->nodeValue != " ")
				$prof->{end($label)} = trim(trim($node->nodeValue,':'));
	    
    	}
	  }
	}

	//Print the json 
	if(count((array)$prof)>1)
		print_r($prof);

	//array_push($proffi, $prof);

	curl_close($ch);
}

//Print the json 
//print json_encode($proffi,JSON_UNESCAPED_UNICODE);

?>
