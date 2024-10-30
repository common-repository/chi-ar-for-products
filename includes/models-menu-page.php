<?php
defined('CHI_AR_VERSION') or die;
function chiar_models_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    $chiar=Chiar::getInstance();
//    $options = get_option( 'chiar_options' );
//    $token=$options['chiar_field_token'];
//    $args=getRequestArgs($token);
//    $response = wp_remote_get('https://choose-ar.com/api/models', $args);
//    $models=[];
//    if (!($response instanceof WP_Error) && $response['response']['code'] == '200') {
//        $data = json_decode($response['body'], true);
//        foreach ($data as $dat){
//                $models['products'][$dat['id']] = $dat['product'];
//                $models['pathes'][$dat['id']] = $dat['path'];
//                $models['names'][$dat['id']] = $dat['name'];
//        }
//    }
    ?>
    <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <? if (!empty($chiar->models)):?>
            <ul>
            <? $n=1; ?>
            <? foreach ($chiar->models as $model_id=>$model):?>
                <li>
                    <span><?= $n.". "; ?></span>
                    <span><?= $model['name']?$model['name']:$model['usdz']; ?></span><br />
                    <?
                    if ($model['product']!=''):
                        $product_arr=explode('/',$model['product']);
                        $product_obj = get_page_by_path($product_arr[sizeof($product_arr)-2] , OBJECT, 'product' );
                        ?>
                        <span><a href="<?= $model['product'];?>"><?= $model['product'];?></a></span>
                    <? endif; ?>
                </li>
            <?$n++;?>
            <? endforeach; ?>
            </ul>
        <?else:?>
            <p>There are no models.</p>
        <? endif; ?>

    </div>
    <?php
}

function chiar_models_page()
{
    add_submenu_page(
        'chiar',
        'CHI.AR Models',
        'Your models',
        'manage_options',
        'chiarmodels',
        'chiar_models_page_html'
    );
}
add_action('admin_menu', 'chiar_models_page');