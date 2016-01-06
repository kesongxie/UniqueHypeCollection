function removeAttribute(thisE){
	var attribute_row = $(thisE).parents('.attribute-row');
	var data_attribute_row_id = attribute_row.attr('data-attribute-row-id');
	$.ajax({
		url:AJAXDIR+'delete_attribute.php',
		method:'post',
		data:{data_attribute_row_id:data_attribute_row_id},
		success:function(resp){
			attribute_row.remove();
			resetDialog();
		}
	});
}





					
function readURL(input,tg) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			tg.attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}




function loadPic(thisE){
	var parentDiv = $('#popup-dialog');
	var imgTarget = parentDiv.find('.attribute-thumb');
	readURL(thisE,imgTarget);
	parentDiv.find('#upload-overlay').addClass('hdn');
}

function promptAlertMessageAtDialogBox(message){
	$('#popup-dialog').find('.alert-message').html(message).removeClass('hdn');
}

function addProduct(){
	var parentDiv = $('#popup-dialog');
	var title = parentDiv.find('#new-product-title').val();
	var description = parentDiv.find('#new-product-desc').val();
	var price =  parentDiv.find('#new-product-price').val();
	$.ajax({
		url:AJAXDIR+'add_new_product.php',
		method:'post',
		data:{title:title, description:description, price:price},
		success:function(resp){
			if(resp != '1'){
				document.location.reload();
			}else{
				promptAlertMessageAtDialogBox("PLEASE CHECK FIELDS, RENAME, ETC");
			}
		}
	});
}





function addAttribute(){
		var data=new FormData();
		var f_input = $('#attribute-pic');
		var parentDiv = $('#popup-dialog');
		var name = parentDiv.find('#attribute-name').val();
		var quantity = parentDiv.find('#available-quantity').val();
		var size = parentDiv.find('#available-size').val();
		var cover = parentDiv.find('#cover-check').is(':checked')?'true':'false';
		var uploadable = parentDiv.find('#attribute-pic-label').hasClass('unuploadable')?'false':'true';
		var product_id = $('#glob-product-prew').attr('data-product-id');
		
		if(name.length == 0){
			promptAlertMessageAtDialogBox("NAME CAN'T BE EMPTY");
			return false;
		}
		
		if( quantity.length <= 0 ||  parseInt(quantity) < 0){
			promptAlertMessageAtDialogBox("THE QUANTITY IS INVALID");
			return false;
		}
		
		if( size == 'AVAILABE SIZE'){
			promptAlertMessageAtDialogBox("PLEASE CHOOSE THE SIZE");
			return false;
		}
		data.append('pic',f_input[0].files[0]);
		data.append('product_id',product_id);
	
		data.append('name',name);
		data.append('quantity',quantity);
		data.append('size',size);
		data.append('cover',cover);
		data.append('uploadable',uploadable);
		
		$.ajax({
			url:AJAXDIR+'add_attribute.php',
			type:'POST',
			processData: false,
			contentType: false,
			data:data,
			success:function(resp){
				console.log(resp);
				if(resp == '1'){
					presentPopupDialog("Error", "The item can't be added.", "GOT IT", "", null, null );
				}else if(resp == '-1'){
					promptAlertMessageAtDialogBox('PLEASE ADD AN IMAGE');
				}else{
					document.location.reload();	
				}
			}
		});
}


function isAttributeNameAndSizeAlreadyExisted(){
		var parentDiv = $('#popup-dialog');
		var product_id = $('#glob-product-prew').attr('data-product-id');
		var name = parentDiv.find('#attribute-name').val().toUpperCase();
		var size = parentDiv.find('#available-size').val();
		if(name.trim().length == 0){
			return false;
		}
		$.ajax({
			url:AJAXDIR+'isAttributeNameAndSizeAlreadyExisted.php',
			method:'post',
			data:{product_id:product_id, name:name, size:size},
			success:function(resp){
				var alert_message = parentDiv.find('.alert-message');
				var action_button = parentDiv.find('.action-button');
				if(resp == '1'){
					alert_message.html("\""+name+"\" FOR SIZE \""+size+"\" EXISTED").removeClass('hdn');
					action_button.addClass('unclickable').unbind('click');
				}else{
					alert_message.html('').addClass('hdn');
					action_button.removeClass('unclickable').bind('click', addAttribute);
				}
			}
		});
}

function checkSettable(thisE){
	isAttributeNameAndSizeAlreadyExisted();
}


$(document).ready(function(){
	$('body').on({
		click:function(evt){
			evt.preventDefault();
			return false;
		}
	},'.unclickable');

	$('body').on({
		click:function(){
			var parent = $('#glob-product-prew');
			var product_id = parent.attr('data-product-id');
			var title = parent.find('#product-detail-info .title input').val();
			var desc = parent.find('#product-detail-info .desc textarea').val();
			var price = parent.find('#product-detail-info .price input').val();
			var thisE = $(this);
			thisE.text('UPDATING...').css({'cursor':'default', 'opacity':'0.8'});
			$.ajax({
				url:AJAXDIR+'save_product_gen_info.php',
				method:'post',
				data:{product_id:product_id, title:title, desc:desc, price:price},
				success:function(resp){
					setTimeout(function(){
						thisE.text('UPDATE').css({'cursor':'pointer', 'opacity':'1'});
					},200);
					
				}
			});
		}
	},'#product-general-info-update');
	
	$('body').on({
		click:function(){
			$(this).find('input').removeClass('hdn').focus();
			$(this).find('.inventory-value').addClass('hdn');
		}
	},'.inventory');	
	
	
	$('body').on({
		keyup:function(evt){
			var thisE = $(this);
			var attribute_row_id = thisE.parents('.attribute-row').attr('data-attribute-row-id');
			var inventory_value = thisE.parents('.inventory').find('.inventory-value');
			if(evt.keyCode == 13){
				var inventory = thisE.val();
				$.ajax({
				url:AJAXDIR+'save_inventory.php',
				method:'post',
				data:{attribute_row_id:attribute_row_id, inventory:inventory},
				success:function(resp){
					if(resp == '0'){
						thisE.attr('value',inventory);
						thisE.addClass('hdn');
						inventory_value.text(inventory).removeClass('hdn');
					}
				}
			});
			}
		}
	},'.inventory input');	
	
	$('body').on({
		change:function(){
			var attr_row = $(this).parents('.attribute-row');
			var attribute_row_id = attr_row.attr('data-attribute-row-id');
			var product_id = $('#glob-product-prew').attr('data-product-id');
			$.ajax({
				url:AJAXDIR+'update_cover.php',
				method:'post',
				data:{attribute_row_id:attribute_row_id, product_id:product_id},
				success:function(resp){
					document.location.reload();	
				}
			});
		}
	
	},'.use-as-cover.selectable');
	
	$('body').on({
		click:function(){
			return false;
		}
	},'.unselectable');
	
	$('body').on({
		click:function(){
			presentPopupDialog('Remove Item', "Do you want to remove this?", "No", "Yes", removeAttribute, this );
		}
	
	},'.remove-attribute');
	
	
	$('body').on({
		click:function(){
			resetDialog();
		}
	},'.cancel-button');
	
	
	$('body').on({
		click:function(){
			$.get(AJAXDIR+"load_add_attribute_box.php", function( resp ) {
    			// my_var contains whatever that request returned
    			presentPopupDialog('Add Color', resp, "Cancel", "Add", addAttribute, this );
    		});
    		
		}
	
	}, '#add-attribute');
	
	$('body').on({
		click:function(evt){
			evt.preventDefault();
			$.get(AJAXDIR+"load_add_new_product_box.php", function( resp ) {
    			// my_var contains whatever that request returned
    			presentPopupDialog('Add New Product', resp, "Cancel", "Add", addProduct, this );
    			setTimeout(function(){
					   $('#popup-dialog .body-text').css('margin','0px');
    			},10);
    		});
    	}
	}, '#add-new-product');
		
	$('body').on({
		keyup:function(){
			isAttributeNameAndSizeAlreadyExisted();
			var attribute_name = $(this).val();
			var product_id = $('#glob-product-prew').attr('data-product-id');
			$.ajax({
				url:AJAXDIR+'getAttributePhoto.php',
				method:'post',
				data:{attribute_name:attribute_name, product_id:product_id},
				success:function(resp){
					var parentDiv = $('#popup-dialog');
					var imgTarget = parentDiv.find('.attribute-thumb');
					if(resp == '1'){
						imgTarget.attr('src', DEFAULT_IMAGE_PATH);
						parentDiv.find('#upload-overlay').removeClass('hdn');
						parentDiv.find('#attribute-pic-label').removeClass('unuploadable');
					}else{
						//set
						imgTarget.attr('src', resp);
						parentDiv.find('#upload-overlay').addClass('hdn');
						parentDiv.find('#attribute-pic-label').addClass('unuploadable');
					}
				}
			});
		
		}
	},'#attribute-name');
	
	
	$('body').on({
		click:function(evt){
			if($(this).hasClass('unuploadable')){
				evt.preventDefault();
			}
		}
	},'#attribute-pic-label');

	
	$('body').on({
		click:function(){
			$(this).find('.status-label').addClass('hdn');
			$(this).find('.update-order-status').removeClass('hdn');
		}
		
	},'.order-status');
	
	$('body').on({
		click:function(){
			$(this).parents('.order-status').find('.status-label').removeClass('hdn');
			$(this).parents('.order-status').find('.update-order-status').addClass('hdn');
			return false;
		}
	
	},'.cancel-status-button');
	
	
	$('body').on({
		click:function(){
			var row  = $(this).parents('.attribute-row');
			var order_record_id = row.attr('data-order-record-id');
			var order_status = row.find('.update-order-status select').val();
			
			$.ajax({
				url:AJAXDIR+'updateOrderStatus.php',
				method:'post',
				data:{order_record_id:order_record_id, order_status:order_status},
				success:function(resp){
					document.location.reload();
				}
			});
			return false;
		}
	
	},'.update-status-button');


	
	
	
	
});