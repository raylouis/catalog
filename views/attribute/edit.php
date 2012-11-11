<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nd.wortel@gmail.com>
 * 
 * @file        /views/backend/editAttribute.php
 * @date        25/10/2012
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
                <td class="label"><label for="attribute_type"><?php echo __('Type'); ?></label></td>
                <td class="field">
                    <?php
                    
                    $attribute_types = array(
                        'float' => 'float',
                        'int' => 'integer',
                        'color' => 'color'
                    );
                    
                    ?>
                    <select name="attribute[type]">
                        <?php foreach ($attribute_types as $type => $label): ?>
                        <option value="float"<?php if ($type == $attribute->type): ?> selected="selected"<?php endif; ?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label"><label for="attribute_unit"><?php echo __('Unit'); ?></label></td>
                <td class="field"><input class="textbox" type="text" name="attribute[unit]" id="attribute_unit" value="<?php echo $attribute->unit; ?>" /></td>
            </tr>
        </tbody>
    </table>
    
    <p class="buttons">
        <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save and Close'); ?>" />
        <input class="button" name="continue" type="submit" accesskey="e" value="<?php echo __('Save and Continue Editing'); ?>" />
        <?php echo __('or'); ?> <a href="<?php echo get_url('plugin/catalog/attributes'); ?>"><?php echo __('Cancel'); ?></a>
    </p>
</form>