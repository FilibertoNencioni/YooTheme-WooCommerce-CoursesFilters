<?php
use YOOtheme\Arr;
use YOOtheme\Str;

require_once'filter.php';



$text_fields = ['title', 'site', 'date', 'price'];

switch ($props['table_order']) {
    case 1:
        $fields = ['image', 'title', 'site', 'date', 'price', 'link'];
        break;
    case 2:
        $fields = ['image', 'title', 'date', 'site', 'price', 'link'];
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
        'uk-flex'
    ]
]);

$filterContainer = $this->el('div',[
    'class'=>[
        'uk-flex-first',
        'uk-width-1-4@m',
        'filters'
    ]
]);

$el = $this->el('div', [

    'class' => [
        'uk-width-expand@m',
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

function printAttrTags($attributes){
    $tags = "";
    for($i = 0; $i<count($attributes);++$i){
        $index = trim(array_keys($attributes)[$i]);
        $tags.= " tag-".preg_replace('/\s+/', '-', $index)."='".$attributes[$index][0]."'";
    }
    echo $tags;
}

?>

    <?= $container ?>
    <?= $el($props, $attrs) ?>
    <?= $table($props) ?>


        <?php if (Arr::some($filtered, function ($field) use ($props) { return $props["table_head_{$field}"]; })) : ?>
        <thead>
            <tr>

                <?php foreach ($filtered as $i => $field) {

                    $lastColumn = $i !== 0 && !isset($filtered[$i + 1]);

                    echo $this->el('th', [

                        'class' => [
                            // Last column alignment
                            'uk-text-{table_last_align}[@m {@table_responsive: responsive}]' => $lastColumn,

                            // Text align need to be set for table heading
                            'uk-text-{text_align}[@{text_align_breakpoint} [uk-text-{text_align_fallback}] {@!text_align: justify}]' => !$lastColumn || !$props['table_last_align'],

                            // Text nowrap
                            'uk-text-nowrap' => $field == 'link' || in_array($field, $text_fields) && $props["table_width_{$field}"] == 'shrink',
                        ],

                    ], $props["table_head_{$field}"])->render($props);

                } ?>

            </tr>
        </thead>
        <?php endif ?>

        <tbody>

        
        <?php if ($props['enable_filters'] == true): ?>
            <?php
            
                $attributes = getEmptyAttributeArray($children[0]->props['attributes'],$unwanted_array);
                foreach ($children as $i => $child) : ?>
                <?php 
                    if(strlen($child->props['attributes'])>1){
                        //GET ALL ATTRIBUTES           
                        $attributes = getData($child->props['attributes'], $attributes, $unwanted_array);

                        //GET ATTRIBUTE OF THE CHILD PRODUCT
                        $singleAttributes = getSingleData($child->props['attributes'], $unwanted_array);
                    }
                    $link = $child->props['link'];

                ?>
                <?php if(!Str::length($link)) : ?>
                    <tr class="el-item corso" id="corso-<?= $i ?>" <?= printAttrTags($singleAttributes) ?>><?= $builder->render($child, ['i' => $i, 'element' => $props, 'fields' => $fields, 'text_fields' => $text_fields, 'filtered' => $filtered]) ?></tr>

                    <!-- <tr class="el-item" tag-tecnologie="<?= $singleAttributes['Tecnologie'][0]?>" tag-ruolo="<?= $singleAttributes['Ruolo'][0]?>" tag-vendor="<?= $singleAttributes['Vendor'][0]?>" tag-erogazione="<?= $singleAttributes['Modalita di erogazione'][0]?>" tag-durata="<?= $singleAttributes['Durata corso'][0]?>" tag-calendario="<?= $singleAttributes['Calendario'][0]?>" tag-sede="<?= $singleAttributes['Sede'][0]?>" tag-status="<?= $singleAttributes['Status'][0]?>"><?= $builder->render($child, ['i' => $i, 'element' => $props, 'fields' => $fields, 'text_fields' => $text_fields, 'filtered' => $filtered]) ?></tr> -->
                <?php else : ?>
                    <tr class="el-item corso" id="corso-<?= $i ?>" <?= printAttrTags($singleAttributes) ?> style="cursor:pointer;" onclick="window.location='<?= $link ?>'"><?= $builder->render($child, ['i' => $i, 'element' => $props, 'fields' => $fields, 'text_fields' => $text_fields, 'filtered' => $filtered]) ?></tr>

                    <!-- <tr class="el-item" tag-tecnologie="<?= $singleAttributes['Tecnologie'][0]?>" tag-ruolo="<?= $singleAttributes['Ruolo'][0]?>" tag-vendor="<?= $singleAttributes['Vendor'][0]?>" tag-erogazione="<?= $singleAttributes['Modalita di erogazione'][0]?>" tag-durata="<?= $singleAttributes['Durata corso'][0]?>" tag-calendario="<?= $singleAttributes['Calendario'][0]?>" tag-sede="<?= $singleAttributes['Sede'][0]?>" tag-status="<?= $singleAttributes['Status'][0]?>" style="cursor:pointer;" onclick="window.location='<?= $link ?>'"><?= $builder->render($child, ['i' => $i, 'element' => $props, 'fields' => $fields, 'text_fields' => $text_fields, 'filtered' => $filtered]) ?></tr> -->
                <?php endif ?>
            <?php endforeach ?>

        <?php endif ?>

        <?php if ($props['enable_filters'] != true):?>
            <?php foreach ($children as $i => $child) : ?>
                <tr class="el-item"><?= $builder->render($child, ['i' => $i, 'element' => $props, 'fields' => $fields, 'text_fields' => $text_fields, 'filtered' => $filtered]) ?></tr>
            <?php endforeach ?>
        <?php endif ?>


        </tbody>

    </table>

<!-- <?php if ($props['table_responsive'] == 'overflow') : ?> -->
</div>
<!-- <?php endif ?> -->

<?php if ($props['enable_filters'] == true) : ?>
    <?= $filterContainer ?>
    <?php for($i = 0; $i<count($attributes);++$i) : 
        $index = trim(array_keys($attributes)[$i]);
        ?>

        <?php if($i==0) : ?>
            <div class="uk-first-column">
        <?php else : ?>
            <div class="uk-grid-margin uk-first-column">
        <?php endif?>

        <div class="uk-card uk-card-body uk-card-default uk-padding-small">
            <h3 class="uk-h5"><?= $index ?></h3>
            <div class="uk-flex uk-flex-column filters-<?= strtolower(preg_replace('/\s+/', '-', $index)) ?>">
            <?php foreach($attributes[$index] as $attr) :
                $attr = trim($attr); 
                // $tagvalue="tag-".preg_replace('/\s+/', '-', $index)."-value-".preg_replace('/\s+/', '-', $attr);
                $tagvalue ='tag-'.preg_replace('/\s+/', '-', $index).'="'.preg_replace('/\s+/', '-', $attr).'"';
                ?>

                <label><input class="uk-checkbox" type="checkbox" <?= $tagvalue?>> <?= $attr ?> </label>
            <?php endforeach ?>
            <!-- Settare onClick (passa come attributo 'nomeattributo'-'termine' es: tecnologie-cloud oppure ruolo-cloud-engineer) -->
            </div>       
            
        </div>
        </div>
    <?php endfor ?>
    </div>
<?php endif ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script type="text/javascript">
    var classNames = [];
    $('div[class*="filters-"]').each(function(i, el){
        var name = (el.className.match(/(^|\s)(filters\-[^\s]*)/) || [,,''])[2];
        if(name){
            classNames.push(name);
        }
    });

    $(".filters :checkbox").click(function() {
        //Populating the array with all the courses, this is used to see which courses match the attributes
        const corsiDaMostrare = [];
        // const initCorsi = Object.keys($(".corso")).map(function (key) {return $(".corso")[key];}).slice(0,-2);
        // Object.keys($(".corso")).map(function (key) {console.log($(".corso")[key])});
        // for(let i = 0; i < initCorsi.length; i++){
        //     corsiDaMostrare.push({corso: initCorsi[i], ok: true});
        // }
        $(".corso").each(function(){
            corsiDaMostrare.push({corso: $(this).attr('id'), ok:true });
        });

        $(".filters :checkbox:checked").each(function() {
            //Check match of attributes
            for (let i = 0; i < classNames.length; i++) {
                let tag = classNames[i].replace('filters','tag');
                // console.log("searching by tag: "+tag);
                let tagValue = $(this).attr(tag);
                // if(tagValue){
                //     console.log("tagvalue: "+tagValue);
                // }

                if(tagValue){
                    $(".corso").each(function(){
                        let attrFound = false;

                        if($(this).attr(tag)){
                            const selectedCorso = $(this);
                            // console.log({title:"selected corso", selectedCorso});
                            let corsoTagValues = selectedCorso.attr(tag).split(",");
                            for(let j = 0; j<corsoTagValues.length;j++){
                                let name = corsoTagValues[j].trim().replace(" ","-");
                                if(tagValue === name){
                                    attrFound=true;
                                }
                            }

                            if(!attrFound){
                                // console.log("inizio find in !attrFound");

                                let corsoIndex = corsiDaMostrare.findIndex(function(elem){
                                    if(elem.corso === selectedCorso.eq(0).attr('id')){
                                        return elem;
                                    }
                                });
                                if(corsoIndex !== -1){
                                    corsiDaMostrare[corsoIndex].ok = false;
                                }else{
                                    console.log("ERRORE - INDICE IN RICERCA NON TROVATO")
                                }
                            }
                        }

                    })
                }
            }

        });
        console.log(corsiDaMostrare);
        for(let i = 0; i < corsiDaMostrare.length; i++){
            var element = document.getElementById(corsiDaMostrare[i].corso);
            if(corsiDaMostrare[i].ok === false){
                element.classList.add('uk-hidden');
            }else{
                if(element.classList.contains('uk-hidden')){
                    element.classList.remove('uk-hidden');
                }
            }
        }
    });
</script>
