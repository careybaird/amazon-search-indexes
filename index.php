<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

<?php

echo '<pre>';
ini_set('display_errors', 1);
error_reporting(E_ALL);

    //$regions = array('CA', 'CN', 'DE', 'ES', 'FR', 'IN', 'IT', 'JP', 'UK', 'US', 'BR');
    $regions = array('AU'=>'australia','BR'=>'brazil','CA'=>'canada','FR'=>'france','DE'=>'germany','IN'=>'india','IT'=>'italy','JP'=>'japan','MX'=>'mexico','ES'=>'spain','TR'=>'turkey','AE'=>'united-arab-emirates','UK'=>'united-kingdom','US'=>'united-states');
    //$regions = array('AU'=>'australia');

    foreach ($regions as $localeKey=>$localePage) {
        $url = 'https://webservices.amazon.com/paapi5/documentation/locale-reference/'.$localePage.'.html';
        $string = file_get_contents($url);

        $dom = new DOMDocument;
        @$dom->loadHTML($string);

		$flag = false;

		$thirdTable = $dom->getElementsByTagName('table')->item(2);

		foreach($thirdTable->getElementsByTagName('tr') as $tr)
		{
            $tds = $tr->getElementsByTagName('td');
            if($tds->item(0))
			{
				$array[$localeKey][] = trim($tds->item(0)->nodeValue);
			}
		}
    }
    $sql =  '-- Script to refresh at https://github.com/sjalgeo/amazon-search-indexes <br>';

    $sql .=  'TRUNCATE TABLE searchindexes;<br>';
    $sql .= 'INSERT INTO `searchindexes` (`locale`, `searchindex`) VALUES <br>';

    foreach ($array as $locale => $searchIndexes){

        foreach($searchIndexes as $index){
            if ($locale=='US') $locale='USA';
            $sql .= "('".$locale."','".$index."'),<br>";
        }
    }

    $sql = substr($sql, 0, -5);
    $sql .= ";";

    echo '<pre>';
    echo $sql;
