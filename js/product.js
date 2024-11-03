// Select all elements with the class 'deleteBtn'
let deleteButtons = document.querySelectorAll('.deleteBtn');

// Iterate over each button
deleteButtons.forEach(button => {
    // Add a 'click' event listener to each button
    button.addEventListener('click', function(e) {
        // Prevent the default action of the click event (e.g., navigation or form submission)
        e.preventDefault();
        
        // Get the product name and id from the button's data attributes
        let product = this.dataset.name;
        let productID = this.dataset.id;
        
        // Ask the user for confirmation to delete the product
        let response = confirm("Do you want to delete the product " + product + "?");
        
        // If the user confirms deletion
        if (response) {
            // Send a GET request to delete the product using the fetch API
            fetch('../products/deleteproduct.php?id=' + productID, {
                method: 'GET'
            })
            .then(response => response.text())  // Parse the response as plain text
            .then(data => {
                // If the server responds with 'success'
                if(data === 'success') {
                    // Redirect the user to 'product.php'
                    window.location.href = 'admin/products';
                }
            });
        }
    });
});