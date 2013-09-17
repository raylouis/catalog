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
    
    public function beforeDelete() {
        $casted_value_class = 'Value' . ucfirst(strtolower(Attribute::findById($this->attribute_id)->type->data_type));
        
        if ($value = $casted_value_class::findByProductVariantValueId($this->id)) {
            if ($value->delete()) {
                return true;
            }
        }
        
        return false;
    }
    
    public function beforeSave() {
        $this->attribute_unit_id = (isset($this->unit)) ? $this->unit : null;
        $this->flat_value = (isset($this->value)) ? $this->value : null;
        
        return true;
    }
    
    public function afterSave() {
        $casted_value_class = 'Value' . ucfirst(strtolower(Attribute::findById($this->attribute_id)->type->data_type));

        if (!$value = $casted_value_class::findByProductVariantValueId($this->id)) {
            $value = new $casted_value_class();
        }
        
        $value->value = $this->flat_value;
        $value->product_variant_value_id = $this->id;
        
        if (!$value->save()) {
            print_r($value);
            die;
        }
        
        return true;
    }

    public static function deleteByProductVariantId($product_variant_id) {
        $product_variant_id = (int) $product_variant_id;
        
        $values = self::findByProductVariantId($product_variant_id);
        
        if (is_array($values)) {
            foreach ($values as $value) {
                if (!$value->delete()) {
                    return false;
                }
            }
        }
        elseif ($values instanceof ProductVariantValue) {
            if (!$values->delete()) {
                return false;
            }
        }
        
        return true;
    }
    
    public function getColumns() {
        return array(
            'id', 'product_variant_id', 'attribute_id', 'attribute_unit_id', 'flat_value'
        );
    }
    
    public static function findByProductVariantId($id) {
        return self::find(array(
            'where' => array('product_variant_id = ?', $id) 
        ));
    }
}