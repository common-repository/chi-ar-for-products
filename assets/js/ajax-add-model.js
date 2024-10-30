jQuery(function ($) {
    $(document).on('change','#chiar_product_model_field',function () {
        var keepOnVariation = 0;
        var addOnVariation = 1;
        var select = $(this);
        if (select.children("option:selected").val()==='') addOnVariation=0;
        $('.chiar_variation_model').each(function(){
            if (select.attr('current_model')!=='' && select.attr('current_model')===$(this).attr('current_model')) keepOnVariation=1;
            if (select.children("option:selected").val()===$(this).attr('current_model')) addOnVariation=0;
        });
        var data = {
            action: 'product_model',
            nonce : product_model.nonce,
            product_url: $(this).parent().find('#product_url').val(),
            model_id: $(this).children("option:selected").val(),
            current_model_id: $(this).attr('current_model'),
            keepModel: keepOnVariation,
            addModel: addOnVariation,
            product_id: $(this).parent().find('#product_id').val()
        };
        $.ajax({
            url:product_model.url,
            data :data,
            type:'POST',
            dataType:'json',
            beforeSend:function(xhr){
            },
            success:function(rspdata){
                console.log(rspdata);
                $('#chiar_product_model_field').attr('current_model',rspdata.current_model);
                $(this).attr('current_model',data.model_id);
                $('.chiar-notification').show();
                setTimeout(function () {
                    $('.chiar-notification').hide();
                },2500);
            },
            error:function(jqXHR, textStatus, errorThrown){
                console.log(textStatus);
            }
        });
    });

    $(document).on('change','.chiar_variation_model',function () {
        var keepOnVariation = 0;
        var addOnVariation = 1;
        var select = $(this);
        $('.chiar_variation_model').each(function(){
            if (select.attr('current_model')!=='' && select.attr('id')!=$(this).attr('id') && select.attr('current_model')===$(this).attr('current_model')) keepOnVariation=1;
            if (select.children("option:selected").val()!=='' && select.children("option:selected").val()===$(this).attr('current_model')) addOnVariation=0;
        });
        if (select.attr('current_model')!=='' && select.attr('current_model')===$('#chiar_product_model_field').attr('current_model')) keepOnVariation=1;
        if (select.children("option:selected").val()!=='' && select.children("option:selected").val()===$('#chiar_product_model_field').attr('current_model')) addOnVariation=0;
        var data = {
            action: 'product_model',
            nonce : product_model.nonce,
            product_url: select.parent().find('.variation_product_url').val(),
            model_id: select.children("option:selected").val(),
            current_model_id: select.attr('current_model'),
            keepModel: keepOnVariation,
            addModel: addOnVariation,
            variation_id: select.parent().find('.variation_id').val()
        };
        $.ajax({
            url:product_model.url,
            data :data,
            type:'POST',
            dataType:'json',
            beforeSend:function(xhr){
            },
            success:function(rspdata){
                console.log(rspdata);
                select.attr('current_model',data.model_id);
                select.parent('p').next('.chiar-variation-notification').show();
                setTimeout(function () {
                    select.parent('p').next('.chiar-variation-notification').hide();
                },2500);
            },
            error:function(jqXHR, textStatus, errorThrown){
                console.log(textStatus);
            }
        });
    });
});