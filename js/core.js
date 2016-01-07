ROOTDIR = "http://localhost:8888/"; 
AJAXDIR = ROOTDIR+"ajax/";
AJAX_PHTML_DIR =  ROOTDIR+"ajax/phtml/";
BAD_IMAGE_MESSAGE = "The valid image type is PNG, JPG, JPEG, and should be within 5M";
DEFAULT_IMAGE_PATH = ROOTDIR+"media/supreme-default.jpg"; 


function resetDialog(){
	$('#popup-dialog-wrapper, #dialog-popup-overlay').addClass('hdn');
	$('body').css('overflow','auto');
	var dialogParent = $('#popup-dialog');
	dialogParent.find('.dialog-header .bar-title').text('');
	dialogParent.find('.dialog-body .body-text').html('').css('margin','14px 24px');
	dialogParent.find('.dialog-footer .dismiss').text('');
	dialogParent.find('.dialog-footer .action-button').unbind().text('').addClass('hdn').removeClass('unclickable');
	resetAlertMessage();
}

function resetAlertMessage(){
	$('#popup-dialog').find('.dialog-footer .alert-message').html('').addClass('hdn');
}

//sender is the element which triggered the popup dialog
function setDialog(parentElement,title, body, dismissButtonnText, actionButtonText, action, sender){
	parentElement.find('.dialog-header .bar-title').text(title);
	parentElement.find('.dialog-body .body-text').html(body);
	parentElement.find('.dialog-footer .dismiss').text(dismissButtonnText);
	if(actionButtonText != ''){
		var actionButton = parentElement.find('.dialog-footer .action-button');
		actionButton.text(actionButtonText).removeClass('hdn');
		if(!actionButton.hasClass('unclickable')){
			actionButton.on('click',function(){
				action(sender);
				return false;
			});	
		}
	}
}

function presentPopupDialog(title, body, dismissButtonnText, actionButtonText, action, sender ){
	$('#dialog-popup-overlay').removeClass('hdn');
	$('#popup-dialog-wrapper').removeClass('hdn');
	$('body').css('overflow','hidden');
	if($('body').find('#popup-dialog').length < 1){
		$('#popup-dialog-wrapper').load( AJAX_PHTML_DIR+"popup_dialog.phtml",function(){
			setDialog($(this),title, body, dismissButtonnText, actionButtonText, action, sender );
		});
	}else{
		setDialog($('#popup-dialog-wrapper'),title, body, dismissButtonnText, actionButtonText, action, sender );
	}		
}


$(document).ready(function(){
	var timer;
    
    $('#shopping-cart-label').hover(function(){
       var thisE = $(this);
        timer = setTimeout(function(){
          thisE.parents('#shopping-cart-wrapper').find('#shopping-cart').fadeIn('fast');
        },  1000);
    }, function(){
        clearTimeout(timer);
    });

	$('body').on({
		click:function(){
			return false;
		}
	},'#shopping-cart');	
	
	
	function updateCartItemNum(){
		$.post(AJAXDIR+"getCartItemNum.php",function(resp){
			$('#shopping-cart-wrapper #cart-item-num').text(resp);
		});
	}
	
	function loadCart(){
		$.post(AJAXDIR+"loadCart.php",function(resp){
			var cart = $('#shopping-cart');
			cart.find('.body').html(resp);
			cart.fadeIn('fast');
			setTimeout(function(){
				cart.fadeOut('fast');
			},3000);
		});
	}
	
	$('body').on({
		click:function(){
			var cart = $('#shopping-cart');
			var info = $('#product-detail-info');
			var attribute_selected = info.find('.product-snap-shot .attribute-selected');
			if(attribute_selected.length > 0){
				var size = info.find('select option:selected').val();
				if(size == 'CHOOSE'){
					info.find('.stock-status').html('<span class="a-red">Please choose a size</span>').removeClass('hdn');
					return false;
				}
				var atr_name_key = attribute_selected.attr('data-atr-name-key');
				var key = info.attr('data-key');
				
				$.ajax({
					url:AJAXDIR+'add_to_cart.php',
					method:'post',
					data:{key:key, atr_name_key:atr_name_key, size:size},
					success:function(resp){
						console.log(resp);
						if(resp != '1'){
							updateCartItemNum();
							loadCart();
						}
					}
				});
			}
			return false;
		}
	},'#add-to-cart');
	
	$('#checkout').click(function(){
		$(this).unbind('click');
		var data = [];
		$('#glob-checkout-wrapper .body .item-row').each(function(index){
			var item = {};
			item['atr_key'] = $(this).find('input[name=key]').val();
			item['quantity'] = $(this).find('input[name=quantity]').val();
			data[index] = item; 
		})	
		
		$.ajax({
			url:AJAXDIR+'checkout.php',
			method:'post',
			data:{data:data},
			success:function(resp){
				window.location.href = resp;
			}
		});
	});
	
	$('#shopping-cart').on({
		click:function(evt){
			evt.stopPropagation();
		}
	},'a');
	
	
	$('body').on({
		click:function(){
			var atr_key = $(this).parents('.item-row').attr('data-atr-key');
			$.ajax({
				url:AJAXDIR+'removeFromcart.php',
				method:'post',
				data:{atr_key:atr_key},
				success:function(resp){
					document.location.reload();
				}
			});
		}
	},'.remove-from-cart');
	
});
$(document).click(function(){
	$('#shopping-cart').fadeOut('fast');

});





