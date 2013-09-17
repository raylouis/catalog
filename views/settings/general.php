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

<h1><?php echo __('General settings'); ?></h1>

<form method="post" action="<?php echo get_url('plugin/catalog/settings/general'); ?>">
    <table>
        <tr>
            <td class="label"><strong><?php echo __('Layout'); ?></strong></td>
            <td class="field">
                <select name="setting[layout_id]">
                    <option value="0"<?php if ($settings['layout_id'] == 0) echo ' selected="selected"'; ?>><?php echo __('inherit'); ?></option>
                    <?php foreach ($layouts as $layout): ?>
                    <option value="<?php echo $layout->id; ?>"<?php if ($settings['layout_id'] == $layout->id) echo ' selected="selected"'; ?>><?php echo $layout->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="label">
                <strong><?php echo __('Decimal seperator'); ?></strong>
            </td>
            <td class="field">
                <label for="decimal_point">
                    <input type="radio" name="setting[decimal_seperator]" id="decimal_point" value="point" <?php echo ($settings['decimal_seperator'] == 'point') ? ' checked="checked"' : null; ?>/>
                    <?php echo __('Point'); ?>
                </label><br />
                <label for="decimal_comma">
                    <input type="radio" name="setting[decimal_seperator]" id="decimal_comma" value="comma" <?php echo ($settings['decimal_seperator'] == 'comma') ? ' checked="checked"' : null; ?>/>
                    <?php echo __('Comma'); ?>
                </label>
            </td>
        </tr>
        <tr>
            <td class="label"><strong><label for="brands_title"><?php echo __('Brands title'); ?></label></strong></td>
            <td class="field">
                <input type="text" name="setting[brands_title]" id="brands_title" value="<?php echo $settings['brands_title']; ?>" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <br />
                <input type="submit" name="save" value="<?php echo __('Save Settings'); ?>" />
            </td>
        </tr>
    </table>
</form>