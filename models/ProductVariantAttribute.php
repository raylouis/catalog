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
 * @version     0.0.1
 */

use_helper('ActiveRecord');

class ProductVariantAttribute extends ActiveRecord {
    const TABLE_NAME = 'catalog_product_variant_attribute';
    
    static $belongs_to = array(
        'attribute' => array(
            'class_name' => 'Attribute',
            'foreign_key' => 'attribute_id'
        ),
        'product_variant' => array(
            'class_name' => 'ProductVariant',
            'foreign_key' => 'variant_id'
        )
    );
    
    public $id;
    public $variant_id;
    public $attribute_id;
    public $value;
}