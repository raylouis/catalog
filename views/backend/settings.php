<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nd.wortel@gmail.com>
 * 
 * @file        /views/backend/settings.php
 * @date        13/09/2012
 */

?>

<h1><?php echo __('Settings'); ?></h1>

<form method="post" action="<?php echo get_url('plugin/catalog/settings'); ?>">
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
            <td colspan="2">
                <br />
                <input type="submit" name="save" value="<?php echo __('Save Settings'); ?>" />
            </td>
        </tr>
    </table>
</form>