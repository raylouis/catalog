<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nd.wortel@gmail.com>
 * 
 * @file        /models/Attribute.php
 * @date        30/09/2012
 */

use_helper('ActiveRecord');

class Attribute extends ActiveRecord {
    const TABLE_NAME = 'catalog_attribute';
    
    static $has_many = array(
        'product_attributes' => array(
            'class_name' => 'ProductAttribute',
            'foreign_key' => 'attribute_id'
        ),
        'product_variable_attributes' => array(
            'class_name' => 'ProductVariableAttribute',
            'foreign_key' => 'attribute_id'
        ),
        'variant_attributes' => array(
            'class_name' => 'ProductVariantAttribute',
            'foreign_key' => 'attribute_id'
        ),
        'filter_options' => array(
            'select' => '*, COUNT(id) AS part_count',
            'class_name' => 'ProductVariantAttribute',
            'foreign_key' => 'attribute_id',
            'group' => 'value ASC'
        ),
        'category_filters' => array(
            'class_name' => 'CategoryFilter',
            'foreign_key' => 'attribute_id'
        )
    );
    
    public $id;
    public $name = '';
    public $description = '';
    public $type = '';
    public $unit;
    
    public static function findAll() {
        return self::find(array(
            'order' => 'id ASC'
        ));
    }
    
    public static function findById($id) {
        return self::find(array(
            'where' => array('id = ?', $id),
            'limit' => 1
        ));
    }
    
    public function findOptionsByCategory($category_id) {
        $category_ids = Category::subcategoryIdsOf($category_id);
        
        $placeholders = array();
        
        
        return ProductVariantAttribute::find(array(
            'select' => 'variant_attr.*, COUNT(DISTINCT prod.id) AS prod_count',
            'from' => ProductVariantAttribute::TABLE_NAME . ' AS variant_attr',
            'joins' => 
                'INNER JOIN ' . ProductVariant::TABLE_NAME . ' AS variant ON variant.id = variant_attr.variant_id
                INNER JOIN  ' . Product::TABLE_NAME . ' AS prod ON prod.id = variant.product_id',
            'where' => array('variant_attr.attribute_id = ? AND prod.category_id IN (' . implode(',', $category_ids) . ')', $this->id),
            'group' => 'variant_attr.value ASC'
        ));
    }
    
    public function getColumns() {
        return array(
            'id', 'name', 'description', 'type', 'unit'
        );
    }
}