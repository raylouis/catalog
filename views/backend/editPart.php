<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nd.wortel@gmail.com>
 * 
 * @file        /views/backend/editPart.php
 * @date        18/09/2012
 */
?>
<h1><?php echo __(ucfirst($action).' part'); ?></h1>

<form method="post" action="<?php if ($action == 'add') echo get_url('plugin/catalog/part/add'); else echo get_url('plugin/catalog/part/edit/'.$part->id); ?>">
    <table border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td class="label"><label for="part_sku"><?php echo __('SKU'); ?></label></td>
                <td class="field"><input class="textbox" type="text" name="part[sku]" id="part_sku" value="<?php echo $part->sku; ?>" /></td>
            </tr>
            <tr>
                <td class="label"><label for="part_description"><?php echo __('Description'); ?></label></td>
                <td class="field"><input class="textbox" type="text" name="part[description]" id="part_description" value="<?php echo $part->description; ?>" /></td>
            </tr>
            <tr>
                <td class="label"><label for="part_weight"><?php echo __('Weight'); ?></label></td>
                <td class="field"><input class="textbox" type="text" name="part[weight]" id="part_weight" value="<?php echo $part->weight; ?>" /></td>
            </tr>
            <tr>
                <td class="label"><label for="part_price"><?php echo __('Price'); ?></label></td>
                <td class="field"><input class="textbox" type="text" name="part[price]" id="part_price" value="<?php echo $part->price; ?>" /></td>
            </tr>
            <tr>
                <td class="label"><label for="part_stock"><?php echo __('Stock'); ?></label></td>
                <td class="field"><input class="textbox" type="text" name="part[stock]" id="part_stock" value="<?php echo $part->stock; ?>" /></td>
            </tr>
        </tbody>
    </table>
    
    <p class="buttons">
        <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save and Close'); ?>" />
        <input class="button" name="continue" type="submit" accesskey="e" value="<?php echo __('Save and Continue Editing'); ?>" />
        <?php echo __('or'); ?> <a href="<?php echo get_url('plugin/catalog/parts'); ?>"><?php echo __('Cancel'); ?></a>
    </p>
</form>