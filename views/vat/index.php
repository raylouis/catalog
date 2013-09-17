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
<h1><?php echo __('VAT rates'); ?></h1>

<p><a href="<?php echo get_url('plugin/catalog/vat/add'); ?>"><?php echo __('Add VAT rate'); ?></a></p>

<table class="vat list">
    <thead>
        <tr>
            <th class="fill">
                <?php echo __('VAT rate'); ?>
            </th>
            <th class="number">
                <?php echo __('Percentage'); ?>
            </th>
            <th class="icon"><?php echo __('Delete'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($vat_rates as $vat): ?>
        <tr>
            <td class="fill">
                <a href="<?php echo get_url('plugin/catalog/vat/edit', $vat->id); ?>"><?php echo $vat->name; ?></a>
            </td>
            <td class="number">
                <?php echo $vat->percentage; ?> %
            </td>
            <td class="icon">
                <?php if (AuthUser::hasPermission('catalog_vat_delete')): ?>
                    <a href="<?php echo get_url('plugin/catalog/vat/delete', $vat->id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete :name (:percentage)?', array(':name' => $vat->name, ':percentage' => $vat->percentage)); ?>');">
                        <img width="16" height="16" src="<?php echo CATALOG_IMAGES; ?>action-delete-16.png" alt="<?php echo __('Delete'); ?>" title="<?php echo __('Delete'); ?>" />
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>