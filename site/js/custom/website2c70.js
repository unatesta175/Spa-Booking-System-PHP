
$(document).ready(function () {
	$('.add-to-cart').on("click",function(){
		var $item = $(this);
		function onSuccess(data){
			if(data.status ==1){
				$('#cart-count').html(data.cart.length);
				Message.Success("Item added in your cart!");
			}else{
				if(data.msg != undefined)
				Message.Warning(data.msg);
			}
		}
		function onFail(response){

		}
		JsManager.SendJson("post","cart/"+$item.attr('data-id'),{'qty':1},onSuccess,onFail);
	});

	$('.remove-from-cart').on("click",function(){
		if(confirm("Are you sure want to remove this?")){
			var $item = $(this);
			function onSuccess(data){
				if(data.status ==1){
					Message.Success("Item removed from your cart!");
					window.location.reload();
				}
			}
			function onFail(response){

			}
			JsManager.SendJson("post","cart-remove",{'index':$item.attr('data-index')},onSuccess,onFail);
		}
	});
});

