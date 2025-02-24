$(document).ready(function() {
    $('form').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: 'add_product.php',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    $('#addProductModal').modal('hide');
                    location.reload(); // Reload the page to see the new product
                } else {
                    alert(response.message);
                }
            }
        });
    });
});
