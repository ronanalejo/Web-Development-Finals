document.addEventListener('DOMContentLoaded', function() {
    fetch('getOrderDetails.php')
        .then(response => response.json())
        .then(orderDetails => {
            document.getElementById('firstName').innerText = orderDetails.firstName;
            document.getElementById('lastName').innerText = orderDetails.lastName;
            document.getElementById('shippingAddress').innerText = orderDetails.shippingAddress;
            document.getElementById('contactNumber').innerText = orderDetails.contactNumber;
        });
});
