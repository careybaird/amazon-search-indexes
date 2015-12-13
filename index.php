<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

<?php

echo '<pre>';
ini_set('display_errors', 1);
error_reporting(E_ALL);

    $regions = array('CA', 'CN', 'DE', 'ES', 'FR', 'IN', 'IT', 'JP', 'UK', 'US', 'BR');
//    $regions = array('DE');

    foreach ($regions as $locale) {
//        $url = 'http://docs.aws.amazon.com/AWSECommerceService/latest/DG/'.$locale.'SearchIndexParamForItemsearch.html';
        $url = 'http://docs.aws.amazon.com/AWSECommerceService/latest/DG/Locale'.$locale.'.html';
//		echo $url;
        $string = file_get_contents($url);

        $dom = new DOMDocument;
        @$dom->loadHTML($string);

		$flag = false;

		$thirdTable = $dom->getElementsByTagName('table')->item(1);

		foreach($thirdTable->getElementsByTagName('tr') as $tr)
		{
			$tds = $tr->getElementsByTagName('td'); // get the columns in this row
			if($tds->length >= 4)
			{
				$array[$locale][] = trim($tds->item(1)->nodeValue);
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