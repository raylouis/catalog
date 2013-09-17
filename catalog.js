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
 * @version     0.1.5
 */

window.onload = (function() {
    try {
        $('.move-to-variants').live('click', function() {

            var label = $(this).parent().children('label').html();
            var field = $(this).parent().parent().children('td.field');

            var i = 0;

            $('table#variants thead tr th.icon').before('<th>' + label + '</th>');

            $('table#variants tbody tr').each(function() {
                var tr = $(this);
                var html = '';

                field.children('input, select, textarea').each(function () {
                    var input = $(this).clone();

                    var input_name;
                    var input_html;

                    var variant = 'variants[' + i + ']';

                    input_name = input.attr('name');
                    input_name = input_name.replace('product', variant);
                    input.attr('name', input_name);
                    input_html = $('<div>').append(input.clone()).html();

                    html += input_html + ' ';
                });

                tr.children('td.icon').before('<td>' + html + '</td>');

                i++;
            });

            $(this).parent().parent().remove();
            
            return false;
        });
    }
    catch(e) { alert(e) }

    try {
        $('.add-variant').live('click', function() {
            if ($('table#variants tbody tr').length < 1) {
                alert('no tr');
            } else {
                var row = $('table#variants tbody tr:last').clone();

                if (typeof old_number == 'undefined') {
                    if (old_number = row.find('input[type="hidden"]').attr('name')) {
                        old_number = old_number.replace('variants[', '');
                        old_number = old_number.replace('][id]', '');
                    } else {
                        old_number = 0;
                    }
                }

                old_number = parseInt(old_number);

                row.find('input[type="hidden"]').remove();
                row.find('td input').removeAttr('value');

                row.find('td input').attr('name', function(i, val) {
                    new_number = old_number + 1;

                    val = val.replace('variants[' + old_number + ']', '');
                    val = 'variants[' + new_number + ']' + val;
                    
                    return val;
                });

                old_number = new_number;

                $('table#variants tbody').append('<tr>' + row.html() + '</tr>');
            }
            return false;
        });
    }
    catch(e) { alert(e) }

    try {
        $('.remove-variant').live('click', function() {
            $(this).parent().parent().remove();
            return false;
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
    
    
});