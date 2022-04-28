<?php
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
   
?>

<!-- CONTACT FORM INTERATION -->
<?php if($props["use-cf7"] && $props['cf7-service-name'] != NULL && $props['cf7-category'] != NULL) :?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        var serviceNameID = <?= json_encode($props["cf7-service-name"]) ?>;
        var categoriesID = <?= json_encode($props["cf7-category"]) ?>;

        $(document).ready(function(){
            //SET INPUT AS DISABLED (USER CANNOT MODIFY VALUE)
            $("."+serviceNameID+" > input").attr("disabled",true);
            $("."+categoriesID+" > input").attr("disabled",true);

            $("."+serviceNameID+" > input").val(<?= json_encode($productTitle )?>);
            $("."+categoriesID+" > input").val(<?= json_encode($productCat)?>);
        });
    </script>
<?php endif ?>