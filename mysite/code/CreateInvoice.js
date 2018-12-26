jQuery( document ).ready(function(){
	paypal.minicart.cart.on('checkout', function (evt) {
		evt.preventDefault();
		
		var form = evt.currentTarget;

		var items = this.items();
		var dataToBeSent = [];
		
		for (i = 0, len = items.length; i < len; i++) {
			var item = items[i].get();
			delete item.href;
			dataToBeSent.push(item);
		}
		
		$.ajax({
			url : window.location.pathname + '/createInvoice',
			type: "post",
			data: JSON.stringify(dataToBeSent),
			contentType: "application/json",
			success: function(data) {
				var input = document.createElement('input');
				input.type = 'hidden';
				input.name = 'custom';
				input.value = data;
				form.appendChild(input);
				form.submit();   //Send client to the payment processor
			}
		});		
	});
	
	function isVoucherCode(i){return i.key == 'Voucher Code';}
	
	paypal.minicart.cart.on('add', function (idx, product, isExisting) {
		if (!isExisting){
			var code = product.options().find(isVoucherCode);
			if (code){
				if (code.value){
					var discountAmount = product.get('voucherDiscount');
					if (discountAmount){
						var amount = product.get('amount')
						var tax = product.get('tax')
						var total = parseFloat(amount) + parseFloat(tax)
						total = total - discountAmount
						total = total / 11 * 10
						total = total.toFixed(2)
						product.set('amount', total)
					}
				}
			}
		}
	});
});