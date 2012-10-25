<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nd.wortel@gmail.com>
 * 
 * @file        /views/backend/parts.php
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
<h1><?php echo __('Parts'); ?></h1>

<p>
    <a href="<?php echo get_url('plugin/catalog/part/add'); ?>"><?php echo __('Add part'); ?></a> |
    <a href="<?php echo get_url('plugin/catalog/export/part'); ?>"><?php echo __('Export'); ?></a>
</p>

<table class="part list">
    <thead>
        <tr>
            <th class="number<?php echo get_sorted('id', $order_by, $order_direction); ?>">
                <a href="<?php echo get_url('plugin/catalog/parts/id', get_direction('id', $order_by, $order_direction)); ?>"><?php echo __('ID'); ?></a>
            </th>
            <th class="fill<?php echo get_sorted('sku', $order_by, $order_direction); ?>">
                <a href="<?php echo get_url('plugin/catalog/parts/sku', get_direction('sku', $order_by, $order_direction)); ?>"><?php echo __('SKU'); ?></a>
            </th>
            <th class="fill<?php echo get_sorted('description', $order_by, $order_direction); ?>">
                <a href="<?php echo get_url('plugin/catalog/parts/description', get_direction('description', $order_by, $order_direction)); ?>"><?php echo __('Description'); ?></a>
            </th>
            <th class="long number<?php echo get_sorted('weight', $order_by, $order_direction); ?>">
                <a href="<?php echo get_url('plugin/catalog/parts/weight', get_direction('weight', $order_by, $order_direction)); ?>"><?php echo __('Weight'); ?></a>
            </th>
            <th class="price<?php echo get_sorted('price', $order_by, $order_direction); ?>">
                <a href="<?php echo get_url('plugin/catalog/parts/price', get_direction('price', $order_by, $order_direction)); ?>"><?php echo __('Price'); ?></a>
            </th>
            <th class="number<?php echo get_sorted('stock', $order_by, $order_direction); ?>">
                <a href="<?php echo get_url('plugin/catalog/parts/stock', get_direction('stock', $order_by, $order_direction)); ?>"><?php echo __('Stock'); ?></a>
            </th>
            <th class="icon"><?php echo __('Delete'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($parts as $part): ?>
        <tr>
            <td class="number">
                <?php echo $part->id; ?>
            </td>
            <td class="fill">
                <a href="<?php echo get_url('plugin/catalog/part/edit', $part->id); ?>">
                    <?php echo $part->sku; ?>
                </a>
            </td>
            <td class="fill">
                <?php echo $part->description; ?>
            </td>
            <td class="long number">
                <?php echo number_format($part->weight, 2, '.', ''); ?> kg
            </td>
            <td class="price">
                € <?php echo number_format($part->price, 2, ',', '.'); ?>
            </td>
            <td class="number">
                <?php echo $part->stock; ?>
            </td>
            <td class="icon">
                <?php if (AuthUser::hasPermission('catalog_part_delete')): ?>
                    <a href="<?php echo get_url('plugin/catalog/part/delete', $part->id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete :name?', array(':name' => $part->name)); ?>');">
                        <img width="16" height="16" src="<?php echo URI_PUBLIC;?>wolf/icons/delete-16.png" alt="<?php echo __('Delete'); ?>" title="<?php echo __('Delete'); ?>" />
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>