<?php 
use YOOtheme\Str;

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

function nestedLowercase($value) {
    if (is_array($value)) {
        return array_map('nestedLowercase', $value);
    }
    return strtolower($value);
}

function insertAttribute($attrName, $attrValue, $attributes){
    //INSERIMENTO DELLE KEY (es. Tecnologie, ruolo, etc..)
    if(key_exists($attrName,$attributes) == false){
        $attributes[$attrName] = array();
    }

    //INSERIMENTO DEI VALORI
    $attrValueExploded = explode(", ", $attrValue);
    foreach ($attrValueExploded as $singleValue) {
        $found = false;
        foreach ($attributes[$attrName] as $value) {
            if($value->get_name() == trim($singleValue)){
                $found = true;
                $value->set_nTimes($value->get_nTimes()+1);
            }
        }

        if(!$found){
            $newAttribute = new Attribute();
            $newAttribute -> set_name(trim($singleValue));
            $newAttribute -> set_nTimes(1);
            $attributes[$attrName][] = $newAttribute;
        }
    }
    return $attributes;
}

function insertSite($course, $attributes){
    if(Str::length($course->props['site'])){
        $found = false;
        foreach ($attributes[DEFAULT_SITE_KEY] as $value) {
            if($value->get_name() == trim($course->props['site'])){
                $found = true;
                $value->set_nTimes($value->get_nTimes()+1);
            }
        }

        if(!$found){
            $newAttribute = new Attribute();
            $newAttribute -> set_name(trim($course->props['site']));
            $newAttribute -> set_nTimes(1);
            $attributes[DEFAULT_SITE_KEY][] = $newAttribute;
        }
    }
    return $attributes;
}

function getData($courses, $attrToShow){
	$DOM = new DOMDocument();
    $attributes = [DEFAULT_SITE_KEY=>array()];

    $attrToShow = array_map('strtolower', $attrToShow);

    foreach ($courses as $course) {
        //CONTROLLO DELLA DATA DEL CORSO (se è passato allora non visualizzare)
        $hide = false;
        if(Str::length($course->props['date'])){
            $date = stringToDate($course->props['date']);
            if(strtotime(date('Y-m-d'))>strtotime($date)){
                $hide = true;
            }
        };

        if(!$hide){
            $DOM->loadHTML($course->props['attributes']);
            $rows = $DOM->getElementsByTagName("tr");
            foreach($rows as $row){
                $rowElements = $row->childNodes;
                $attrName = strtr(utf8_decode(trim($rowElements[1]->nodeValue)),UNWANTED_ARRAY);
                $attrValue = strtr(utf8_decode(trim($rowElements[3]->nodeValue)),UNWANTED_ARRAY);
                
                if(count($attrToShow) > 0){
                    if(array_search(strtolower($attrName), $attrToShow) != false){
                        $attributes = insertAttribute($attrName, $attrValue, $attributes);
                    }
                }else{
                    $attributes = insertAttribute($attrName, $attrValue, $attributes);
                }
                
            }
    
    
            //INSERIMENTO SITE
            if(count($attrToShow) > 0){
                if(array_search(strtolower(DEFAULT_SITE_KEY), $attrToShow) != false){
                    $attributes =  insertSite($course, $attributes);
                }
            }else{
                $attributes =  insertSite($course, $attributes);
            }
        }
        
    }
	
    return $attributes;
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
 
 * @return string The string converted to date
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


?>