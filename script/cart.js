// Make sure your DOM content is fully loaded before attaching event handlers
document.addEventListener('DOMContentLoaded', function() {
    // Select all forms that have the 'add-to-cart-form' class
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');

    // Attach a submit event listener to each form
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Stop the form from submitting normally

            // Extract the product ID from the hidden input in the form
            const productId = this.querySelector('[name="product_id"]').value;

            
            // Use 'fetch' to send a POST request to 'addToCart.php'
            fetch('addToCart.php', {
                method: 'POST',
                body: new URLSearchParams({ productId: productId }),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                credentials: 'include'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // If the item was added successfully, display the success message
                    showNotification('Success! Your item has been added to the cart.');
                } else {
                    // If there was an error, display the error message
                    showNotification('Sorry, there was an error. Please try again.', true);
                }
            })
            .catch(error => {
                // Log any errors to the console and display an error message
                console.error('Error adding item to cart:', error);
                showNotification('Sorry, there was an error. Please try again.', true);
            });
        });
    });
});

// This function creates and displays a notification message
function showNotification(message, isError = false) {
    const notification = document.createElement('div');
    notification.className = `notification ${isError ? 'error' : 'success'}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Remove the notification after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
}




function updateCartUI() {
    // Placeholder for updating the UI, like incrementing a cart item counter
}

function showNotification(message, isError = false) {
    const notification = document.createElement('div');
    notification.classList.add('cart-notification');
    notification.textContent = message;

    if (isError) {
        notification.classList.add('error');
    }

    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
