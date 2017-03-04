<?php
  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');

  function getDOM($link){
    libxml_use_internal_errors(true);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $html = curl_exec($ch);

    $dom = new DOMDocument;
    $dom->loadHTML($html);

    curl_close($ch);

    return $dom;
  }

  function saveJSON($folder, $filename, $content){
    $fh = fopen($folder . "/" . $filename, 'w+') or die("Can't open file");
    $stringData = json_encode($content, JSON_PRETTY_PRINT);
    fwrite($fh, $stringData);
    fclose($fh);
    //chmod($folder . "/" . $filename, 0777);
  }

  function createFolder($folder){
    if (!file_exists($folder))
      mkdir($folder);

    //chmod($folder, 0777);
  }

  function trimRemoveTab($string){
    return trim(str_replace("\t","", $string), chr(0xC2).chr(0xA0));
  }
?>
