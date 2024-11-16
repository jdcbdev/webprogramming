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
                })

                $('#custom-search').on('keyup', function() {
                    table.search(this.value).draw()
                })

                $('#category-filter').on('change', function() {
                    if(this.value !== 'choose'){
                        table.column(3).search(this.value).draw()
                    }
                })

                $('#add-product').on('click', function(e){
                    e.preventDefault()
                    showProductModal($(this).attr('href'))
                })

                $('.btn-edit').on('click', function(e){
                    e.preventDefault()
                    showProductModal($(this).attr('href'), $(this).data('id'))
                })

                $('.btn-delete').on('click', function(e){
                    e.preventDefault()
                    showProductModal($(this).attr('href'), $(this).data('id'), $(this).data('name'))
                })
                
            }
        })
    }

    function showProductModal(url='', id='', name=''){
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'html',
            success: function(view) {
                $('.modal-container').html(view)
                $('#modal-product').modal('show')

                $('form span#product-name').html(name)
    
                fetchCategories(function() {
                    fetchProduct(id, function(image) {
                        $('#form-product').on('submit', function(e) {
                            e.preventDefault()
                            updateProduct(id, image, $(this).attr('action'))
                        })
                    })
                })
            }
        })
    }
    
    function fetchProduct(id, callback) {
        let image
        $.ajax({
            url: '../products/fetch-product.php?id=' + id,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('form #code').val(data.code)
                $('form #name').val(data.name)
                $('form #category').val(data.category_id)
                $('form #price').val(data.price)
                if (data.file_path) {
                    image = data.file_path
                    $('#product-image-preview').attr('src', data.file_path)
                }
                callback(image)
            }
        })
    }    

    function updateProduct(id, image, url){
        let form = new FormData($('#form-product')[0])
        let hasImagePreview = (image) ? 'true' : 'false'
        form.append('id', id)
        form.append('hasImagePreview', hasImagePreview)
        $.ajax({
            type: 'POST',
            url: '../products/' + url,
            data: form,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'error') {
                    if (response.codeErr) {
                        $('#code').addClass('is-invalid')
                        $('#code').next('.invalid-feedback').text(response.codeErr).show()
                    }else{
                        $('#code').removeClass('is-invalid')
                    }
                    if (response.nameErr) {
                        $('#name').addClass('is-invalid')
                        $('#name').next('.invalid-feedback').text(response.nameErr).show()
                    }else{
                        $('#name').removeClass('is-invalid')
                    }
                    if (response.categoryErr) {
                        $('#category').addClass('is-invalid')
                        $('#category').next('.invalid-feedback').text(response.categoryErr).show()
                    }else{
                        $('#category').removeClass('is-invalid')
                    }
                    if (response.priceErr) {
                        $('#price').addClass('is-invalid')
                        $('#price').next('.invalid-feedback').text(response.priceErr).show()
                    }else{
                        $('#price').removeClass('is-invalid')
                    }
                    if (response.imageErr) {
                        $('#product_image').addClass('is-invalid')
                        $('#product_image').next('.invalid-feedback').text(response.imageErr).show()
                    }else{
                        $('#product_image').removeClass('is-invalid')
                    }
                } else if (response.status === 'success') {
                    $('#modal-product').modal('hide')
                    $('#form-product')[0].reset()
                    viewProducts()
                }
            }
        })
        
    }

    function fetchCategories(callback) {
        $.ajax({
            url: '../products/fetch-categories.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#category').empty().append('<option value="">--Select--</option>')
                
                $.each(data, function(index, category) {
                    $('#category').append(
                        $('<option>', {
                            value: category.id,
                            text: category.name
                        })
                    )
                })
                callback()
            }
        })
    }


})