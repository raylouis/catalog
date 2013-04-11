/**
 * Catalog
 * 
 * The catalog plugin adds a catalog or webshop to Wolf CMS.
 * 
 * @package     Plugins
 * @subpackage  catalog
 * 
 * @author      Nic Wortel <nic.wortel@nth-root.nl>
 * @copyright   Nic Wortel, 2012
 * @version     0.1.0
 */

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
    
    try {
        $('#attribute_attribute_type_id').change(function() {
            
            var type_id = $('#attribute_attribute_type_id option:selected').val();
            var url = 'http://localhost/zandbak/admin/plugin/catalog/ajax/attribute_type_units/' + type_id;
            
            var request = $.ajax({
                url: url,
                cache: false,
                success: function (data) {
                    $('#attribute_default_unit_td').html(data);
                    if (data == '') {
                        $('#attribute_default_unit_tr').hide();
                    }
                    else {
                        $('#attribute_default_unit_tr').show();
                    }
                }
            });
            
            request.fail(function(jqXHR, textStatus) {
                alert("Request failed: " + textStatus );
            });
            
            alert('end');
        });
    }
    catch(e) { alert(e) }
    
    try {
        $('#product_category_id').change(function() {
            var category_id = $('#product_category_id option:selected').val();
            var url = 'http://localhost/zandbak/admin/plugin/catalog/ajax/product_attribute_selector/' + category_id;
            
            var request = $.ajax({
                url: url,
                cache: false,
                success: function (data) {
                    $('.product_variant_attributes').html(data);
                }
            });
            
            request.fail(function(jqXHR, textStatus) {
                alert("Request failed: " + textStatus );
            });
            
            alert('end');
        });
    }
    catch(e) { alert(e) }
    
    try {
        $('#unit_abbreviation').change(function() {
            var abbreviation = $('#unit_abbreviation').val();
            
            var comparision = '1 ' + abbreviation + ' =';
            
            $('#unit_multiply_abbreviation').html(comparision);
        });
    }
    catch(e) { alert(e) }
    
    try {
        $('.remove-attribute').live('click', function() {
            $(this).parent().remove();
            return false;
        });
    }
    catch(e) { alert(e) }
});