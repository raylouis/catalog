<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nd.wortel@gmail.com>
 * 
 * @file        /views/backend/pick_format.php
 * @date        19/09/2012
 */

?>
<h1><?php echo __('Export ' . $model . 's'); ?></h1>

<p><a href="<?php echo get_url('plugin/catalog/export/' . $model . '/csv'); ?>"><?php echo __('CSV'); ?></a></p>

