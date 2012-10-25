<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nd.wortel@gmail.com>
 * 
 * @file        /models/ProductPart.php
 * @date        17/09/2012
 */

use_helper('ActiveRecord');

class ProductPart extends ActiveRecord {
    const TABLE_NAME = 'catalog_product_part';
    
    static $belongs_to = array(
        'product' => array(
            'class_name' => 'Product',
            'foreign_key' => 'product_id'
        ),
        'part' => array(
            'class_name' => 'Part',
            'foreign_key' => 'part_id'
        )
    );
    
    public $id;
    public $product_id;
    public $part_id;
    public $quantity;
    
    public function beforeInsert() {
        $this->created_on       = date('Y-m-d H:i:s');
        $this->created_by_id    = AuthUser::getRecord()->id;

        return true;
    }
    
    public function beforeSave() {
        $this->slug             = Node::toSlug($this->name);
        
        $this->updated_on       = date('Y-m-d H:i:s');
        $this->updated_by_id    = AuthUser::getRecord()->id;
        
        return true;
    }
    
    public static function deleteByProductId($product_id) {
        $product_id = (int) $product_id;
        
        $product_parts = self::findByProductId($product_id);
        
        if (is_array($product_parts)) {
            foreach ($product_parts as $product_part) {
                if (!$product_part->delete()) {
                    return false;
                }
            }
        }
        elseif ($product_parts instanceof CatalogProductColor) {
            if (!$product_parts->delete()) {
                return false;
            }
        }
        
        return true;
    }
    
    public static function findAll() {
        return self::find(array(
            'order' => 'slug ASC'
        ));
    }
    
    public static function findById($id) {
        return self::find(array(
            'where' => array('id = ?', $id),
            'limit' => 1
        ));
    }
    
    public static function findByProductId($id) {
        return self::find(array(
            'where' => array('product_id = ?', $id)
        ));
    }
    
    public function getColumns() {
        return array(
            'id', 'name', 'slug', 'description', 'category_id', 'brand_id',
            'created_on', 'updated_on', 'created_by_id', 'updated_by_id'
        );
    }
}