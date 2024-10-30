<?php
defined('CHI_AR_VERSION') or die;

add_action( 'woocommerce_variation_options', 'chiar_product_variation_model', 10, 3 );
function chiar_product_variation_model($loop, $variation_data, $variation )
{
    $chiar=Chiar::getInstance();
//    $product_url=get_the_permalink();
    ?>
    <br/>
    <label for="chiar_variation_model[<?= $loop; ?>]">Choose 3D model for the variation: </label>
    <select current_model="<?= $variation_data['_chiar_model'][$loop]?$variation_data['_chiar_model'][$loop]:'';?>" name="chiar_variation_model[<?= $loop; ?>]" id="chiar_variation_model[<?= $loop; ?>]" class="postbox chiar_variation_model" style="margin-bottom: 0;">
        <option value=''>Select something...</option>
        <? foreach ($chiar->models as $model_id=>$model):?>
            <option value="<?= $model_id; ?>" <?= $model_id==$variation_data['_chiar_model'][$loop]?'selected':''; ?> ><?= $model['name']?$model['name']:$model['usdz']; ?></option>
        <? endforeach;?>
    </select>
    <input type="hidden" class="variation_product_url" id="variation_product_url[<?= $loop; ?>]" value="<?= get_the_permalink();?>">
    <input type="hidden" class="variation_id" id="variation_id[<?= $loop; ?>]" value="<?= $variation->ID; ?>">
    <p class="chiar-variation-notification" >Saved</p>
    <?php
}

//add_action( 'woocommerce_save_product_variation', 'chiar_save_product_variation_model', 10, 2 );
//function chiar_save_product_variation_model( $variation_id, $i ) {
//    $custom_field = $_POST['chiar_variation_model'][$i];
//    if ( isset( $custom_field ) ) update_post_meta( $variation_id, '_chiar_model', esc_attr( $custom_field ) );
//}

add_filter( 'woocommerce_available_variation', 'chiar_set_product_variation_model' );
function chiar_set_product_variation_model( $variations ) {
    global $product;
    if (isset($product)) $variations['_chiar_product_model'] = get_post_meta( $product->get_id(), '_chiar_model', true );
    $variations['_chiar_model'] = get_post_meta( $variations[ 'variation_id' ], '_chiar_model', true );
    $variations['image']['gallery_thumbnail_src']=CHI_AR_URL."assets/img/chiar_img.jpeg";
    return $variations;
}