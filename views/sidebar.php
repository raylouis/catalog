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
 * @version     0.1.0
 */

?>
<?php if (AuthUser::hasPermission('catalog_product_view')): ?>
<p class="button">
    <a href="<?php echo get_url("plugin/catalog/products"); ?>">
        <img width="32" height="32" src="<?php echo CATALOG_IMAGES; ?>product.png" align="middle" alt="<?php echo __('Products'); ?>" />
        <?php echo __('Products'); ?>
    </a>
</p>
<?php endif; ?>
<?php if (AuthUser::hasPermission('catalog_category_view')): ?>
<p class="button">
    <a href="<?php echo get_url("plugin/catalog/categories"); ?>">
        <img width="32" height="32" src="<?php echo CATALOG_IMAGES; ?>category.png" align="middle" alt="<?php echo __('Categories'); ?>" />
        <?php echo __('Categories'); ?>
    </a>
</p>
<?php endif; ?>
<?php if (AuthUser::hasPermission('catalog_brand_view')): ?>
<p class="button">
    <a href="<?php echo get_url("plugin/catalog/brands"); ?>">
        <img width="32" height="32" src="<?php echo CATALOG_IMAGES; ?>brand.png" align="middle" alt="<?php echo __('Brands'); ?>" />
        <?php echo __('Brands'); ?>
    </a>
</p>
<?php endif; ?>
<?php if (AuthUser::hasPermission('catalog_attribute_view')): ?>
<p class="button">
    <a href="<?php echo get_url("plugin/catalog/attributes"); ?>">
        <img width="32" height="32" src="<?php echo CATALOG_IMAGES; ?>attributes.png" align="middle" alt="<?php echo __('Attributes'); ?>" />
        <?php echo __('Attributes'); ?>
    </a>
</p>
<?php endif; ?>
<p class="button">
    <a href="<?php echo get_url("plugin/catalog/settings"); ?>">
        <img width="32" height="32" src="<?php echo URL_PUBLIC; ?>wolf/icons/settings-32.png" align="middle" alt="<?php echo __('Settings'); ?>" />
        <?php echo __('Settings'); ?>
    </a>
</p>
<p class="button">
    <a href="<?php echo get_url("plugin/catalog/documentation"); ?>">
        <img width="32" height="32" src="<?php echo CATALOG_IMAGES; ?>documentation-32.png" align="middle" alt="<?php echo __('Documentation'); ?>" />
        <?php echo __('Documentation'); ?>
    </a>
</p>