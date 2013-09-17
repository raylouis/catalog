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

class Value extends ActiveRecord {
    
    static $belongs_to = array(
        'value' => array(
            'class_name' => 'ProductVariantValue',
            'foreign_key' => 'product_variant_value_id'
        )
    );
    
    public $id;
    public $value;
    public $product_variant_value_id;
    
    public function beforeSave() {
        if ($this->convert()) {
            return true;
        }
    }
    
    public function convert() {
        $this->setProductVariantValue();
        
        if (!is_null($this->product_variant_value->attribute->default_unit_id)) {
            $original_unit_id = $this->product_variant_value->attribute_unit_id;
            $storage_unit_id = $this->product_variant_value->attribute->default_unit_id;

            if ($new_value = AttributeUnit::convert($this->value, $original_unit_id, $storage_unit_id)) {
                $this->value = $new_value;
                return true;
            }

            return false;
        }
        else {
            return true;
        }
    }
    
    public static function findByProductVariantValueId($id) {
        return self::find(array(
            'where' => array('product_variant_value_id = ?', $id),
            'limit' => 1,
            'include' => array('product_variant_value')
        ));
    }
    
    public function getColumns() {
        return array(
            'id', 'value', 'product_variant_value_id'
        );
    }
    
    public function setProductVariantValue() {
        if (!isset($this->product_variant_value)) {
            $this->product_variant_value = ProductVariantValue::findById($this->product_variant_value_id);
        }
    }
}