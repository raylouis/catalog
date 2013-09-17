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
<h1><?php echo __(ucfirst($action).' VAT rate'); ?></h1>

<form method="post" action="<?php if ($action == 'add') echo get_url('plugin/catalog/vat/add'); else echo get_url('plugin/catalog/vat/edit/' . $vat->id); ?>" enctype="multipart/form-data">
    <table border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td class="label"><label for="vat_name"><?php echo __('Name'); ?></label></td>
                <td class="field"><input class="textbox" type="text" name="vat[name]" id="vat_name" value="<?php echo $vat->name; ?>" /></td>
            </tr>
            <tr>
                <td class="label"><label for="vat_percentage"><?php echo __('Percentage'); ?></label></td>
                <td class="field"><input class="textbox" type="text" name="vat[percentage]" id="vat_percentage" value="<?php echo $vat->percentage; ?>" /> %</td>
            </tr>
        </tbody>
    </table>
    
    <p class="buttons">
        <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save and Close'); ?>" />
        <input class="button" name="continue" type="submit" accesskey="e" value="<?php echo __('Save and Continue Editing'); ?>" />
        <?php echo __('or'); ?> <a href="<?php echo get_url('plugin/catalog/vat_rates'); ?>"><?php echo __('Cancel'); ?></a>
    </p>
</form>