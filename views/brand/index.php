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
 * @version     0.2.0
 */

?>
<h1><?php echo __('Brands'); ?></h1>

<form name="search" method="post" style="float: right;">
    <input type="text" name="search" autocomplete="off" placeholder="<?php echo (isset($_POST['search']) && $_POST['search'] != '') ? $_POST['search'] : 'Zoeken...'; ?>" />
</form>

<p><a href="<?php echo get_url('plugin/catalog/brand/add'); ?>"><?php echo __('Add brand'); ?></a></p>

<table class="brand list data-sortable">
    <thead>
        <tr>
            <th class="number">
                <?php echo __('ID'); ?>
            </th>
            <th class="fill">
                <?php echo __('Name'); ?>
            </th>
            <th class="fill">
                <?php echo __('Website'); ?>
            </th>
            <th class="icon" data-sorter="false"><?php echo __('View'); ?></th>
            <th class="icon" data-sorter="false"><?php echo __('Delete'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($brands as $brand): ?>
        <tr>
            <td class="number">
                <?php echo $brand->id; ?>
            </td>
            <td class="fill">
                <a href="<?php echo get_url('plugin/catalog/brand/edit', $brand->id); ?>"><?php echo $brand->name; ?></a>
            </td>
            <td class="fill">
                <a href="<?php echo $brand->website; ?>" target="_blank"><?php echo $brand->website; ?></a>
            </td>
            <td class="icon">
                <a href="<?php echo $brand->url(); ?>" target="_blank"><img src="<?php echo CATALOG_IMAGES; ?>action-open-16.png" alt="<?php echo __('View brand'); ?>" title="<?php echo __('View brand'); ?>" /></a>
            </td>
            <td class="icon">
                <?php if (AuthUser::hasPermission('catalog_brand_delete')): ?>
                    <a href="<?php echo get_url('plugin/catalog/brand/delete', $brand->id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete :name?', array(':name' => $brand->name)); ?>');">
                        <img width="16" height="16" src="<?php echo CATALOG_IMAGES; ?>action-delete-16.png" alt="<?php echo __('Delete'); ?>" title="<?php echo __('Delete'); ?>" />
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
