<?php
$editions = [];
$productID = wc_get_product()->get_id();

$url = "http://one.wordpress.test/wp-json/wp/v2/date";

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

        //SETTING DATA FORMAT
        $dateOnly = $date->acf->data;
        $formattedDate = sprintf("%s/%s/%s", substr($dateOnly, -2), substr($dateOnly, 4,2), substr($dateOnly,0, 4));

        $edition->date = $formattedDate;
        $editions[]=$edition;
    }
}

$el = $this->el("div", [
    'class' => [
        'el-item',
    ],
]);

$title = $this->el($props['title-style'],[]);

?>

<?php if(count($editions)>0) : ?>
    <?= $el($attrs) ?>
        <?= $title ?> <?= $props['title'] ?> <?= $title->end() ?>
        <form>
            <?php foreach ($editions as $key => $edition) : ?>
                <p> <?= $edition->date ?> - <?= $edition->site ?> </p>
            <?php endforeach ?>
        </form>
    <?= $el->end() ?>
<?php else : ?>
    <?= $title ?> <?= $props['no-edition-title'] ?> <?= $title->end() ?>
<?php endif ?>

<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->


