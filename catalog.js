window.onload=function(){try{$(function(){$("table.data-sortable").tablesorter({sortReset:!0})})}catch(t){alert(t)}try{$(".move-to-variants").live("click",function(){var t=$(this).parent().children("label").html(),e=$(this).parent().parent().children("td.field"),a=0;return $("table#variants thead tr th.icon").before("<th>"+t+"</th>"),$("table#variants tbody tr").each(function(){var t=$(this),r="";e.children("input, select, textarea").each(function(){var t,e,n=$(this).clone(),i="variants["+a+"]";t=n.attr("name"),t=t.replace("product",i),n.attr("name",t),e=$("<div>").append(n.clone()).html(),r+=e+" "}),t.children("td.icon").before("<td>"+r+"</td>"),a++}),$(this).parent().parent().remove(),!1})}catch(t){alert(t)}try{$(".add-variant").live("click",function(){if($("table#variants tbody tr").length<1)alert("no tr");else{var t=$("table#variants tbody tr:last").clone();"undefined"==typeof old_number&&((old_number=t.find('input[type="hidden"]').attr("name"))?(old_number=old_number.replace("variants[",""),old_number=old_number.replace("][id]","")):old_number=0),old_number=parseInt(old_number),t.find('input[type="hidden"]').remove(),t.find("td input").removeAttr("value"),t.find("td input").attr("name",function(t,e){return new_number=old_number+1,e=e.replace("variants["+old_number+"]",""),e="variants["+new_number+"]"+e}),old_number=new_number,$("table#variants tbody").append("<tr>"+t.html()+"</tr>")}return!1})}catch(t){alert(t)}try{$(".remove-variant").live("click",function(){return $(this).parent().parent().remove(),!1})}catch(t){alert(t)}try{$(".remove-attribute").live("click",function(){return $(this).parent().remove(),!1})}catch(t){alert(t)}try{$("#attribute_attribute_type_id").change(function(){var t=$("#attribute_attribute_type_id option:selected").val(),e="http://localhost/zandbak/admin/plugin/catalog/ajax/attribute_type_units/"+t,a=$.ajax({url:e,cache:!1,success:function(t){$("#attribute_default_unit_td").html(t),""==t?$("#attribute_default_unit_tr").hide():$("#attribute_default_unit_tr").show()}});a.fail(function(t,e){alert("Request failed: "+e)}),alert("end")})}catch(t){alert(t)}try{$("#product_category_id").change(function(){var t=$("#product_category_id option:selected").val(),e="http://localhost/zandbak/admin/plugin/catalog/ajax/product_attribute_selector/"+t,a=$.ajax({url:e,cache:!1,success:function(t){$(".product_variant_attributes").html(t)}});a.fail(function(t,e){alert("Request failed: "+e)}),alert("end")})}catch(t){alert(t)}try{$("#unit_abbreviation").change(function(){var t=$("#unit_abbreviation").val(),e="1 "+t+" =";$("#unit_multiply_abbreviation").html(e)})}catch(t){alert(t)}};