<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nic.wortel@nth-root.nl>
 * 
 * @file        /models/ProductAttribute.php
 * @date        30/09/2012
 */

use_helper('ActiveRecord');

class ProductAttribute extends ActiveRecord {
    const TABLE_NAME = 'catalog_product_attribute';
    
    static $belongs_to = array(
        'attribute' => array(
            'class_name' => 'Attribute',
            'foreign_key' => 'attribute_id'
        ),
        'product' => array(
            'class_name' => 'Product',
            'foreign_key' => 'product_id'
        )
    );
    
    public $id;
    public $attribute_id;
    public $product_id;
    public $value;
    
    public static function deleteByProductId($product_id) {
        $product_id = (int) $product_id;
        
        $product_attributes = self::findByProductId($product_id);
        
        if (is_array($product_attributes)) {
            foreach ($product_attributes as $product_attribute) {
                if (!$product_attribute->delete()) {
                    return false;
                }
            }
        }
        elseif ($product_attributes instanceof CatalogProductColor) {
            if (!$product_attributes->delete()) {
                return false;
            }
        }
        
        return true;
    }
    
    public static function findByProductId($id) {
        return self::find(array(
            'where' => array('product_id = ?', $id)
        ));
    }
}