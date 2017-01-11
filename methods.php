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
?>
