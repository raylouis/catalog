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
 * @version     0.1.5
 */

?>
<h1><?php echo __(ucfirst($action).' attribute'); ?></h1>

<form method="post" action="<?php if ($action == 'add') echo get_url('plugin/catalog/attribute/add'); else echo get_url('plugin/catalog/attribute/edit/'.$attribute->id); ?>">
    <table border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td class="label"><label for="attribute_name"><?php echo __('Name'); ?></label></td>
                <td class="field"><input class="textbox" type="text" name="attribute[name]" id="attribute_name" value="<?php echo $attribute->name; ?>" /></td>
            </tr>
            <tr>
                <td class="label"><label for="attribute_description"><?php echo __('Description'); ?></label></td>
                <td class="field">
                    <textarea class="textbox" name="attribute[description]" id="attribute_description"><?php echo $attribute->description; ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="label"><label><?php echo __('Type'); ?></label></td>
                <td class="field">
                    <select name="attribute[attribute_type_id]" id="attribute_attribute_type_id">
                        <option><?php echo __('Choose one'); ?></option>
                        <?php foreach ($attribute_types as $type): ?>
                        <option value="<?php echo $type->id; ?>"<?php if ($type->id == $attribute->attribute_type_id): ?> selected="selected"<?php endif; ?>><?php echo strtolower($type->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr id="attribute_default_unit_tr"<?php echo (count($attribute->type->units) > 0) ? '' : ' style="display: none;"'; ?>>
                <td class="label"><label><?php echo __('Default unit'); ?></label></td>
                <td class="field" id="attribute_default_unit_td">
                    <select name="attribute[default_unit_id]" id="attribute_default_unit_id">
                        <?php foreach ($attribute->type->units as $unit): ?>
                        <option value="<?php echo $unit->id; ?>"<?php if ($unit->id == $attribute->default_unit_id): ?> selected="selected"<?php endif; ?>><?php echo strtolower($unit->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    
    <p class="buttons">
        <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save and Close'); ?>" />
        <input class="button" name="continue" type="submit" accesskey="e" value="<?php echo __('Save and Continue Editing'); ?>" />
        <?php echo __('or'); ?> <a href="<?php echo get_url('plugin/catalog/attributes'); ?>"><?php echo __('Cancel'); ?></a>
    </p>
</form>