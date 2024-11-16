$(document).ready(function(){
    window.viewProducts = function(){
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

                $('.btn-edit').on('click', function(e){
                    e.preventDefault()
                    let id = $(this).data('id');
                    editProduct($(this).attr('href'), id)
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
                $('#modal-add-product').modal('show')

                fetchCategories()

                $('#form-add-product').on('submit', function(e){
                    e.preventDefault()
                    saveProduct()
                })
            }
        })
    }

    function editProduct(url, id) {
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'html',
            success: function(view) {
                $('.modal-container').html(view);
                $('#modal-edit-product').modal('show');
    
                fetchCategories(function() {
                    fetchProduct(id, function(image) {
                        $('#form-edit-product').on('submit', function(e) {
                            e.preventDefault();
                            updateProduct(id, image);
                        });
                    });
                });
            }
        });
    }
    
    function fetchProduct(id, callback) {
        let image;
        $.ajax({
            url: '../products/fetch-product.php?id=' + id,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('form #code').val(data.code);
                $('form #name').val(data.name);
                $('form #category').val(data.category_id);
                $('form #price').val(data.price);
                if (data.file_path) {
                    image = data.file_path;
                    $('#product-image-preview').attr('src', data.file_path);
                }
                callback(image);
            }
        });
    }    

    function saveProduct(){
        let form = new FormData($('#form-add-product')[0])
        $.ajax({
            type: 'POST',
            url: '../products/add-product.php',
            data: form,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'error') {
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
                    if (response.imageErr) {
                        $('#product_image').addClass('is-invalid');
                        $('#product_image').next('.invalid-feedback').text(response.imageErr).show();
                    }else{
                        $('#product_image').removeClass('is-invalid');
                    }
                } else if (response.status === 'success') {
                    $('#modal-add-product').modal('hide');
                    $('#form-add-product')[0].reset();
                    viewProducts()
                }
            }
        });
        
    }

    function updateProduct(id, image){
        let form = new FormData($('#form-edit-product')[0])
        if(image){
            image = true
        }
        $.ajax({
            type: 'POST',
            url: '../products/edit-product.php?id=' + id + '&hasImagePreview=' + image,
            data: form,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'error') {
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
                    if (response.imageErr) {
                        $('#product_image').addClass('is-invalid');
                        $('#product_image').next('.invalid-feedback').text(response.imageErr).show();
                    }else{
                        $('#product_image').removeClass('is-invalid');
                    }
                } else if (response.status === 'success') {
                    $('#modal-edit-product').modal('hide');
                    $('#form-edit-product')[0].reset();
                    viewProducts()
                }
            }
        });
        
    }

    function fetchCategories(callback) {
        $.ajax({
            url: '../products/fetch-categories.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#category').empty().append('<option value="">--Select--</option>');
                
                $.each(data, function(index, category) {
                    $('#category').append(
                        $('<option>', {
                            value: category.id,
                            text: category.name
                        })
                    );
                });
                callback();
            }
        });
    }
})