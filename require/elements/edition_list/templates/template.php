<?php
try {
    $productID = wc_get_product()->get_id();
    $productTitle = wc_get_product()->get_title();

    $url = "http://one.wordpress.test/wp-json/wp/v2/date"; // CHANGE THIS WITH YOUR URL
    
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    //for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $resp = curl_exec($curl);
    curl_close($curl);
    $dates = json_decode($resp);
    
    foreach($dates as $date){
        if($date->acf->corso_erogato == $productID){
            $edition = new stdClass();
            $edition->site = $date->acf->sede;
    
            //SETTING DATE FORMAT
            $dateOnly = $date->acf->data;
            $formattedDate = sprintf("%s/%s/%s", substr($dateOnly, -2), substr($dateOnly, 4,2), substr($dateOnly,0, 4));
    
            $edition->date = $formattedDate;
            $editions[]=$edition;
        }
    }
} catch (\Throwable $th) {
    //throw $th;
    throw new Exception("Error during curl operation in editions list", 1);
    $editions=[];
    
}

$form = $this->el("form",[
    "class"=>[
        "uk-flex",
        "uk-flex-column",
        "uk-flex-wrap",
        "uk-flex-wrap-between"
    ],
    "id"=>"edition-form"
]);

$container = $this->el("div", [
    'class' => [
        'el-item',
        $props['title-m-top'], 
        $props['title-m-bottom']
    ],
]);

$title = $this->el($props['title-size'],[]);
?>

<!-- DISPLAY LIST ITEM -->
<?php if(count($editions)>0) : ?>
    <?= $container($attrs) ?>
        <?= $title ?> <?= $props['title'] ?> <?= $title->end() ?>
        <?= $form ?>
            <?php foreach ($editions as $key => $edition) : ?>
                <label><input class="uk-radio" type="radio" name="edition" value="<?= $edition->date ?> - <?= $edition->site ?>"<?php if($key == 0) :?> checked <?php endif ?>> <?= $edition->date ?> - <?= $edition->site ?> </label>
            <?php endforeach ?>
        <?= $form->end() ?>
    <?= $container->end() ?>
<?php else : ?>
    <?= $container($attrs) ?>
        <?= $title ?> <?= $props['no-edition-title'] ?> <?= $title->end() ?>
    <?= $container->end() ?>
<?php endif ?>


<!-- CONTACT FORM INTERATION -->
<?php if($props["use-cf7"] && $props['cf7-course'] != NULL && $props['cf7-edition'] != NULL) :?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        var courseNameID = <?= json_encode($props["cf7-course"]) ?>;
        var editionID = <?= json_encode($props["cf7-edition"]) ?>;

        $(document).ready(function(){
            //SET INPUT AS DISABLED (USER CANNOT MODIFY VALUE)
            $("."+courseNameID+" > input").attr("disabled",true);
            $("."+editionID+" > input").attr("disabled",true);

            $("."+courseNameID+" > input").val(<?= json_encode($productTitle )?>);
            $("."+editionID+" > input").val($("input[name=edition]:checked").val());
            $("input[type=radio][name=edition]").change(function(){
                $("."+editionID+" > input").val($("input[name=edition]:checked").val());
            });
        });
    </script>
<?php endif ?>


