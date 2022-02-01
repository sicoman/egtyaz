$(function(){

    function errorPart(error){
        return `<span class="invalid-feedback" role="alert"><strong>${error}</strong></span>`;
    }


    $('.validate-form').on("submit",function(){ 
        $(this).find('input, select').each(function(){  
            if($(this).data('required')){  
                let isCheck = $(this).attr("type") == "checkbox" ? true : false;
                if((!isCheck && !$(this).val().length) || (isCheck && !$(this).prop("checked"))){
                    if(!$(this).hasClass('is-invalid')){
                        if($(this).data('container')){
                            $(this).parents($(this).data('container')).first().append(errorPart($(this).data('message') ? $(this).data('message') : "عفوا هذا الحقل مطلوب"));
                        }else{
                            $(this).after(errorPart("عفوا هذا الحقل مطلوب"));
                        }

                        $(this).addClass('is-invalid');
                    }
                }else{  console.log($(this));
                    if($(this).data('container')){
                        $(this).parents($(this).data('container')).find('.invalid-feedback').remove();
                    }else{
                        $(this).parent().find('.invalid-feedback').remove();
                    }
                    $(this).removeClass('is-invalid');
                }
            } 
        });

        if($(this).find('.is-invalid').length){
            return false ;
        }

        return true; 
    });

});