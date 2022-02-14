


$(document).ready(function(){
    paypal.Buttons({
        style: {
        layout: 'vertical',
        color:  'blue',
        shape:  'pill',
        label:  'paypal'
        },
        createOrder: function(data, actions){
            const cartAmount = document.querySelector('#tempT').value
    
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: cartAmount
                    }
                }]
            })
        },
        onApprove: function(data, actions){
            let ele = document.querySelector('#paypal-message > p');
            ele.innerHTML = '';
            ele.classList.remove('paypal-cancel');
            return actions.order.capture().then(function (details){

                // not yet done
                // not working
                $.ajax({
                    url: './paypalHandler.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(details),
                    dataType: 'json'
                })
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
    
})