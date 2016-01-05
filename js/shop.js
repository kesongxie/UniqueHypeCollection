function updateStockStatus(){
	var info = $('#product-detail-info');
	var attribute_selected = info.find('.product-snap-shot .attribute-selected');
	if(attribute_selected.length > 0){
		var size = info.find('select option:selected').val();
		var atr_name_key = attribute_selected.attr('data-atr-name-key');
		var key = info.attr('data-key');
		$.ajax({
			url:AJAXDIR+'check_inventory.php',
			method:'post',
			data:{key:key, size:size, atr_name_key:atr_name_key},
			success:function(resp){
				if(resp == '-1'){
					info.find('.stock-status').text('Sold out').removeClass('hdn');
				}else{
					info.find('.stock-status').text(resp+' available').removeClass('hdn');
				}
			}
		});
	}
}

$(document).ready(function(){
	updateStockStatus();
	$('body').on({
		click:function(){
			var info = $(this).parents('#detail-info-inner');
			var title = $(this).attr('title');
			info.find('.attribute-label').text(title);
			info.find('.attribute-name-selector').removeClass('attribute-selected');
			$(this).addClass('attribute-selected');
			info.find("select option:selected").removeAttr("selected");
			updateStockStatus();
			$('#product-preview-shot').attr('src',$(this).find('.product-thumbnail').attr('src'));
		}
	},'.attribute-name-selector');	
	
	$('body').on({
		change:function(){
			updateStockStatus();
		}
	},'.proudct-select-size select');	
	
	
	
	
});