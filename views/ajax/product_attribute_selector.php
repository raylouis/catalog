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
<?php
if (isset($product_variant)) {
    $attribute->values = $attribute->findValuesByProductVariantId($product_variant->id);
    if (count($attribute->values) > 0) {
        $attribute->value = $attribute->values[0];
    }
}

$selected_unit_id = $attribute->default_unit_id;
if (isset($attribute->value)) {
    $selected_unit_id = $attribute->value->attribute_unit_id;
}

?>
<tr>
    <td class="label"><label for="attribute_<?php echo $i; ?>_value"><?php echo $attribute->name; ?></label></td>
    <td class="field">
        <input name="variants[0][attributes][<?php echo $attribute->id; ?>][id]" type="hidden" value="<?php echo (isset($attribute->value)) ? $attribute->value->id : ''; ?>" />
        
        <?php if (in_array($attribute->type->data_type, array('INT', 'FLOAT'))): ?>
            <input class="textbox" name="variants[0][attributes][<?php echo $attribute->id; ?>][value]" type="text" id="attribute_<?php echo $i; ?>_value" value="<?php echo (isset($attribute->value)) ? $attribute->value->flat_value : ''; ?>" /> 

            <?php if (count($attribute->type->units) > 0): ?>
            <select name="variants[0][attributes][<?php echo $attribute->id; ?>][unit]">
                <?php foreach ($attribute->type->units as $unit): ?>
                <option value="<?php echo $unit->id; ?>"<?php echo ($unit->id == $selected_unit_id) ? ' selected="selected"' : ''; ?>><?php echo $unit->abbreviation; ?></option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>
        <?php elseif($attribute->type->data_type == 'BOOLEAN'): ?>
            <input type="checkbox" id="attribute_<?php echo $i; ?>_value" />
        <?php elseif($attribute->type->data_type == 'VARCHAR'): ?>
            <input class="textbox" name="variants[0][attributes][<?php echo $attribute->id; ?>][value]" type="text" id="attribute_<?php echo $i; ?>_value" value="<?php echo (isset($attribute->value)) ? $attribute->value->flat_value : ''; ?>" /> 
        <?php endif; ?>
    </td>
</tr>
<?php $i++; ?>
<?php endforeach; ?>
<?php endif; ?>