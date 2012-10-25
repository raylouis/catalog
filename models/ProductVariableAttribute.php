<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nd.wortel@gmail.com>
 * 
 * @file        /models/ProductVariableAttribute.php
 * @date        30/09/2012
 */

use_helper('ActiveRecord');

class ProductVariableAttribute extends ActiveRecord {
    const TABLE_NAME = 'catalog_product_variable_attribute';
    
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
    
    static $has_many = array(
        'options' => array(
            'class_name' => 'ProductVariableOption',
            'foreign_key' => 'variable_id'
        )
    );
    
    public $id;
    public $attribute_id;
    public $product_id;
    
    public static function deleteByProductId($product_id) {
        $product_id = (int) $product_id;
        
        $product_variable_attributes = self::findByProductId($product_id);
        
        if (is_array($product_variable_attributes)) {
            foreach ($product_variable_attributes as $product_variable_attribute) {
                if (!$product_variable_attribute->delete()) {
                    return false;
                }
            }
        }
        elseif ($product_variable_attributes instanceof CatalogProductColor) {
            if (!$product_variable_attributes->delete()) {
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