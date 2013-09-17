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
<h1><?php echo __('Products'); ?></h1>

<form name="search" method="post" style="float: right;">
    <input type="text" name="search" autocomplete="off" placeholder="<?php echo (isset($_POST['search']) && $_POST['search'] != '') ? $_POST['search'] : __('Search'); ?>" />
</form>

<p>
    <a href="<?php echo get_url('plugin/catalog/product/add'); ?>"><?php echo __('Add product'); ?></a> |
    <a href="<?php echo get_url('plugin/catalog/export/product'); ?>"><?php echo __('Export'); ?></a>
</p>

<table class="product list">
    <thead>
        <tr>
            <th class="fill<?php echo get_sorted('name', $order_by, $order_direction); ?>">
                <a href="<?php echo get_url('plugin/catalog/products/name', get_direction('name', $order_by, $order_direction)); ?>"><?php echo __('Name'); ?></a>
            </th>
            <th class="fill<?php echo get_sorted('brand', $order_by, $order_direction); ?>">
                <a href="<?php echo get_url('plugin/catalog/products/brand', get_direction('brand', $order_by, $order_direction)); ?>"><?php echo __('Brand'); ?></a>
            </th>
            <th class="fill<?php echo get_sorted('category', $order_by, $order_direction); ?>">
                <a href="<?php echo get_url('plugin/catalog/products/category', get_direction('category', $order_by, $order_direction)); ?>"><?php echo __('Category'); ?></a>
            </th>
            <th class="number<?php echo get_sorted('variants', $order_by, $order_direction); ?>">
                <a href="<?php echo get_url('plugin/catalog/products/variants', get_direction('variants', $order_by, $order_direction)); ?>"><?php echo __('Variants'); ?></a>
            </th>
            <th class="price<?php echo get_sorted('price', $order_by, $order_direction); ?>">
                <a href="<?php echo get_url('plugin/catalog/products/price', get_direction('price', $order_by, $order_direction)); ?>"><?php echo __('Price'); ?></a>
            </th>
            <th class="number<?php echo get_sorted('stock', $order_by, $order_direction); ?>">
                <a href="<?php echo get_url('plugin/catalog/products/stock', get_direction('stock', $order_by, $order_direction)); ?>"><?php echo __('Stock'); ?></a>
            </th>
            <th class="icon"><?php echo __('View'); ?></th>
            <th class="icon"><?php echo __('Delete'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($products as $product): ?>
        <tr>
            <td class="fill">
                <a href="<?php echo get_url('plugin/catalog/product/edit', $product->id); ?>">
                    <?php echo $product->name; ?>
                </a>
            </td>
            <td class="fill">
                <?php if (isset($product->brand)): ?>
                <?php echo $product->brand->name; ?>
                <?php else: ?>
                -
                <?php endif; ?>
            </td>
            <td class="fill">
                <?php echo $product->category->title; ?>
            </td>
            <td class="number">
                <?php echo $product->variant_count; ?>
            </td>
            <td class="price">
                <?php if (!is_null($product->min_price)): ?>
                <?php if (Plugin::getSetting('decimal_seperator', 'catalog') == 'comma'): ?>
                € <?php echo number_format($product->min_price, 2, ',', '.'); ?>
                <?php else: ?>
                € <?php echo number_format($product->min_price, 2, '.', ','); ?>
                <?php endif; ?>
                <?php else: ?>
                <span class="not-applicable"><?php echo __('N/A'); ?></span>
                <?php endif; ?>
            </td>
            <td class="number">
                <?php $product->stock = $product->total_stock; ?>
                <?php if (!is_null($product->stock)): ?>
                <?php echo $product->stock; ?>
                <?php else: ?>
                <span class="not-applicable"><?php echo __('N/A'); ?></span>
                <?php endif; ?>
            </td>
            <td class="icon">
                <a href="<?php echo $product->url(); ?>" target="_blank"><img src="<?php echo CATALOG_IMAGES; ?>action-open-16.png" alt="<?php echo __('View product'); ?>" title="<?php echo __('View product'); ?>" /></a>
            </td>
            <td class="icon">
                <?php if (AuthUser::hasPermission('catalog_product_delete')): ?>
                    <a href="<?php echo get_url('plugin/catalog/product/delete', $product->id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete :name and all its variants, prices, stock info etc.?', array(':name' => $product->name())); ?>');">
                        <img width="16" height="16" src="<?php echo CATALOG_IMAGES; ?>action-delete-16.png" alt="<?php echo __('Delete'); ?>" title="<?php echo __('Delete'); ?>" />
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>