<?php 


function getData($htmlContent, $prevAttributes){
    $attributes = array(
        'Tecnologie'=>array(),
        'Ruolo'=>array(),
        'Vendor'=>array(),
        'Modalita di erogazione'=>array(),
        'Durata corso'=>array(),
        'Calendario'=>array(),
        'Sede'=>array(),
        'Status'=>array()
    );

	$DOM = new DOMDocument();
	$DOM->loadHTML($htmlContent);
	
	$Header = $DOM->getElementsByTagName('th');
	$Detail = $DOM->getElementsByTagName('td');

    //#Get header name of the table
	foreach($Header as $NodeHeader) 
	{
		$aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
	}
	//print_r($aDataTableHeaderHTML); die();

	//#Get row data/detail table without header name as key
	$i = 0;
	$j = 0;
	foreach($Detail as $sNodeDetail) 
	{
        // var_dump($i);
        $index = '';
        switch($i){
            case 0:
                $index = 'Tecnologie';
                break;
            case 1:
                $index = 'Ruolo';
                break;
            case 2:
                $index ='Vendor';
                break;
            case 3:
                $index = 'Modalita di erogazione';
                break;
            case 4:
                $index = 'Durata corso';
                break;
            case 5:
                $index = 'Calendario';
                break;
            case 6:
                $index = 'Sede';
                break;
            case 7:
                $index = 'Status';
                break;
            default:
                $index = '';
                break;
        }


        $exploded = explode(',',$sNodeDetail->textContent);
        foreach($exploded as $value){
            // var_dump($value." ".$index." ".!in_array($value, $attributes[$index])." ".$i);
            if(!in_array(trim($value), $prevAttributes[$index])){
                array_push($attributes[$index], trim($value));
                // $attributes[$index][] = $value;
            }
        }
       
		// $aDataTableDetailHTML[$i][] = trim($sNodeDetail->textContent);
		$i = $i + 1;
		// $j = $i % count($aDataTableHeaderHTML) == 0 ? $j + 1 : $j;
	}
	// return($attributes);
    return(array_merge_recursive($attributes, $prevAttributes));
}

// function insertData($res){
//     foreach()
// }
?>