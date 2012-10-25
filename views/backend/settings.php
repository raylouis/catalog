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
            <td><strong><?php echo __('Layout'); ?></strong></td>
            <td>
                <select name="setting[layout_id]">
                    <option value="0"<?php if ($settings['layout_id'] == 0) echo ' selected="selected"'; ?>><?php echo __('inherit'); ?></option>
                    <?php foreach ($layouts as $layout): ?>
                    <option value="<?php echo $layout->id; ?>"<?php if ($settings['layout_id'] == $layout->id) echo ' selected="selected"'; ?>><?php echo $layout->name; ?></option>
                    <?php endforeach; ?>
                </select>
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