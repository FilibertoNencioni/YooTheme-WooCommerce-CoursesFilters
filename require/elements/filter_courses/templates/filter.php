<?php 

global $unwanted_array;
$unwanted_array = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );



function getData($htmlContent, $prevAttributes, $unwanted_array){
	$DOM = new DOMDocument();
	$DOM->loadHTML($htmlContent);
	
	$Detail = $DOM->getElementsByTagName('td');
    $attributes = getEmptyAttributeArray($htmlContent, $unwanted_array);

	$i = 0;
    $keys = array_keys($attributes);

	foreach($Detail as $sNodeDetail) 
	{
        $index = $keys[$i];
        $exploded = explode(',',$sNodeDetail->textContent);
        foreach($exploded as $value){
            if(!in_array(trim($value), $prevAttributes[$index])){
                array_push($attributes[$index], trim($value));
            }
        }
       
		$i = $i + 1;
	}
    return(array_merge_recursive($attributes, $prevAttributes));
}


function getSingleData($htmlContent, $unwanted_array){
	$DOM = new DOMDocument();
	$DOM->loadHTML($htmlContent);
    $Detail = $DOM->getElementsByTagName('td');
    
    $attributes = getEmptyAttributeArray($htmlContent, $unwanted_array);

	//#Get row data/detail table without header name as key
	$i = 0;
    $keys = array_keys($attributes);
	foreach($Detail as $sNodeDetail) 
	{
        $index = $keys[$i];
        
        array_push($attributes[$index], trim($sNodeDetail->textContent));
        
		$i = $i + 1;
	}
	return($attributes);
}


function getEmptyAttributeArray($htmlContent, $unwanted_array){     
    $DOM = new DOMDocument();
	$DOM->loadHTML($htmlContent);  
    $Header = $DOM->getElementsByTagName('th');
    $aDataTableHeaderHTML = [];

    //#Get header name of the table
    foreach($Header as $NodeHeader) 
    {
        $aDataTableHeaderHTML[] = strtr(utf8_decode(trim($NodeHeader->textContent)),$unwanted_array);
    }
    $attributes = array_fill_keys($aDataTableHeaderHTML, array());
    return $attributes;
}
?>