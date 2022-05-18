<?php
try {
    $productID = wc_get_product()->get_id();
    $productTitle = wc_get_product()->get_title();
    $productCatList =  get_the_terms($productID,'product_cat');
    $productCat = "";

    foreach($productCatList as $key =>$cat){
        if($key != 0){
            $productCat = $productCat.", ".$cat->name;
        }else{
            $productCat = $cat->name;
        }
    }
    
    $editions = [];
    $page = 1;
    do {
        // $url = "http://127.0.0.1/wp-json/wp/v2/date?_fields=acf&per_page=10&page=".$page; // CHANGE THIS WITH YOUR URL
        $url = "https://one.wordpress.test/wp-json/wp/v2/date?_fields=acf&per_page=10&page=".$page;  //TEST
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        $resp = curl_exec($curl);
        curl_close($curl);
        $dates = json_decode($resp);
        
        $isOver = false;
        if(!isset($dates->code)){
            foreach($dates as $date){
            
                if($date->acf->corso_erogato == $productID){
                    $edition = new stdClass();
                    $edition->site = $date->acf->sede;
            
                    //SETTING DATE FORMAT
                    $dateOnly = $date->acf->data;
                    $formattedDate = sprintf("%s/%s/%s", substr($dateOnly, -2), substr($dateOnly, 4,2), substr($dateOnly,0, 4));
                    $edition->date = $formattedDate;
    
                    $dateForCheck = sprintf("%s-%s-%s",substr($dateOnly,0, 4), substr($dateOnly, 4,2), substr($dateOnly, -2));
                    $edition->hide = false;
    
                    $dateTimestamp1 = strtotime(date('Y-m-d'));
                    $dateTimestamp2 = strtotime($dateForCheck);
                    if($dateTimestamp1>$dateTimestamp2){
                        $edition->hide = true;
                    }
    
                    $editions[]=$edition;
                }
            }
        }else{
            $isOver = true;
        }
        
        
        $page++;
        
    } while (!$isOver);

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

function checkIsEmpty($editionsList){
    if(!isset($editionsList)){
        return true;
    }
    $count = 0;
    foreach($editionsList as $edition){
        if($edition->hide == false){
            $count++;
        }
    }
    if($count == 0){
        return true;
    }else{
        return false;
    }
}


$title = $this->el($props['title-size'],[]);
?>


<!-- DISPLAY LIST ITEM -->
<?php if(!checkIsEmpty($editions)) : ?>
    <?= $container($attrs) ?>
        <?= $title ?> <?= $props['title'] ?> <?= $title->end() ?>
        <?= $form ?>
            <?php foreach ($editions as $key => $edition) : ?>
                <?php if($edition->hide == false) : ?>
                    <label><input class="uk-radio" type="radio" name="edition" value="<?= $edition->date ?> - <?= $edition->site ?>"> <?= $edition->date ?> - <?= $edition->site ?> </label>
                <?php endif ?>
            <?php endforeach ?>
        <?= $form->end() ?>
    <?= $container->end() ?>
<?php else : ?>
    <?= $container($attrs) ?>
        <?= $title ?> <?= $props['no-edition-title'] ?> <?= $title->end() ?>
    <?= $container->end() ?>
<?php endif ?>


<!-- CONTACT FORM INTERATION -->
<?php if($props["use-cf7"] && $props['cf7-course'] != NULL && $props['cf7-edition'] != NULL && $props['cf7-category'] != NULL) :?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        var courseNameID = <?= json_encode($props["cf7-course"]) ?>;
        var editionID = <?= json_encode($props["cf7-edition"]) ?>;
        var categoriesID = <?= json_encode($props["cf7-category"]) ?>;

        $(document).ready(function(){
            //SET INPUT AS DISABLED (USER CANNOT MODIFY VALUE)
            $("."+courseNameID+" > input").attr("disabled",true);
            $("."+editionID+" > input").attr("disabled",true);
            $("."+categoriesID+" > input").attr("disabled",true);

            $("."+courseNameID+" > input").val(<?= json_encode($productTitle )?>);
            $("."+editionID+" > input").val($("input[name=edition]:checked").val());
            $("."+categoriesID+" > input").val(<?= json_encode($productCat)?>);

            $("input[type=radio][name=edition]").change(function(){
                $("."+editionID+" > input").val($("input[name=edition]:checked").val());
            });
        });
    </script>
<?php endif ?>


<!-- TEST-->
 <!--<?php
    $request = new WP_REST_Request( 'GET', '/wp/v2/posts' );
    $request->set_query_params( [ 'per_page' => 12 ] );
    $response = rest_do_request( $request );
    $server = rest_get_server();
    $data = $server->response_to_data( $response, false );
    $json = wp_json_encode( $data );
    //"cURL error 60: SSL: no alternative certificate subject name matches target host name 'one.wordpress.test'" <- Requests_exception
?>  --> 