$(".close").on("click", function() { 
	var current_elem = $(this);
	$.ajax({
        url : 'ajax_delete_product.php',
        type : 'post',
        data : { id: $(this).attr('data-id') },
        success : function (res) {
            try {
                res = $.parseJSON(res);
                if (res.success == true) {
                	current_elem.parents('.bloc_product').remove();
                    
                }
            } catch (e) {
                console.error("parseJSON");

                return;
            }
        }
    });
});