<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
use YOOtheme\Arr;
use YOOtheme\Str;
require_once'filter.php';

$text_fields = ['title', 'site', 'date', 'price'];

switch ($props['table_order']) {
    case 1:
        $fields = ['image', 'title', 'site', 'date', 'price'];
        break;
    case 2:
        $fields = ['image', 'title', 'date', 'site', 'price'];
        break;
}

// Find empty fields
$filtered = array_values(Arr::filter($fields, function ($field) use ($props, $children) {
    return $props["show_{$field}"] && Arr::some($children, function ($child) use ($field) {
        return $child->props[$field];
    });
}));

$container = $this->el('div', [
    'class'=>[
        'uk-flex',
        'uk-flex-wrap'
    ]
]);

$filterContainer = $this->el('div',[
    'class'=>[
        'uk-flex-first',
        'uk-width-1-4@s',
        'filters'
    ]
]);

$el = $this->el('div', [

    'class' => [
        'uk-overflow-auto {@table_responsive: overflow}',
        'uk-width-expand@s',
        'uk-flex-last',
        'uk-margin-left'
    ],

]);

$table = $this->el('table', [

    'class' => [

        // Style
        'uk-table',
        'uk-table-{table_style}',
        'uk-table-hover {@table_hover}',
        'uk-table-justify {@table_justify}',

        // Size
        'uk-table-{table_size}',

        // Vertical align
        'uk-table-middle {@table_vertical_align}',

        // Responsive
        'uk-table-responsive {@table_responsive: responsive}',
    ],

]);

// TODO: deve prendere anche l'array 'scheletro' 
// in modo da inserire il campo tag-x anche se quel tag non esiste
function printAttrTags($singleAttributes, $allAttributes){
    $tags = "";

    foreach($allAttributes as $key => $saa){
        if(key_exists($key, $singleAttributes)){
            $tags.= " tag-".preg_replace('/\s+/', '-', $key)."='".implode(",",$singleAttributes[$key])."'";
        }else{
            $tags.= " tag-".preg_replace('/\s+/', '-', $key)."=''";
        }
    }

    return $tags;
}

echo $container;
echo $el($props, $attrs);
echo $table($props);

        if (Arr::some($filtered, function ($field) use ($props) { return $props["table_head_{$field}"]; })) : ?>
            <thead>
                <tr>

                    <?php foreach ($filtered as $i => $field) {
                        $lastColumn = $i !== 0 && !isset($filtered[$i + 1]);
                        if($field == "date"){
                            echo $this->el('th', [
                                'id'=>'head-date',
                                'class' => [
                                    // Last column alignment
                                    'uk-text-{table_last_align}[@m {@table_responsive: responsive}]' => $lastColumn,
        
                                    // Text align need to be set for table heading
                                    'uk-text-{text_align}[@{text_align_breakpoint} [uk-text-{text_align_fallback}] {@!text_align: justify}]' => !$lastColumn || !$props['table_last_align'],
                                ],
                                'onclick'=>'sortDate()',
                                'style'=>'cursor:pointer'
                            ], $props["table_head_{$field}"])->render($props);
                        }else{
                            echo $this->el('th', [

                                'class' => [
                                    // Last column alignment
                                    'uk-text-{table_last_align}[@m {@table_responsive: responsive}]' => $lastColumn,
        
                                    // Text align need to be set for table heading
                                    'uk-text-{text_align}[@{text_align_breakpoint} [uk-text-{text_align_fallback}] {@!text_align: justify}]' => !$lastColumn || !$props['table_last_align'],
                                ],
        
                            ], $props["table_head_{$field}"])->render($props);
                        }
                    

                    } ?>

                </tr>
            </thead>
        <?php endif ?>

        <tbody>
            <?php if ($props['enable_filters'] == true): 

                // $attrToShow contiene il nome degli attributi/tag da mostrare tra i filtri
                $attrToShow = [];
                if(Str::length($props['sort_attributes'])){
                    $sortAttributesArr = explode(",",$props['sort_attributes']);
                    foreach($sortAttributesArr as $key =>$singleAttr){
                        $attrToShow[]=trim($singleAttr);
                    }
                }

                //Popolamento dell'array che contiene tutti gli attributi (usato per la ricerca con filtro)           
                $allAttributes = getData($children, $attrToShow);
                // var_dump($allAttributes);
                foreach ($children as $i => $child) :

                    //$singleAttributes contiene tutti gli attributi di un corso
                    $singleAttributes=[];
                    $date = "";
                    $hide = false;
                    if(Str::length($child->props['date'])){
                        $date = stringToDate($child->props['date']);
                        if(strtotime(date('Y-m-d'))>strtotime($date)){
                            $hide = true;
                        }
                    };

                    if(!$hide) : 
                        $link = $child->props['link'];
                        if(Str::length($child->props['attributes'])){
                            //GET ATTRIBUTE OF THE SINGLE COURSE
                            $singleAttributes = getSingleData($child->props['attributes']);
                        }

                        if(Str::length($child->props['site'])){
                            $singleAttributes[DEFAULT_SITE_KEY][] = $child->props['site'];
                        };
                    
                        if(!Str::length($link)) : 
                            if(Str::length($date)) : ?>
                                <tr class="el-item corso" id="corso-<?= $i ?>" <?= printAttrTags($singleAttributes, $allAttributes) ?> tag-calendario="<?= $date ?>"><?= $builder->render($child, ["i" => $i, "element" => $props, "fields" => $fields, "text_fields" => $text_fields, "filtered" => $filtered]) ?></tr>
                            <?php else : ?>
                                <tr class="el-item corso" id="corso-<?= $i ?>" <?= printAttrTags($singleAttributes, $allAttributes) ?>><?= $builder->render($child, ['i' => $i, 'element' => $props, 'fields' => $fields, 'text_fields' => $text_fields, 'filtered' => $filtered]) ?></tr>
                            <?php endif;
                        else :
                            if(Str::length($date)) : ?>
                                <tr class="el-item corso" id="corso-<?= $i ?>" <?= printAttrTags($singleAttributes, $allAttributes) ?> style="cursor:pointer;" onclick="window.location='<?= $link ?>'" tag-calendario="<?=$date?>"><?= $builder->render($child, ['i' => $i, 'element' => $props, 'fields' => $fields, 'text_fields' => $text_fields, 'filtered' => $filtered]) ?></tr>
                            <?php else : ?>
                                <tr class="el-item corso" id="corso-<?= $i ?>" <?= printAttrTags($singleAttributes, $allAttributes) ?> style="cursor:pointer;" onclick="window.location='<?= $link ?>'"><?= $builder->render($child, ['i' => $i, 'element' => $props, 'fields' => $fields, 'text_fields' => $text_fields, 'filtered' => $filtered]) ?></tr>
                            <?php endif;
                        endif;
                    endif;
                endforeach; //FINE POPOLAMENTO TABELLA 
                
            else:  //FILTERED SEARCH ENABLED == FALSE
                foreach ($children as $i => $child) : ?>
                    <tr class="el-item"><?= $builder->render($child, ['i' => $i, 'element' => $props, 'fields' => $fields, 'text_fields' => $text_fields, 'filtered' => $filtered]) ?></tr>
                <?php endforeach;
            endif ?>

        </tbody>
    </table>
</div>

<?php if ($props['enable_filters'] == true) : 
    // RENDER DELLA SEZIONE RICERCA CON FILTRI 
    echo($filterContainer);
    if(count($attrToShow)> 0) :

        //RENDER DEL FILTRO "DATE" SPECIFICANDO LE POSIZIONI
        if(Str::length($date)) :

            $position = -1; 
            foreach($attrToShow as $key=>$attr){
                if(strtolower($attr)=="date"){
                    $position = $key;
                }
            }

            //TODO: nel javascript se datepicker == undefined allora non esegure nessuna operazione a riguardo
            if($position != -1): ?>
                <h3 class="uk-h5 filter-section" data-sort="<?=$position?>">
            
                <?php
                if(Str::length($props['date_search_title'])) :
                    echo $props['date_search_title'];
                else :
                    echo "Cerca disponibilità";
                endif ?>
                <input type="text" id="txtDate" style="width:97%">
                </h3>
            <?php endif;
        endif; //FINE RENDER FILTRO DATE

        //RENDER DEI VARI ATTRIBUTI
        for($i = 0; $i<count($allAttributes);++$i) :
            $index = trim(array_keys($allAttributes)[$i]);
            $position = -1;
            foreach($attrToShow as $key=>$attr){
                if(strtolower($attr)==strtolower($index)){
                    $position = $key;
                }
            }

            if($position != -1) : ?>
                <div class="uk-first-column filter-section uk-margin-small-bottom" data-sort="<?= $position ?>">
                    <div class="uk-card uk-card-body uk-card-default uk-padding-small">
                        <h3 class="uk-h5"> <?= $index==DEFAULT_SITE_KEY? "Sede": $index ?></h3>
                        <div class="uk-flex uk-flex-column filters-<?= strtolower(preg_replace('/\s+/', '-', $index)) ?>">
                            <?php 
                            foreach($allAttributes[$index] as $key => $attr) :
                                $attrvalue = trim($attr->get_name()); 
                                $nTimes = $attr ->get_nTimes();
                                $tagvalue ='tag-'.preg_replace('/\s+/', '-', $index).'="'.preg_replace('/\s+/', '-', $attrvalue).'"';
                                ?>
                
                                <label for="check-<?=$i?>-<?=$key?>"> <input class="uk-checkbox" type="checkbox" <?= $tagvalue?> id="check-<?=$i?>-<?=$key?>"> <?= $attrvalue ?> (<span class="count" id="span-<?=$i?>-<?=$key?>"><?= $nTimes ?></span>)</label>
                            
                            <?php
                            endforeach ?>
                        </div>
                    </div>
                </div>
            <?php
            endif;
        endfor;
    else : // $attrToShow == 0, quindi fare il render con ordine standard: date, tags 
        
        //RENDER DEL FILTRO "DATE" SENSA SPECIFICARE POSIZIONI 
        if(Str::length($date)) : ?>
            <h3 class="uk-h5 filter-section">
            <?php
            if(Str::length($props['date_search_title'])) :
                echo $props['date_search_title'];
            else :
                echo "Cerca disponibilità";
            endif ?>
            <input type="text" id="txtDate" style="width:97%">
            </h3>
        <?php
        endif;

        for($i = 0; $i<count($attributes);++$i) :
            $index = trim(array_keys($attributes)[$i]); ?>

                <div class="uk-first-column filter-section uk-margin-small-bottom">
                    <div class="uk-card uk-card-body uk-card-default uk-padding-small">

                        <h3 class="uk-h5"> <?= $index==DEFAULT_SITE_KEY? "Sede": $index ?></h3>
                        <div class="uk-flex uk-flex-column filters-<?= strtolower(preg_replace('/\s+/', '-', $index)) ?>">
                            <?php 
                            foreach($attributes[$index] as $key => $attr) :
                                $attrvalue = trim($attr->get_name()); 
                                $nTimes = $attr ->get_nTimes();
                                $tagvalue ='tag-'.preg_replace('/\s+/', '-', $index).'="'.preg_replace('/\s+/', '-', $attrvalue).'"';
                                ?>
                
                                <label for="check-<?=$i?>-<?=$key?>"> <input class="uk-checkbox" type="checkbox" <?= $tagvalue?> id="check-<?=$i?>-<?=$key?>"> <?= $attrvalue ?> (<span class="count" id="span-<?=$i?>-<?=$key?>"><?= $nTimes ?></span>)</label>
                            
                            <?php
                            endforeach ?>
                        </div>
                    </div>
                </div>
            <?php
        endfor;
    endif;
    echo($filterContainer->end()); 
    echo($container->end());

endif ?>
