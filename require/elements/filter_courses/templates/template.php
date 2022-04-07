<style>
.event a {
    background-color: #42B373 !important;
    background-image :none !important;
    color: #ffffff !important;
}

</style>

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
        'uk-overflow-auto {@table_responsive: overflow}',
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
        if(count($attributes[$index])>0){
            $tags.= " tag-".preg_replace('/\s+/', '-', $index)."='".$attributes[$index][0]."'";
        }else{
            $tags.= " tag-".preg_replace('/\s+/', '-', $index)."=''";
        }
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
                        ],

                    ], $props["table_head_{$field}"])->render($props);

                } ?>

            </tr>
        </thead>
        <?php endif ?>

        <tbody>

        
        <?php if ($props['enable_filters'] == true): 

                $attrToHide = [];
                if(Str::length($props['sort_attributes'])){
                    $sortAttributesArr = explode(",",$props['sort_attributes']);
                    foreach($sortAttributesArr as $key =>$singleAttr){
                        // $singleAttr = str_replace(" ","-",trim($singleAttr));
                        $attrToHide[]=trim($singleAttr);
                    }
                }

                $attributes = getEmptyAttributeArray($children[0]->props['attributes']);
                foreach ($children as $i => $child) : ?>
                <?php 
                    if(Str::length($child->props['attributes'])){
                        //GET ALL ATTRIBUTES           
                        $attributes = getData($child->props['attributes'], $attributes);

                        //GET ATTRIBUTE OF THE CHILD PRODUCT
                        $singleAttributes = getSingleData($child->props['attributes']);
                    }
                    $date = "";
                    if(Str::length($child->props['date'])){
                        $date = stringToDate($child->props['date']);
                    };

                    
                    if(Str::length($child->props['site'])){
                        $attributes = insertSite($attributes,$child->props['site']);
                    };

                    $link = $child->props['link'];

                ?>
                <?php if(!Str::length($link)) : ?>
                    <?php if(Str::length($date)) : ?>
                        <tr class="el-item corso" id="corso-<?= $i ?>" <?= printAttrTags($singleAttributes) ?> tag-calendario="<?= $date ?>"><?= $builder->render($child, ['i' => $i, 'element' => $props, 'fields' => $fields, 'text_fields' => $text_fields, 'filtered' => $filtered]) ?></tr>
                    <?php else : ?>
                        <tr class="el-item corso" id="corso-<?= $i ?>" <?= printAttrTags($singleAttributes) ?>><?= $builder->render($child, ['i' => $i, 'element' => $props, 'fields' => $fields, 'text_fields' => $text_fields, 'filtered' => $filtered]) ?></tr>
                    <?php endif ?>

                <?php else : ?>
                    <?php if(Str::length($date)) : ?>
                        <tr class="el-item corso" id="corso-<?= $i ?>" <?= printAttrTags($singleAttributes) ?> style="cursor:pointer;" onclick="window.location='<?= $link ?>'" tag-calendario="<?=$date?>"><?= $builder->render($child, ['i' => $i, 'element' => $props, 'fields' => $fields, 'text_fields' => $text_fields, 'filtered' => $filtered]) ?></tr>
                    <?php else : ?>
                        <tr class="el-item corso" id="corso-<?= $i ?>" <?= printAttrTags($singleAttributes) ?> style="cursor:pointer;" onclick="window.location='<?= $link ?>'"><?= $builder->render($child, ['i' => $i, 'element' => $props, 'fields' => $fields, 'text_fields' => $text_fields, 'filtered' => $filtered]) ?></tr>
                    <?php endif ?>

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

</div>

<?php if ($props['enable_filters'] == true) : ?>
    <?= $filterContainer ?>

    <?php if(Str::length($date)) : ?>
        <?php if(count($attrToHide)> 0) : 
        $position = 0;
            foreach($attrToHide as $key=>$attr){
                if(strtolower($attr)=="date"){
                    $position = $key+1;
                }
            }
            ?>
            <?php if($position == 0) : ?>
                <h3 class="uk-h5 uk-hidden filter-section">
            <?php else : ?>
                <h3 class="uk-h5 filter-section" data-sort="<?=$position?>" >
            <?php endif ?>
        <?php else : ?>
            <h3 class="uk-h5 filter-section">
        <?php endif ?>

            <?php if(Str::length($props['date_search_title'])) : ?>
                <?= $props['date_search_title'] ?>
            <?php else : ?>
                Cerca disponibilità
            <?php endif ?>
        <input type="text" id="txtDate" style="width:97%"></h3>
    <?php endif ?>

    <?php for($i = 0; $i<count($attributes);++$i) : 
        $index = trim(array_keys($attributes)[$i]);
        ?>
        <?php if(count($attrToHide)> 0) : 
            $position = 0;
            foreach($attrToHide as $key=>$attr){
                if(strtolower($attr)==strtolower($index)){
                    $position = $key+1;
                }
            }
         ?>
            
            <?php if($position == 0) : ?>
                <div class="uk-first-column filter-section uk-margin-small-bottom uk-hidden">
            <?php else : ?>
                <div class="uk-first-column filter-section uk-margin-small-bottom" data-sort="<?= $position ?>">
            <?php endif ?>
            
        <?php else: ?>
            <div class="uk-first-column filter-section uk-margin-small-bottom"> 
        <?php endif ?>


        <div class="uk-card uk-card-body uk-card-default uk-padding-small">
        
            <h3 class="uk-h5"><?= $index ?></h3>
            <div class="uk-flex uk-flex-column filters-<?= strtolower(preg_replace('/\s+/', '-', $index)) ?>">
            <?php 
            foreach($attributes[$index] as $key => $attr) :
                $attrvalue = trim($attr->get_name()); 
                $nTimes = $attr ->get_nTimes();
                $tagvalue ='tag-'.preg_replace('/\s+/', '-', $index).'="'.preg_replace('/\s+/', '-', $attrvalue).'"';
                ?>

                <label for="check-<?=$i?>-<?=$key?>"> <input class="uk-checkbox" type="checkbox" <?= $tagvalue?> id="check-<?=$i?>-<?=$key?>"> <?= $attrvalue ?> (<span class="count" id="span-<?=$i?>-<?=$key?>"><?= $nTimes ?></span>)</label>
                
            <?php endforeach ?>
            </div>       
            
        </div>
        </div>
    <?php endfor ?>
    </div>
<?php endif ?>


<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
    var daysAvailable = {};

    var elems = $(".filter-section");
    elems.sort(function(a, b) {
        return a.getAttribute('data-sort') > b.getAttribute('data-sort')
    }).appendTo(elems.parent());

    $.fn.dataStartsWith = function(p) {
        var pCamel = p.replace(/-([a-z])/ig, function(m,$1) { return $1.toUpperCase(); });
        return this.filter(function(i, el){
            return Object.keys(el.dataset).some(function(v){
            return v.indexOf(pCamel) > -1;
            });
        });
    };
    
    var classNames = [];
    $('div[class*="filters-"]').each(function(i, el){
        var name = (el.className.match(/(^|\s)(filters\-[^\s]*)/) || [,,''])[2];
        if(name){
            classNames.push(name);
        }
    });

    
    function getDays(){
        daysAvailable = [];
        $(".corso").each(function(){
            if(!$(this).hasClass("uk-hidden")){
                var date = new Date($(this).attr("tag-calendario"));
                date.setHours(0,0,0,0);
                daysAvailable[date] = date.toString();
            }
        });
    }

    function initDatepicker(){
        getDays();
        // datepicker
        $('#txtDate').datepicker({
            showButtonPanel: true,
            closeText: 'Svuota',
            onClose: function (dateText, inst) {
                if ($(window.event.srcElement).hasClass('ui-datepicker-close')) {
                    document.getElementById(this.id).value = '';
                    checkCorsi();
                }
            },
            beforeShowDay: function( date ) {
                var highlight = daysAvailable[date];
                if( highlight ) {
                    return [true, "event", "Uno o più corsi sono disponibili in questo giorno"]; 
                } else {
                    return [false, ''];
                }
            },
            onSelect: ()=>checkCorsi()
        });
    }

    
        

    
    function checkCorsi(){
        //Populating the array with all the courses, this is used to see which courses match the attributes
        const corsiDaMostrare = [];

        $(".corso").each(function(){
            corsiDaMostrare.push({corso: $(this).attr('id'), ok:true});
        });
        $(".filters :checkbox:checked").each(function() {
            //Check match of attributes
            for (let i = 0; i < classNames.length; i++) {
                let tag = classNames[i].replace('filters','tag');
                let tagValue = $(this).attr(tag);

                if(tagValue){
                    $(".corso").each(function(){
                        let attrFound = false;

                        
                        if($(this).attr(tag)){
                            const selectedCorso = $(this);
                            let corsoTagValues = selectedCorso.attr(tag).split(",");
                            for(let j = 0; j<corsoTagValues.length;j++){
                                let name = corsoTagValues[j].trim().replaceAll(" ","-");
                                if(tagValue === name){
                                    attrFound=true;
                                }
                            }

                            if(!attrFound){
                                let corsoIndex = corsiDaMostrare.findIndex(function(elem){
                                    if(elem.corso === selectedCorso.eq(0).attr('id')){
                                        return elem;
                                    }
                                });
                                if(corsoIndex !== -1){
                                    corsiDaMostrare[corsoIndex].ok = false;
                                }else{
                                    console.log("ERRORE - INDICE IN RICERCA NON TROVATO");
                                }
                            }
                        }

                    })
                }
            }
        });
        var date =$("#txtDate").val();
        if(date){
            var dataDatepicker = date.split("/");
            var joinedDataDatepicker = dataDatepicker[2]+"-"+dataDatepicker[0]+"-"+dataDatepicker[1];
            
            $(".corso").each(function(){
                if($(this).attr("tag-calendario")!==joinedDataDatepicker){
                    var selectedCorso = $(this);
                    let corsoIndex = corsiDaMostrare.findIndex(function(elem){
                        if(elem.corso === selectedCorso.eq(0).attr('id')){
                            return elem;
                        }
                    });
                        if(corsoIndex !== -1){
                            corsiDaMostrare[corsoIndex].ok = false;
                        }else{
                            console.log("ERRORE - INDICE IN RICERCA NON TROVATO");
                        }
                    }
            });
                    
        }
        for(let i = 0; i < corsiDaMostrare.length; i++){
            var element = document.getElementById(corsiDaMostrare[i].corso);

            //Hide courses that don't match the filters and hide the filters with 0 courses shown 
            if(corsiDaMostrare[i].ok === false){
                if(!element.classList.contains('uk-hidden')){
                    element.classList.add('uk-hidden');
                    classNames.forEach(function(value){
                        let tagPrefix = value.replaceAll("filters","tag");
                        let currentTags=$(element).attr(tagPrefix).split(", ");
                        currentTags.forEach(function(value){
                            value = value.replaceAll(" ","-");                            
                            $('.filters :checkbox').each(function(){
                                if($(this).attr(tagPrefix)==value.trim()){
                                    var label = $("label[for='"+this.id+"']");
                                    var span = label.children('span');
                                    var spanValue = parseInt(span.text())-1;
                                    span.text(spanValue);
                                    if(spanValue === 0){
                                        label.addClass("uk-hidden");
                                    }
                                }
                            });
                        })
                    });
                }

            //Show courses and filters
            }else{
                if(element.classList.contains('uk-hidden')){
                    element.classList.remove('uk-hidden');
                    classNames.forEach(function(value){
                        let tagPrefix = value.replace("filters","tag");
                        let currentTags=$(element).attr(tagPrefix).split(", ");
                        currentTags.forEach(function(value){
                            value = value.replaceAll(" ","-");
                            
                            $('.filters :checkbox').each(function(){
                                if($(this).attr(tagPrefix)==value.trim()){
                                    var label = $("label[for='"+this.id+"']");
                                    var span = label.children('span');
                                    var spanValue = parseInt(span.text())+1;
                                    span.text(spanValue);
                                    if(spanValue > 0){
                                        label.removeClass("uk-hidden");
                                    }
                                }
                            });
                        })
                    });
                }
            }
            getDays();
        }
    }

    $(".filters :checkbox").click(function() {
        checkCorsi();
    });


    
</script>

<?php if(Str::length($date)) : ?>
    <script type="text/javascript">
    //initialize datepicker
    initDatepicker();

    </script>
<?php endif ?>

