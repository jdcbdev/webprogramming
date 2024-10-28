$(document).ready(function(){
    $('.nav-link').on('click', function(e){
        e.preventDefault()
        $('.nav-link').removeClass('link-active')
        $(this).addClass('link-active')
        
        let url = $(this).attr('href')
        window.history.pushState({path: url}, '', url)
    })

    $('#dashboard-link').on('click', function(e){
        e.preventDefault()
        viewAnalytics()
    })

    $('#products-link').on('click', function(e){
        e.preventDefault()
        viewProducts()
    })

    $('#accounts-link').on('click', function(e){
        e.preventDefault()
        viewAccounts()
    })

    let url = window.location.href;
    if (url.endsWith('dashboard')){
        $('#dashboard-link').trigger('click')
    }else if (url.endsWith('products')){
        $('#products-link').trigger('click')
    }else if (url.endsWith('accounts')){
        $('#accounts-link').trigger('click')
    }else{
        $('#dashboard-link').trigger('click')
    }

    function viewAnalytics(){
        $.ajax({
            type: 'GET',
            url: 'view-analytics.php',
            dataType: 'html',
            success: function(response){
                $('.content-page').html(response)
                loadChart()
            }
        })
    }

    function loadChart(){
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
            datasets: [{
            label: 'Sales',
            data: [7000, 5500, 5000, 4000, 4500, 6500, 8200, 8500, 9200, 9600, 10000, 9800],
            backgroundColor: '#EE4C51',
            borderColor: '#EE4C51',
            borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
            y: {
                beginAtZero: true,
                max: 10000,
                ticks: {
                    stepSize: 2000  // Set step size to 2000
                }
            }
            }
        }
        });
    }

    function viewAccounts(){
        $.ajax({
            type: 'GET',
            url: '../admin/view-accounts.php',
            dataType: 'html',
            success: function(response){
                $('.content-page').html(response)

                var table = $('#table-products').DataTable({
                    dom: 'rtp',
                    pageLength: 10,
                    ordering: false,
                });

                // Bind custom input to DataTable search
                $('#custom-search').on('keyup', function() {
                    table.search(this.value).draw()
                });

                $('#category-filter').on('change', function() {
                    if(this.value !== 'choose'){
                        table.column(3).search(this.value).draw()
                    }
                });

                $('#add-product').on('click', function(e){
                    e.preventDefault()
                    addProduct()
                })

            }
        })
    }

    function viewProducts(){
        $.ajax({
            type: 'GET',
            url: '../products/view-products.php',
            dataType: 'html',
            success: function(response){
                $('.content-page').html(response)

                var table = $('#table-products').DataTable({
                    dom: 'rtp',
                    pageLength: 10,
                    ordering: false,
                });

                // Bind custom input to DataTable search
                $('#custom-search').on('keyup', function() {
                    table.search(this.value).draw()
                });

                $('#category-filter').on('change', function() {
                    if(this.value !== 'choose'){
                        table.column(3).search(this.value).draw()
                    }
                });

                $('#add-product').on('click', function(e){
                    e.preventDefault()
                    addProduct()
                })

            }
        })
    }

    function addProduct(){
        $.ajax({
            type: 'GET',
            url: '../products/add-product.html',
            dataType: 'html',
            success: function(view){
                $('.modal-container').html(view)
                $('#staticBackdrop').modal('show')

                fetchCategories()

                $('#form-add-product').on('submit', function(e){
                    e.preventDefault()
                    saveProduct()
                })
            }
        })
    }

    function saveProduct(){
        $.ajax({
            type: 'POST',
            url: '../products/add-product.php',  // Make sure this points to your PHP handler
            data: $('form').serialize(),         // Serialize the form data
            dataType: 'json',                    // Expect a JSON response
            success: function(response) {
                if (response.status === 'error') {
                    // Display validation errors for each field
                    if (response.codeErr) {
                        $('#code').addClass('is-invalid');
                        $('#code').next('.invalid-feedback').text(response.codeErr).show();
                    }else{
                        $('#code').removeClass('is-invalid');
                    }
                    if (response.nameErr) {
                        $('#name').addClass('is-invalid');
                        $('#name').next('.invalid-feedback').text(response.nameErr).show();
                    }else{
                        $('#name').removeClass('is-invalid');
                    }
                    if (response.categoryErr) {
                        $('#category').addClass('is-invalid');
                        $('#category').next('.invalid-feedback').text(response.categoryErr).show();
                    }else{
                        $('#category').removeClass('is-invalid');
                    }
                    if (response.priceErr) {
                        $('#price').addClass('is-invalid');
                        $('#price').next('.invalid-feedback').text(response.priceErr).show();
                    }else{
                        $('#price').removeClass('is-invalid');
                    }
                } else if (response.status === 'success') {
                    // Hide the modal and reset the form on success
                    $('#staticBackdrop').modal('hide');
                    $('form')[0].reset();  // Reset the form
                    // Optionally, redirect to the product listing page or display a success message
                    viewProducts()
                }
            }
        });
        
    }

    function fetchCategories(){
        $.ajax({
            url: '../products/fetch-categories.php', // URL to the PHP script that returns the categories
            type: 'GET',
            dataType: 'json', // Expect JSON response
            success: function(data) {
                // Clear the existing options (if any) and add a default "Select" option
                $('#category').empty().append('<option value="">--Select--</option>');
                
                // Iterate through the data (categories) and append each one to the select dropdown
                $.each(data, function(index, category) {
                    $('#category').append(
                        $('<option>', {
                            value: category.id, // The value attribute
                            text: category.name // The displayed text
                        })
                    );
                });
            }
        });
    }
});