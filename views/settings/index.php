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

<h1><?php echo __('Settings'); ?></h1>

<p><a href="<?php echo get_url('plugin/catalog/settings/general'); ?>"><?php echo __('General settings'); ?></a></p>

<p><a href="<?php echo get_url('plugin/catalog/vat_rates'); ?>"><?php echo __('VAT rates'); ?></a></p>

<p><a href="<?php echo get_url('plugin/catalog/units'); ?>"><?php echo __('Units'); ?></a></p>