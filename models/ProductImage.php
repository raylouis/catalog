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

use_helper('ActiveRecord');

class ProductImage extends ActiveRecord
{
    const TABLE_NAME = 'catalog_product_image';

    static $belongs_to = array(
        'product' => array(
            'class_name' => 'Product',
            'foreign_key' => 'product_id'
        ),
        'file' => array(
            'class_name' => 'Attachment',
            'foreign_key' => 'attachment_id'
        )
    );

    public function html_img($resize_method = null, $width = null, $height = null)
    {
        return $this->file->html_img($resize_method, $width, $height);
    }

    public function url($resize_method = null, $width = null, $height = null)
    {
        return $this->file->url($resize_method, $width, $height);
    }
}
