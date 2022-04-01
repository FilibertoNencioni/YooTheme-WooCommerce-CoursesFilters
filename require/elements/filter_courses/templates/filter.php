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

	$i = 0;
	foreach($Detail as $sNodeDetail) 
	{
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
            if(!in_array(trim($value), $prevAttributes[$index])){
                array_push($attributes[$index], trim($value));
            }
        }
       
		$i = $i + 1;
	}
    return(array_merge_recursive($attributes, $prevAttributes));
}

function getSingleData($htmlContent){
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

	//#Get row data/detail table without header name as key
	$i = 0;
	foreach($Detail as $sNodeDetail) 
	{
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
        array_push($attributes[$index], trim($sNodeDetail->textContent));
        
		$i = $i + 1;
	}
	return($attributes);
}

?>