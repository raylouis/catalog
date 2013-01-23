<li><?php
?><select name="attribute_ids[]"><?php
?><option></option><?php

foreach ($attributes as $attribute) {
    if (is_null($category_attribute)) {
?><option value="<?php echo $attribute->id; ?>"><?php echo $attribute->name; ?></option><?php        
    }
    else {
?><option value="<?php echo $attribute->id; ?>"<?php echo ($attribute->id == $category_attribute->id) ? ' selected="selected"' : ''; ?>><?php echo $attribute->name; ?></option><?php
    }
}
?></select> <?php
?><a href="#" class="remove-attribute"><?php
?><img width="16" height="16" src="<?php echo URL_PUBLIC; ?>wolf/icons/delete-16.png" alt="<?php echo __('Delete'); ?>" /><?php
?></a><?php
?></li>