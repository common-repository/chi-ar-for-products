<?php
defined('CHI_AR_VERSION') or die;

function chiar_product_model()
{
    global $post;
    if (wp_verify_nonce( $_POST['nonce'],'product_model') && chiar_validate_product_model($_POST)){
        $options = get_option( 'chiar_options' );
        $token=$options['chiar_field_token'];
        $args=chiar_getRequestArgs($token);
        $args['method']='PUT';
//        $keepModel= isset($_POST['keepModel']) ? strip_tags($_POST['keepModel']) : false;
//        $addModel= isset($_POST['addModel']) ? strip_tags($_POST['addModel']) : true;
        $variation_id = isset($_POST['variation_id']) ? strip_tags($_POST['variation_id']) : null;
        $product_id = isset($_POST['product_id']) ? strip_tags($_POST['product_id']) : null;

//        if ($_POST['current_model_id']!='' && !$keepModel){
//            $model_id=strip_tags($_POST['current_model_id']);
//            $product_url='';
//            $body=json_encode(['product'=>$product_url]);
//            $url='https://choose-ar.com/model/?token='.$token."&model=".$model_id;
//            $args['body']=$body;
//            $response = wp_remote_request($url, $args);
//            wp_send_json($response['response']['code']); return;
//            if (($response instanceof WP_Error) || $response['response']['code'] != '200') wp_die('Failed!');
//        }
//
//        if ($_POST['model_id']!='' && $addModel){
//            $model_id=strip_tags($_POST['model_id']);
//            $product_url=strip_tags($_POST['product_url']);
//            $body=json_encode(['product'=>$product_url]);
//            $url='https://choose-ar.com/model/?token='.$token."&model=".$model_id;
//            $args['body']=$body;
//            $response = wp_remote_request($url, $args);
//            wp_send_json($response['response']['code']);
//            if (($response instanceof WP_Error) || $response['response']['code'] != '200') wp_die('Failed!');
//        }
        $model_id = strip_tags($_POST['model_id']);
        if ( isset( $model_id ) && isset($variation_id) ) update_post_meta( $variation_id, '_chiar_model', esc_attr( $model_id ) );
        if ( isset($model_id) && isset($product_id) ) update_post_meta($product_id, '_chiar_model', esc_attr($model_id));
        $current_model = $_POST['model_id'];
        wp_send_json(['status'=>'Saved','current_model'=>$current_model,'v_id'=>$variation_id]);
    }
    else{
        wp_die('Failed!');
    }
}
add_action( 'wp_ajax_product_model', 'chiar_product_model' );

function chiar_validate_product_model($post)
{
    $errors = [];

    if (!isset($post['model_id']) || !is_integer($post['model_id'])) {
        $errors[] = 'model_id';
    }

    if (isset($post['current_model_id']) && !is_integer($post['current_model_id'])) {
        $errors[] = 'current_model_id';
    }

    if (isset($post['variation_id']) && !is_integer($post['variation_id'])) {
        $errors[] = 'variation_id';
    }

    if (isset($post['product_id']) && !is_integer($post['product_id'])) {
        $errors[] = 'product_id';
    }

    if ($post['model_id'] == $post['current_model_id']) {
        $errors[] = 'same model_id and current model_id';
    }

    if (!empty($errors)) {
        return false;
    } else {
        return true;
    }
}