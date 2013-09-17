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
<h1><?php echo __('Units'); ?></h1>

<p><a href="<?php echo get_url('plugin/catalog/unit/add'); ?>"><?php echo __('Add unit'); ?></a></p>

<table class="unit list">
    <thead>
        <tr>
            <th class="fill">
                <?php echo __('Name'); ?>
            </th>
            <th class="">
                <?php echo __('Abbreviation'); ?>
            </th>
            <th class="fill">
                <?php echo __('Comparison'); ?>
            </th>
            <th class="fill">
                <?php echo __('Type'); ?>
            </th>
            <th class="fill">
                <?php echo __('System'); ?>
            </th>
            <th class="icon"><?php echo __('Delete'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($units as $unit): ?>
        <tr>
            <td class="fill">
                <a href="<?php echo get_url('plugin/catalog/unit/edit', $unit->id); ?>"><?php echo $unit->name; ?></a>
            </td>
            <td class="">
                <?php echo $unit->abbreviation; ?>
            </td>
            <td class="">
                <?php echo $unit->compareParent(); ?>
            </td>
            <td class="">
                <?php echo $unit->type->name; ?>
            </td>
            <td class="">
                Metric
            </td>
            <td class="icon">
                <?php if (AuthUser::hasPermission('catalog_unit_delete')): ?>
                    <a href="<?php echo get_url('plugin/catalog/unit/delete', $unit->id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete unit :name?', array(':name' => $unit->name)); ?>');">
                        <img width="16" height="16" src="<?php echo CATALOG_IMAGES; ?>action-delete-16.png" alt="<?php echo __('Delete'); ?>" title="<?php echo __('Delete'); ?>" />
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>