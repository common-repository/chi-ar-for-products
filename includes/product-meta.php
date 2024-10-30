<?php
defined('CHI_AR_VERSION') or die;
// Product metabox
function chiar_product_model_box()
{
    add_meta_box(
        'chiar_product_model_box',           // Unique ID
        'CHI.AR 3D model',  // Box title
        'chiar_product_model_box_cb',  // Content callback, must be of type callable
        'product',                   // Post type
        'side',
        'low'
    );
}
add_action('add_meta_boxes', 'chiar_product_model_box');

function chiar_product_model_box_cb($post)
{
    $chiar=Chiar::getInstance();
    $product_url=get_the_permalink();
    $product_model_id=get_post_meta(get_the_ID(),'_chiar_model',true);
    if (!isset($product_model_id)) $product_model_id='';
    $key = ($product_model_id!='' && $chiar->models[$product_model_id]['product']==$product_url)?$product_model_id:'';
    ?>
    <label for="chiar_product_model_field">Choose model for the product</label>
    <select current_model="<?= $key;?>" name="chiar_product_model_field" id="chiar_product_model_field" class="postbox" style="margin-bottom: 0;">
        <option value=''>Select something...</option>
        <? foreach ($chiar->models as $model_id=>$model):?>
            <? if ($model['product']==$product_url || $model['product']==''):?>
                <option value="<?= $model_id; ?>" <?= $model_id==$product_model_id?'selected':''; ?> ><?= $model['name']?$model['name']:$model['usdz']; ?></option>
            <? endif; ?>
        <? endforeach;?>
    </select>
    <input type="hidden" id="product_url" value="<?= get_the_permalink();?>">
    <input type="hidden" id="product_id" value="<?= get_the_ID();?>">
    <p class="chiar-notification">Saved</p>
    <?php
}
