$(function(){
	$('input[type="radio"]').click(function(){
	if ($(this).is(':checked'))
	{
		var option = $('input[type="radio"]:checked').val();
		if(option == 'cc') {
			  var brick = new Brick({
			    public_key: 't_34476d9e5ec696b70515f7d104151b',
			    amount: 9.99,
			    currency: 'USD',
			    container: 'iframe',
			    action: 'pw/brick.php',
			    form: {
			      merchant: 'Merchant Name',
			      product: 'Product Name',
			      pay_button: 'Pay',
			      zip: true
			    }
			  });

			  brick.showPaymentForm(function(data) {
			    // handle success
			    alert("OK");
			  }, function(errors) {
			    // handle errors
			    alert("NOK");
			  });
		} else {
	 		$.ajax({
			   type: 'POST',
			   data : {option: option},
			   url:'pw/api.php',
		  	   beforeSend: function() {
		  	   	 $("#iframe").hide();
		  	     $('#loading').show(); 
		  	   },
			   success: function(data){
			        if(data.status == 'success'){
				        $("#iframe").html(data['widget']);
				    }else if(data.status == 'error'){
				        $("#iframe").html("Error");
				    }
			   		$('#loading').hide();
				    $("#iframe").show();
			   }
			});			
		}
	}
	});
});


function sendId(id) {
	console.log(id.options[id.selectedIndex].value);
	var hidden = document.getElementById('hidden');
	hidden.value = id.options[id.selectedIndex].value;

}