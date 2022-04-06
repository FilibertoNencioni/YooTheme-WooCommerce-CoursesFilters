<?php 

define("UNWANTED_ARRAY",array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' ));


define("DEFAULT_SITE_KEY","Site");
class Attribute{
    public $name;
    public $nTimes;

    function set_name($name){
        $this->name = $name;
    }

    function set_nTimes($nTimes){
        $this->nTimes = $nTimes;
    }

    function get_name() {
        return $this->name;
    }

    function get_nTimes() {
        return $this->nTimes;
    }
}

function getData($htmlContent, $prevAttributes){
	$DOM = new DOMDocument();
	$DOM->loadHTML($htmlContent);
	
	$Detail = $DOM->getElementsByTagName('td');

	$i = 0;
    $keys = array_keys($prevAttributes);

	foreach($Detail as $sNodeDetail) 
	{
        $index = $keys[$i];
        $exploded = explode(',',$sNodeDetail->textContent);

        foreach($exploded as $value){
            $innerIndex = -1;
            if(count($prevAttributes[$index])>0){
                $attrFoundIndex = 0;
                foreach($prevAttributes[$index] as $attr){
                    if($attr->get_name() == trim($value)){
                        $innerIndex = $attrFoundIndex;
                    }
                    $attrFoundIndex++;
                }
            }
            
            if($innerIndex == -1){
                $newAttribute = new Attribute();
                $newAttribute -> set_name(trim($value));
                $newAttribute -> set_nTimes(1);
                array_push($prevAttributes[$index], $newAttribute);
            }else{
                $prevTimes = $prevAttributes[$index][$innerIndex]->get_nTimes()+1;
                $prevAttributes[$index][$innerIndex]->set_nTimes($prevTimes);
            }
        }
       
		$i = $i + 1;
	}
    return $prevAttributes;
}


function getSingleData($htmlContent){
	$DOM = new DOMDocument();
	$DOM->loadHTML($htmlContent);
    $Detail = $DOM->getElementsByTagName('td');
    
    $attributes = getEmptyAttributeArray($htmlContent, UNWANTED_ARRAY);

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


function getEmptyAttributeArray($htmlContent){     
    $DOM = new DOMDocument();
	$DOM->loadHTML($htmlContent);
    $Header = $DOM->getElementsByTagName('th');
    $aDataTableHeaderHTML = [];

    //#Get header name of the table
    foreach($Header as $NodeHeader) 
    {
        $aDataTableHeaderHTML[] = strtr(utf8_decode(trim($NodeHeader->textContent)),UNWANTED_ARRAY);
    }
    $aDataTableHeaderHTML[]=DEFAULT_SITE_KEY;
    $attributes = array_fill_keys($aDataTableHeaderHTML, array());
    return $attributes;
}


/**
 * Transform a string with format (DD "mese" YYYY) to a date (layout: ITALIANO) (format YYYY-MM-DD)
 *
 * @param string $stringDate  The string that will be converted
 
 * @return Date The string converted to date
 */ 
function stringToDate($stringDate){
    //input: dd "mese" YYYY
    $month = "";
    $arraydate = explode(" ", $stringDate);
    switch($arraydate[1]){
        case "Gennaio":
            $month = "01";
            break;
        case "Febbario":
            $month = "02";
            break;
        case "Marzo":
            $month = "03";
            break;
        case "Aprile":
            $month = "04";
            break;
        case "Maggio":
            $month = "05";
            break;
        case "Giugno":
            $month = "06";
            break;
        case "Luglio":
            $month = "07";
            break;
        case "Agosto":
            $month = "08";
            break;
        case "Settembre":
            $month = "09";
            break;
        case "Ottobre":
            $month = "10";
            break;
        case "Novembre":
            $month = "11";
            break;
        case "Dicembre":
            $month = "12";
            break;
    }

    $formattedDate = $arraydate[2]."-".$month."-".$arraydate[0];
    return($formattedDate);
}

function insertSite($attributes, $site){
    
    
    $innerIndex = -1;

    if(count($attributes[DEFAULT_SITE_KEY])>0){
        $attrFoundIndex = 0;
        foreach($attributes[DEFAULT_SITE_KEY] as $attr){
            if($attr->get_name() == $site){
                $innerIndex = $attrFoundIndex;
            }
            $attrFoundIndex++;
        }
    }

    if($innerIndex == -1){
        $newAttribute = new Attribute();
        $newAttribute -> set_name($site);
        $newAttribute -> set_nTimes(1);
        array_push($attributes[DEFAULT_SITE_KEY], $newAttribute);
    }else{
        $prevTimes = $attributes[DEFAULT_SITE_KEY][$innerIndex]->get_nTimes()+1;
        $attributes[DEFAULT_SITE_KEY][$innerIndex]->set_nTimes($prevTimes);
    }

    return $attributes;
}
?>