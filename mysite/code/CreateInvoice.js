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
});