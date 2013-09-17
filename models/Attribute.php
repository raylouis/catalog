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

use_helper('ActiveRecord');

class Attribute extends ActiveRecord {
    const TABLE_NAME = 'catalog_attribute';
    
    static $belongs_to = array(
        'type' => array(
            'class_name' => 'AttributeType',
            'foreign_key' => 'attribute_type_id'
        ),
        'default_unit' => array(
            'class_name' => 'AttributeUnit',
            'foreign_key' => 'default_unit_id'
        )
    );
    static $has_many = array(
        'category_attributes' => array(
            'class_name' => 'CategoryAttribute',
            'foreign_key' => 'attribute_id'
        ),
        'categories' => array(
            'class_name' => 'Category',
            'foreign_key' => 'attribute_id',
            'through' => 'category_attributes'
        )
    );
    
    public $id;
    public $name = '';
    public $description = '';
    public $attribute_type_id;
    public $default_unit_id;
    
    public static function findAll() {
        return self::find(array(
            'order' => 'id ASC',
            'include' => array('type' => array('units'), 'default_unit')
        ));
    }
    
    public static function findById($id) {
        return self::find(array(
            'where' => array('id = ?', $id),
            'limit' => 1,
            'include' => array('type' => array('units'))
        ));
    }
    
    public function findValuesByProductVariantId($product_variant_id) {
        return ProductVariantValue::find(array(
            'where' => array('attribute_id = ? AND product_variant_id = ?', $this->id, $product_variant_id),
            'include' => array('unit')
        ));
    }

    public function findValuesByProductId($product_id) {
        return ProductVariantValue::find(array(
            'select' => 'product_variant_value.*',
            'from' => 'catalog_product_variant_value AS product_variant_value',
            'joins' => 'INNER JOIN catalog_product_variant AS product_variant ON product_variant.id = product_variant_value.product_variant_id',
            'where' => array('attribute_id = ? AND product_variant.product_id = ?', $this->id, $product_id),
            'include' => array('unit')
        ));
    }
    
//    public function findOptionsByCategory($category_id) {
//        $category_ids = Category::subcategoryIdsOf($category_id);
//        
//        $placeholders = array();
//        
//        
//        return ProductVariantAttribute::find(array(
//            'select' => 'variant_attr.*, COUNT(DISTINCT prod.id) AS prod_count',
//            'from' => ProductVariantAttribute::TABLE_NAME . ' AS variant_attr',
//            'joins' => 
//                'INNER JOIN ' . ProductVariant::TABLE_NAME . ' AS variant ON variant.id = variant_attr.variant_id
//                INNER JOIN  ' . Product::TABLE_NAME . ' AS prod ON prod.id = variant.product_id',
//            'where' => array('variant_attr.attribute_id = ? AND prod.category_id IN (' . implode(',', $category_ids) . ')', $this->id),
//            'group' => 'variant_attr.value ASC'
//        ));
//    }
    
    public function getColumns() {
        return array(
            'id', 'name', 'description', 'attribute_type_id', 'default_unit_id'
        );
    }
}