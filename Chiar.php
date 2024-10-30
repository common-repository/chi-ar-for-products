<?php

class Chiar
{
    const NODES = array(
        'models'=>'https://choose-ar.com/all-models'
    );
    const CHIAR_LOGO = "assets/img/chiar_img.png";
    const THREEDGLYPH = "assets/img/3d-glyph.svg";
    const ARGLYPH = "assets/img/ar-glyph.svg";
    const TRYGLYPH = "assets/img/try-glyph.svg";

    const ALLOW_AR = 1;
    const ALLOW_360 = 2;
    const ALLOW_TRY_ON = 3;

    private static $instance;
    private $js_params=array();

    public $options=array();
    public $models=array();

    public static function getInstance()
    {
        if (null === static::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        // User token for CHI.AR service
        global $product;
        $this->options = get_option( 'chiar_options' );
        $token=$this->options['chiar_field_token'];
        $args=$this->getRequestArgs($token);
        // Trying to get user models from server.
        $response = wp_remote_get(self::NODES['models'] . '?token=' . $token, $args);
        /**
         *   Exit if request failed
         */
        if (($response instanceof WP_Error) || $response['response']['code'] != '200') return;
        $data = json_decode($response['body'], true);
        foreach ($data as $dat){
            $this->models[$dat['id']]=array(
                //'product'=>$dat['product'],
                'usdz'=>$dat['usdz'],
                'obj'=>$dat['obj'],
                'gltf'=>$dat['gltf'],
                'name'=>$dat['name'],
                'fb_model'=>$dat['fb_model'],
                'features'=>$dat['features'],
                'try_features' => $dat['try_features'],
                'user_functions' => $dat['user_functions'] ? $dat['user_functions'] : []
            );
        }

        $this->add_actions();
    }
    private function __clone(){}
    private function __wakeup(){}

    //adding all actions
    private function add_actions(){
        add_action('admin_enqueue_scripts', array('Chiar','load_admin_scripts'));
        add_action('wp_enqueue_scripts', array('Chiar','load_scripts'));
        add_action('wp_footer', array($this,'main_script'));
        //add_action('wp_head',array($this,'setup_fb_init'),99);
        //add_action('wp_footer',array($this,'setup_fbDialog_script'),5);

        add_filter( 'woocommerce_single_product_image_thumbnail_html', array($this,'model_first'), 10, 2 );

        //meta tags actions
        add_action('wp_head',array($this,'set_meta_tags_action'),1);

    }

    //load admin scripts
    public static function load_admin_scripts(){
        wp_enqueue_script( 'ajax-product-model', CHI_AR_URL . 'assets/js/ajax-add-model.js',array(),false,true);
        wp_localize_script( 'ajax-product-model', 'product_model', array(
            'url'=> admin_url( 'admin-ajax.php' ),
            'nonce'=>wp_create_nonce( 'product_model' )
        ));
        wp_register_style('chiar',CHI_AR_URL."assets/css/chiar.css");
        wp_enqueue_style('chiar');
    }

    public function set_meta_tags_action(){
        if (function_exists('is_product') && is_product()){
            $key=$this->get_current_model();

            if ($key && $this->models[$key]['fb_model']){
                if ($this->is_plugin_active('wordpress-seo/wp-seo.php')) {
                    add_filter('wpseo_opengraph_type', array($this,'yoast_change_opengraph_type'), 99, 1);
                    add_action('wpseo_opengraph', array($this,'og_add_3dmodel'));
                    add_action('wpseo_opengraph_image', array($this,'og_change_image'), 10, 1);
                }
                else{
                    add_action('wp_head',array($this,'put_og_meta_tags'), 2);
                }
            }
        }
    }

    //load user scripts
    public static function load_scripts(){
        wp_register_script('iziModal', CHI_AR_URL."assets/js/iziModal.min.js", array( 'jquery'));
        wp_enqueue_script( 'iziModal' );

        wp_register_style('iziModal', CHI_AR_URL."assets/css/iziModal.min.css");
        wp_enqueue_style('iziModal');
        wp_register_style('chiar', CHI_AR_URL."assets/css/chiar.css");
        wp_enqueue_style('chiar');
    }

    //Args for server request
    private function getRequestArgs($token)
    {
        global $wp_version;
        $args = array(
            'timeout' => 5,
            'redirection' => 5,
            'httpversion' => '1.0',
            'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url(),
            'blocking' => true,
            'headers' => array(
                'accept-encoding' => 'gzip, deflate',
                'content-type' => 'application/json',
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . $token,
            ),
            'cookies' => array(),
            'body' => null,
            'compress' => false,
            'decompress' => true,
            'sslverify' => true,
            'stream' => false,
            'filename' => null
        );
        return $args;
    }

    //Set modal
    private function set_modal($key, $type)
    {?>
        <div class="modal" id="threedmodel-modal" data-izimodal-iframeURL="https://choose-ar.com/<?= $type; ?>/?model=<?= $key; ?>&token=<?= $this->options['chiar_field_token']?>">
            <button class="modal-close" data-izimodal-close="">
                <span></span>
                <span></span>
            </button>
        </div>
    <?}

    //Set modal android
    private function set_android_modal()
    {?>
        <div id="android-modal">
            <div class="iframe_wrapper" >
                <iframe scrolling='no'></iframe>
            </div>
        </div>
    <?}

    private function get_current_model(){
        $product_url=get_permalink( get_the_id() );
        $product_model_id=get_post_meta(get_the_id(),'_chiar_model',true);
        if (!isset($product_model_id)) $product_model_id='';
        //$key = ($product_model_id!='' && $this->models[$product_model_id]['product']==$product_url)?$product_model_id:false;
        $key = ($product_model_id != '') ? $product_model_id : false;
        return $key;
    }

    //Setting 3d model instead of feature image on product page
    public function model_first($html, $post_thumbnail_id){
        $chiar_img_regex="\"".plugin_dir_url(__FILE__).self::CHIAR_LOGO."\"";
        $home_url=preg_replace('/(\/)/','\\/',get_home_url());
        global $product;
        if ($product->get_image_id()==$post_thumbnail_id && $this->options['chiar_field_3dcheck']!='popup' && $this->options['chiar_field_3dcheck']!='none') {
            $product_url=get_permalink( $product->get_id() );
            $product_model_id=get_post_meta($product->get_id(),'_chiar_model',true);
            if (!isset($product_model_id)) $product_model_id='';
            //$key = ($product_model_id!='' && $this->models[$product_model_id]['product']==$product_url)?$product_model_id:false;
            $key = ($product_model_id != '') ? $product_model_id : false;
            if ($key && $this->models[$key]['gltf']) {
                $replacement="
                <div id='chiar_main_image'>
                        <iframe src='https://choose-ar.com/cart-frame/?model=".$key."&token=".$this->options['chiar_field_token']."' scrolling='no'></iframe>
                </div>
                ";
                //Deleting main image class
                $html=preg_replace('/wp-post-image/','',$html);
                //Adding 3d element to gallery
                $new_el=wc_get_gallery_image_html($post_thumbnail_id, true);
                $new_el = preg_replace('/([\"\\\']'.$home_url.')[^\"\\\']*([\"\\\'])/',$chiar_img_regex,$new_el);
                $new_el = preg_replace('/(<img)[^>]*(>)/',$replacement,$new_el);
                $html =  $new_el . $html;
            }
        }
        return $html;
    }

    public function main_script(){
        global $product;
        $product_url=chiar_getCurrentPage();
        //Initial params for plugin script
        $js_params=array(
            'token' => $this->options['chiar_field_token'],
            'product' => '',
            'arglyph' => $this->options['chiar_field_glyphcheck']!='image',
            'd3glyphpath' => plugin_dir_url( __FILE__ ) . self::THREEDGLYPH,
            'arglyphpath' => plugin_dir_url( __FILE__ ) . self::ARGLYPH,
            'tryglyphpath' => plugin_dir_url( __FILE__ ) . self::TRYGLYPH,
            'vert' => $this->options['chiar_field_glyphv']!=''?$this->options['chiar_field_glyphv']:'top',
            'hor' => $this->options['chiar_field_glyphh']!=''?$this->options['chiar_field_glyphh']:'left'
        );

        $key=null;
        if (is_product() && !empty($this->models)){
            $product_model_id= get_post_meta($product->get_id(),'_chiar_model',true);
            $key = $product_model_id;
            //if (!isset($product_model_id) || $this->models[$key]['product']!=$product_url) $key=null;
            if (!isset($product_model_id)) $key=null;
        }
        wp_enqueue_script( 'chi-ar-main', plugin_dir_url(__FILE__) . 'assets/js/main.js',array('jquery'),false,true);

        //Additinal params and enqueueing of script for product page
        if (is_product() && isset($key)) {
            $js_params['product']='yes';
            $type = ($this->models[$key]['features'] != null && $this->models[$key]['features'] != '') ? 'featured-view' : 'cart-frame-modal';
            if ($this->options['chiar_field_3dcheck']!='product' && $this->options['chiar_field_3dcheck']!='none' && $this->models[$key]['gltf']!="") $this->set_modal($key, $type);
            $js_params['usdz']=$this->models[$key]['usdz'];
            $js_params['gltf']=$this->models[$key]['gltf'];
            $js_params['name']=$this->models[$key]['name'];
            $js_params['id']=$key;
            $js_params['user_functions']=$this->models[$key]['user_functions'];
            $js_params['arimage']=$this->options['chiar_field_glyphcheck']!='glyph';
            $js_params['image_action']=$this->options['chiar_field_image_action'];
            $js_params['d3glyph']=!($this->options['chiar_field_3dcheck']=='product' || $this->options['chiar_field_3dcheck']=='none' || $this->models[$key]['gltf']=="");
            $js_params['tryglyph'] = !is_null($this->models[$key]['try_features']);
            wp_localize_script( 'chi-ar-main', 'params', $js_params);
        }
        //Additinal params and enqueueing of script for not product page
        elseif (!is_product() && $this->options['chiar_field_glyphcheck']!='image'){
            $this->set_modal(0, 'featured-view');
            $js_params['products'] = $this->getProductsWithModels();
            $js_params['product']='no';
            $js_params['models']=$this->modelsToJS($this->models);
            $js_params['d3glyph']=!($this->options['chiar_field_3dcheck']=='product' || $this->options['chiar_field_3dcheck']=='none');
            wp_localize_script( 'chi-ar-main', 'params', $js_params);
        }


    }

    public function modelsToJS($models){
        $modelsjs=array();
        foreach($models as $id => $model){
            //$modelsjs['products'][]=$model['product'];
            $modelsjs['usdzs'][]=$model['usdz'];
            $modelsjs['fb_models'][]=$model['fb_model'];
            $models['objects'][]=isset($model['obj'])?$model['obj']:"";
            $modelsjs['gltfs'][]=isset($model['gltf'])?$model['gltf']:"";
            $modelsjs['names'][]=isset($model['name'])?$model['name']:"";
            $modelsjs['ids'][]=$id;
            $modelsjs['user_functions'][]=isset($model['user_functions'])?$model['user_functions']:[];
            $modelsjs['tryglyphs'][] = !is_null($model['try_features']);
        }
        return $modelsjs;
    }

    public function getProductsWithModels() {
        $result = array();
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_chiar_model',
                    'value' => '',
                    'compare' => '!='
                )
            )
        );
        $loop = new WP_Query( $args );
        if ( $loop->have_posts() ) {
            while ( $loop->have_posts() ) : $loop->the_post();
                $result[get_the_ID()] = [
                    'model' => get_post_meta(get_the_ID(),'_chiar_model',true),
                    'permalink' => get_the_permalink()
                ];
            endwhile;
        } else {
            echo __( 'No products found' );
        }
        wp_reset_postdata();
        return $result;
    }
    // wp-admin plugin activation checking
    private function is_plugin_active( $plugin ) {
        return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || $this->is_plugin_active_for_network( $plugin );
    }

    public function is_plugin_active_for_network( $plugin ) {
        if ( !is_multisite() )
            return false;

        $plugins = get_site_option( 'active_sitewide_plugins');
        if ( isset($plugins[$plugin]) )
            return true;

        return false;
    }
    //wordpress-seo/wp-seo.php - yoast seo path
}