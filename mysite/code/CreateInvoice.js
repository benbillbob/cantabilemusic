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
	
	function isVoucherCode(i){return i.key.indexOf('Voucher') > 0;}
	function isRateDiscount(i){return i.key == 'Re enrol discount';}
	
	paypal.minicart.cart.on('add', function (idx, product, isExisting) {
		if (!isExisting){
			var amount = product.get('amount');
			var tax = product.get('tax');
			var total = parseFloat(amount) + parseFloat(tax);

			var rate = product.options().find(isRateDiscount);
			if (rate){
				if (rate.value){
					var discountRate = product.get('voucherDiscountRate');
					if (discountRate){
						total = total - total * (discountRate / 100);
					}
				}
			}

			if (!this.items().some(item => item._data.discountApplied))
			{
				var code = product.options().find(isVoucherCode);
				if (code){
					if (code.value){
						var discountAmount = product.get('voucherDiscount');
						if (discountAmount){
							total = total - discountAmount;
							product.set('discountApplied', true);
						}
					}
					else {
						code.value = false;
					}
				}
			}
			
			tax = (total / 11).toFixed(2);
			total = total - tax;
			total = total.toFixed(2);
			product.set('amount', total);
			product._tax = tax;
			product.set('tax', tax);
		}
	});
});