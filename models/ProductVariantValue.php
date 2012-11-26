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

class ProductVariantValue extends ActiveRecord {
    const TABLE_NAME = 'catalog_product_variant_value';
    
    static $belongs_to = array(
        'product_variant' => array(
            'class_name' => 'ProductVariant',
            'foreign_key' => 'product_variant_id'
        ),
        'attribute' => array(
            'class_name' => 'Attribute',
            'foreign_key' => 'attribute_id'
        ),
        'unit' => array(
            'class_name' => 'AttributeUnit',
            'foreign_key' => 'attribute_unit_id'
        )
    );
    
    public $id;
    public $product_variant_id;
    public $attribute_id;
    public $attribute_unit_id;
    public $flat_value = '';
}