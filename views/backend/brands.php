<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nd.wortel@gmail.com>
 * 
 * @file        /views/backend/brands.php
 * @date        17/09/2012
 */

function get_direction($column, $order, $direction) {
    if ($column == $order && $direction == 'asc') {
        return 'desc';
    }
    else {
        return 'asc';
    }
}

function get_sorted($column, $order, $direction) {
    if ($column == $order) {
        return ' sorted ' . $direction . 'ending';
    }
    return '';
}

?>
<h1><?php echo __('Brands'); ?></h1>

<p><a href="<?php echo get_url('plugin/catalog/brand/add'); ?>"><?php echo __('Add brand'); ?></a></p>

<table class="brand list">
    <thead>
        <tr>
            <th class="number <?php echo get_sorted('id', $order_by, $order_direction); ?>">
                <a href="<?php echo get_url('plugin/catalog/brands/id', get_direction('id', $order_by, $order_direction)); ?>"><?php echo __('ID'); ?></a>
            </th>
            <th class="fill <?php echo get_sorted('name', $order_by, $order_direction); ?>">
                <a href="<?php echo get_url('plugin/catalog/brands/name', get_direction('name', $order_by, $order_direction)); ?>"><?php echo __('Name'); ?></a>
            </th>
            <th class="icon"><?php echo __('View'); ?></th>
            <th class="icon"><?php echo __('Delete'); ?></th>
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
            <td class="icon">
                <a href="<?php echo $brand->url(); ?>" target="_blank"><img src="<?php echo URI_PUBLIC;?>wolf/admin/images/magnify.png" alt="<?php echo __('View brand'); ?>" title="<?php echo __('View brand'); ?>" /></a>
            </td>
            <td class="icon">
                <?php if (AuthUser::hasPermission('catalog_brand_delete')): ?>
                    <a href="<?php echo get_url('plugin/catalog/brand/delete', $brand->id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete :name?', array(':name' => $brand->name)); ?>');">
                        <img width="16" height="16" src="<?php echo URI_PUBLIC;?>wolf/icons/delete-16.png" alt="<?php echo __('Delete'); ?>" title="<?php echo __('Delete'); ?>" />
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>