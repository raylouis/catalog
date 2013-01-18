<li><?php
?><input type="hidden" name="attributes[<?php echo $i; ?>][old_id]" value="<?php echo $category_attribute->id; ?>" /><?php
?><select name="attributes[<?php echo $i; ?>][id]"><?php
?><?php foreach ($attributes as $attribute): ?><?php
?><option value="<?php echo $attribute->id; ?>"<?php echo ($attribute->id == $category_attribute->id) ? ' selected="selected"' : ''; ?>><?php echo $attribute->name; ?></option><?php
?><?php endforeach; ?><?php
?></select> <?php
?><a href="#" class="remove-attribute"><?php
?><img width="16" height="16" src="<?php echo URL_PUBLIC; ?>wolf/icons/delete-16.png" alt="<?php echo __('Delete'); ?>" /><?php
?></a><?php
?></li>