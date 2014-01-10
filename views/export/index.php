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
<h1><?php echo __('Export :models', array(':models' => __(ucfirst($plural)))); ?></h1>

<p><a href="<?php echo get_url('plugin/catalog/export/' . $model . '/csv'); ?>"><?php echo __('CSV'); ?></a></p>

