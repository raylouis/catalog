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
 * @version     0.2.1
 */

?>
<h1><?php echo __('Documentation'); ?></h1>

<h3><?php echo __('Product variants'); ?></h3>
<p>
    <?php echo __('A :product-variant is a unique version of a certain product. One product can have multiple variants based on a certain attribute. For instance, when you sell t-shirts, you can have the same t-shirt (product) in multiple sizes and colors. In this example, all combinations of size and color (medium-sized red t-shirt, large blue t-shirt, etc.) are unique product variants, having their own stock and SKU.', array(':product-variant' => '<strong>' . __('product variant') . '</strong>')); ?>    
</p>
