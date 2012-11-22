<?php
if (!defined('IN_CMS')) { exit(); }

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
 * @version     0.0.1
 */

?>
<?php if (isset($category)): ?>
<?php $i = 1; ?>
<?php foreach ($category->unlimitedAttributes() as $attribute): ?>
<tr>
    <td class="label"><label for="attribute_<?php echo $i; ?>_value"><?php echo $attribute->name; ?></label></td>
    <td class="field">
        <input class="textbox" name="attributes[<?php echo $i; ?>][value]" type="text" id="attribute_<?php echo $i; ?>_value" /> 

        <select name="attributes[<?php echo $i; ?>][unit]">
            <?php foreach ($attribute->type->units as $unit): ?>
            <option value="<?php echo $unit->id; ?>"<?php echo ($unit->id == $attribute->default_unit_id) ? ' selected="selected"' : ''; ?>><?php echo $unit->abbreviation; ?></option>
            <?php endforeach; ?>
        </select>
    </td>
</tr>
<?php $i++; ?>
<?php endforeach; ?>
<?php endif; ?>