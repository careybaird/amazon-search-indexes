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

//		foreach($dom->getElementsByTagName('td') as $table)
//		{
//			echo $table->getElementsByTagName('')
//		}

		$thirdTable = $dom->getElementsByTagName('table')->item(1);

		foreach($thirdTable->getElementsByTagName('tr') as $tr)
		{
//			echo 'row';
//echo $tds->length;
			$tds = $tr->getElementsByTagName('td'); // get the columns in this row
			if($tds->length >= 4)
			{
				// check if B and D are found in column 2 and 4
//				if(trim($tds->item(1)->nodeValue) == 'B' && trim($tds->item(3)->nodeValue) == 'D')
//				{
					// found B and D in the second and fourth columns
					// echo out each column value
//					echo $tds->item(0)->nodeValue; // A
//					echo $tds->item(1)->nodeValue; // B

				$array[$locale][] = trim($tds->item(1)->nodeValue);
//					echo $tds->item(2)->nodeValue; // C
//					echo $tds->item(3)->nodeValue; // D
//					break; // don't check any further rows
//				}
			}
		}



//		exit;
//        foreach($dom->getElementsByTagName('td') as $node)
//        {
//            $text = trim($node->nodeValue);
//            $text = trim($text);
//            if(strlen($text)<=2) continue;
//            if (substr($text, 0, 1)=='-') continue;
////            if (strstr($text, '-') AND !strstr($text, 'Blu-ray')) continue;
//            if (strstr($text, 'Documentation')) continue;
//            if (strstr($text, 'PercentageOff')) continue;
//            if (strstr($text, 'songtitle')) continue;
//            if (strstr($text, 'Amazon Web Services')) continue;
//            if (strstr($text, 'AWS Documentation')) continue;
//            if (strstr($text, 'Document Conventions')) continue;
//            if (strstr($text, 'Terms of Use')) continue;
//            if (strstr($text, 'Did this page help')) continue;
//            if (strstr($text, 'Previous')) continue;
//            if (strstr($text, '.pdf')) continue;
//            if (strstr($text, '<img')) continue;
//            if (strstr($text, '<p')) continue;
//            if (strstr($text, 'padding')) continue;
//            if (strstr($text, 'salesranktitlerank')) continue;
//            if (strstr($text, 'Search index')) continue;
//            if (strstr($text, 'Availability')) continue;
//            if (strstr($text, '-price')) continue;
//            if (strstr($text, 'Â')) continue;
//            if (trim($text)=='') continue;
//            if ($text==' ') continue;
//            if (is_numeric($text)) continue;
//
//			if ($flag){
//				$array[$locale][] = trim($text);
//			}
//
//			$flag = !$flag;
//        }
    }

    $sql =  '-- Update Search Indexes at http://docs.freshdevelopment.org/searchindexes/ <br>';

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