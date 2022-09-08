



paypal.Buttons({
    style: {
    layout: 'vertical',
    color:  'blue',
    shape:  'pill',
    label:  'paypal'
    },
    createOrder: function(data, actions){
        
        var total_value = cart_total
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: total_value
                }
            }]
        })
    },
    onApprove: function(data, actions){
        let message = document.querySelector('#paypal-message > #message');
        let desc = document.querySelector('#paypal-message > #desc');
        let paypalBtn = document.querySelector('#paypal-button-container');
        paypalBtn.style.display = "block";
        desc.innerHTML = '';
        message.innerHTML = '';
        message.classList.remove('paypal-cancel');
        return actions.order.capture().then(function (details){
            if(details.status = "COMPLETE"){
                paypalBtn.style.display = "none";
                message.classList.add('paypal-approve');
                message.classList.remove('paypal-cancel');
                message.innerHTML = 'Payment Success';
                desc.innerHTML = 'Redirecting...';
                console.log('--SUCCESS!--');
            }else{
                message.classList.add('paypal-cancel');
                message.classList.remove('paypal-approve');
                message.innerHTML = 'Payment Failed';
                desc.innerHTML = 'Please Try Again!';
                console.log('--Failed!--');
            }
            
            var data = JSON.stringify(details);
            window.location = `paypal-success.php?data=${data}`;
            // $.ajax({
            //     url: './paypalHandler.php',
            //     type: 'POST',
            //     async: false,
            //     data: {'paypal_data': data},
            //     success: function (msg){
            //     }
            // })
            console.log('okay2!');
        })
    }, 
    onCancel: function(data){
        let message = document.querySelector('#paypal-message > #message');
        let desc = document.querySelector('#paypal-message > #desc');
        message.innerHTML = 'Payment Canceled';
        message.classList.add('paypal-cancel');
        desc.innerHTML = 'Please Try Again!';
    }
}).render('#paypal-button-container');