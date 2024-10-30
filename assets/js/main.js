jQuery(window).load(function($){
    const ALLOW_AR = 1;
    const ALLOW_360 = 2;
    const ALLOW_TRY_ON = 3;
    ver = iOSversion();
    ua = navigator.userAgent.toLowerCase();
    isAndroid = ua.indexOf("android") > -1;
    $=jQuery;
    if ($('.variations_form').length) {
        var tmp = JSON.parse($('.variations_form').attr('data-product_variations'));
        var variation_data = {};
        tmp.forEach(function (item) {
            variation_data[item.variation_id] = item;
        });

        $('.variations_form').on('found_variation.wc-variation-form',function(e) {
            e.preventDefault();
            let current_variation = variation_data[$(this).find('.variation_id').val()];
            let iframe_main;
            let iframe_modal;
            let model_id='';
            if (current_variation._chiar_model !== '')
            model_id = current_variation._chiar_model;
            else if (current_variation._chiar_product_model !== '')
            model_id = current_variation._chiar_product_model;
            if (model_id!=='')
            {
                iframe_main = "<iframe src='https://choose-ar.com/cart-frame/?model="+model_id+"&token="+params.token+"' scrolling='no'></iframe>";
                iframe_modal = "<iframe src='https://choose-ar.com/cart-frame-modal/?model="+model_id+"&token="+params.token+"' scrolling='no'></iframe>";
                $('#chiar_main_image').html(iframe_main);
                $('#threedmodel-modal').find('iframe').parent().html(iframe_modal);
                if ($('#chi-glyph-usdz').length) $('#chi-glyph-usdz').attr('href',usdz[model_id]);
                if ($('#chi-image-usdz').length) $('#chi-image-usdz').attr('href',usdz[model_id]);
            }
        });

        $('.variations_form').on('reset_data',function(e){
            let model_id='';
            let current_variation = tmp[0];
            if (current_variation._chiar_product_model !== '')
                model_id = current_variation._chiar_product_model;
            if (model_id!=='')
            {
                iframe_main = "<iframe src='https://choose-ar.com/cart-frame/?model="+model_id+"&token="+params.token+"' scrolling='no'></iframe>";
                iframe_modal = "<iframe src='https://choose-ar.com/cart-frame-modal/?model="+model_id+"&token="+params.token+"' scrolling='no'></iframe>";
                $('#chiar_main_image').html(iframe_main);
                $('#threedmodel-modal').find('iframe').parent().html(iframe_modal);
                if ($('#chi-glyph-usdz').length) $('#chi-glyph-usdz').attr('href',usdz[model_id]);
                if ($('#chi-image-usdz').length) $('#chi-image-usdz').attr('href',usdz[model_id]);
            }
        });
    }
    if (typeof params!== 'undefined'){
        if ($('.woocommerce-product-gallery').length && params.product=='yes') {
            let width = $('.woocommerce-product-gallery__image').width();
            let height = $('.woocommerce-product-gallery__image').height();

            if (ver && ver[0] >= 12 && params.user_functions.includes(ALLOW_AR)) {
                if (params.arglyph)
                    $('.woocommerce-product-gallery').prepend('<a id="chi-glyph-usdz" href="' + params.usdz + '" class="glyph_' + params.vert + ' glyph_' + params.hor + '-ar chi-glyph-usdz" rel="ar"><img src="' + params.arglyphpath + '"></a>');
                if (params.arimage && params.image_action === 'ar'){
                    $('.woocommerce-product-gallery').prepend('<a id="chi-image-usdz" href="' + params.usdz + '" rel="ar"><img src="' + jQuery('.woocommerce-product-gallery__image').attr('data-thumb') + '" style="opacity:0;"></a>');
                    $('#chi-image-usdz').width(width);
                    $('#chi-image-usdz').height(height);
                }
            } else if (isAndroid && params.user_functions.includes(ALLOW_AR)) {
                const url = params.gltf.replace('https://','');
                const name = params.name;
                const sceneViewerUrl = 'intent://'+url+'?title='+name+'#Intent;scheme=https;package=com.google.android.googlequicksearchbox;action=android.intent.action.VIEW;end;';
                if (params.arglyph) {
                    $('.woocommerce-product-gallery').prepend('<a id="chi-glyph-usdz" href="'+sceneViewerUrl+'" class="glyph_' + params.vert + ' glyph_' + params.hor + '-ar chi-glyph-usdz"><img src="' + params.arglyphpath + '"></a>');
                }

                if (params.arimage && params.image_action === 'ar'){
                    $('.woocommerce-product-gallery').prepend('<a id="chi-image-usdz" href="'+sceneViewerUrl+'" ><img src="' + jQuery('.woocommerce-product-gallery__image').attr('data-thumb') + '" style="opacity:0;"></a>');
                    $('#chi-image-usdz').width(width);
                    $('#chi-image-usdz').height(height);
                }
            }

            if (params.d3glyph && params.user_functions.includes(ALLOW_360)) {
                    $('.woocommerce-product-gallery').prepend('<a id="chi-glyph-popup" href="#" class="glyph_' + params.vert + ' glyph_' + params.hor + ' chi-glyph-popup" data-izimodal-open="#threedmodel-modal"><img src="' + params.d3glyphpath + '"></a>');
                    if (params.image_action === '3d') {
                        $('.woocommerce-product-gallery').prepend('<a id="chi-image-popup" href="#" data-izimodal-open="#threedmodel-modal"></a>');
                        $('#chi-image-popup').width(width);
                        $('#chi-image-popup').height(height);
                    }
            }

            if (params.tryglyph && params.user_functions.includes(ALLOW_TRY_ON)) {
                $('.woocommerce-product-gallery').prepend('<a id="chi-glyph-try" href="https://choose-ar.com/tryon?model='+params.id+'&token='+params.token+'" class="glyph_' + params.vert + ' glyph_' + params.hor + '-try chi-glyph-try"><img src="' + params.tryglyphpath + '"></a>');
            }
        }
        else if (params.product='no'){
            arr=params.models;
            jQuery('.product').each(function() {
                ref=jQuery(this).find('img').parent('a').next('a');
                product_id = ref.data('product_id');
                if (params.products[product_id] !== undefined){
                    index=Object.keys(arr.ids).find(key => arr.ids[key] == params.products[product_id]['model']);
                    if (index !== undefined && ver && ver[0]>=12 && arr.user_functions[index].includes(ALLOW_AR)){
                        ref.parent().prepend('<a href="'+arr.usdzs[index]+'" class="ar-glyph-shop" rel="ar"><img src="'+params.arglyphpath+'"></a>');
                    } else if (index !== undefined && isAndroid && arr.user_functions[index].includes(ALLOW_AR)) {
                        const url = arr.gltfs[index].replace('https://','');
                        const name = arr.names[index];
                        const sceneViewerUrl = 'intent://'+url+'?title='+name+'#Intent;scheme=https;package=com.google.android.googlequicksearchbox;action=android.intent.action.VIEW;end;';
                        $('.woocommerce-product-gallery').prepend('<a id="chi-glyph-usdz" href="'+sceneViewerUrl+'" class="glyph_' + params.vert + ' glyph_' + params.hor + '-ar chi-glyph-usdz"><img src="' + params.arglyphpath + '"></a>');
                        ref.parent().prepend('<a class="ar-glyph-shop" href="'+sceneViewerUrl+'" data-id="'+arr.ids[index]+'"><img src="'+params.arglyphpath+'"></a>');
                    }
                    if (index !== undefined && arr.user_functions[index].includes(ALLOW_360)) {
                        ref.parent().prepend('<a href="#" class="threed-glyph-shop" data-id="'+arr.ids[index]+'" onclick="changeThreedIframe(this)"><img src="'+params.d3glyphpath+'"></a>');
                    }
                    if (index !== undefined && arr.tryglyphs[index] && arr.user_functions[index].includes(ALLOW_TRY_ON)) {
                        ref.parent().prepend('<a href="https://choose-ar.com/tryon?model='+arr.ids[index]+'&token='+params.token+'" class="try-glyph-shop" data-id="'+arr.ids[index]+'"><img src="'+params.tryglyphpath+'"></a>');
                    }
                }
            });
        }

        if (jQuery("#threedmodel-modal").length) {
            jQuery("#threedmodel-modal").iziModal({
                bodyOverflow: true,
                width: 1900,
                iframe: true,
                iframeHeight: 800,
                zindex: 1100,
                bodyOverflow: true,
                transitionIn: 'fadeInUp',
                transitionOut: 'fadeOutDown',
                overlayColor: 'rgba(0, 0, 0, 0.35)',
            });
        }

    }
});

function iOSversion() {
    if (/iP(hone|od|ad)/.test(navigator.platform)) {
        var v = (navigator.appVersion).match(/OS (\d+)_(\d+)_?(\d+)?/);
        return [parseInt(v[1], 10), parseInt(v[2], 10), parseInt(v[3] || 0, 10)];
    }
}

function changeThreedIframe(element) {
    jQuery('#threedmodel-modal').iziModal('open');
    url = jQuery('#threedmodel-modal').find('iframe').attr('src');
    jQuery('#threedmodel-modal').find('iframe').attr('src', url.replace(/(model=).*?(&)/,'$1' + element.dataset.id + '$2'));
}