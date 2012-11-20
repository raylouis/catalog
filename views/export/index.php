<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nic.wortel@nth-root.nl>
 * 
 * @file        /views/backend/pick_format.php
 * @date        19/09/2012
 */

?>
<h1><?php echo __('Export ' . $model . 's'); ?></h1>

<p><a href="<?php echo get_url('plugin/catalog/export/' . $model . '/csv'); ?>"><?php echo __('CSV'); ?></a></p>

