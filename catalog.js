window.onload = (function() {
    try {
        $('.block').hide();
        var box = $('#product_type option:selected').text();
        $('#' + box).show();
        
        $('#product_type').change(function() {
            var box = $('#product_type option:selected').text();
            
            $('.block').slideUp(600);
            $('#' + box).slideDown(600);
        });
    }
    catch(e) {}
});