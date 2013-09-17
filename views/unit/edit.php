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
<h1><?php echo __(ucfirst($action).' unit'); ?></h1>

<form method="post" action="<?php if ($action == 'add') echo get_url('plugin/catalog/unit/add'); else echo get_url('plugin/catalog/unit/edit/' . $unit->id); ?>" enctype="multipart/form-data">
    <table border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td class="label"><label for="unit_name"><?php echo __('Name'); ?></label></td>
                <td class="field"><input class="textbox" type="text" name="unit[name]" id="unit_name" value="<?php echo $unit->name; ?>" /></td>
            </tr>
            <tr>
                <td class="label"><label for="unit_abbreviation"><?php echo __('Abbreviation'); ?></label></td>
                <td class="field"><input class="textbox" type="text" name="unit[abbreviation]" id="unit_abbreviation" value="<?php echo $unit->abbreviation; ?>" /></td>
            </tr>
            <tr>
                <td class="label"><label><?php echo __('Type'); ?></label></td>
                <td class="field">
                    <select name="unit[attribute_type_id]">
                        <?php foreach ($types as $type): ?>
                        <option value="<?php echo $type->id; ?>" <?php if ($unit->attribute_type_id == $type->id): ?> selected="selected"<?php endif; ?>><?php echo $type->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label"><label for="unit_multiplier" id="unit_multiply_abbreviation">1 <?php echo $unit->abbreviation; ?> =</label></td>
                <td class="field"><input class="textbox number" type="text" name="unit[multiplier]" id="unit_multiplier" value="<?php echo $unit->multiplier; ?>"> m</td>
            </tr>
        </tbody>
    </table>
    
    <p class="buttons">
        <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save and Close'); ?>" />
        <input class="button" name="continue" type="submit" accesskey="e" value="<?php echo __('Save and Continue Editing'); ?>" />
        <?php echo __('or'); ?> <a href="<?php echo get_url('plugin/catalog/units'); ?>"><?php echo __('Cancel'); ?></a>
    </p>
</form>