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

class ProductVariant extends ActiveRecord {
    const TABLE_NAME = 'catalog_product_variant';
    
    static $belongs_to = array(
        'product' => array(
            'class_name' => 'Product',
            'foreign_key' => 'product_id'
        ),
        'vat' => array(
            'class_name' => 'Vat',
            'foreign_key' => 'vat_id'
        )
    );
    
    public $id;
    public $sku;
    public $name = '';
    public $weight;
    public $price;
    public $vat_id;
    public $stock;
    
    public $product_id;
    
    public $created_on;
    public $updated_on;
    public $created_by_id;
    public $updated_by_id;
    
    public function afterSave() {
        $old_values = ProductVariantValue::findByProductVariantId($this->id);

        foreach ($old_values as $old_value) {
            $not_in = true;
            if (isset($this->attributes)) {
                foreach ($this->attributes as $attribute_id => $data) {
                    if ($old_value->attribute_id == $attribute_id && $data['value'] != '') {
                        $not_in = false;

                        $old_value->setFromData($data);
                        $old_value->save();

                        unset($this->attributes[$attribute_id]);

                        break;
                    }
                }
            }

            if ($not_in) {
                $old_value->delete();
            }
        }

        foreach ($this->attributes as $attribute_id => $data) {
            $value = new ProductVariantValue();
            $value->setFromData($data);
            $value->attribute_id = $attribute_id;
            $value->product_variant_id = $this->id;

            if (!empty($value->value)) {
                $value->save();
            }
        }
        
        return true;
    }

    public function beforeDelete() {
        if (!ProductVariantValue::deleteByProductVariantId($this->id)) {
            return false;
        }
        
        return true;
    }
    
    public function beforeInsert() {
        $this->created_on       = date('Y-m-d H:i:s');
        $this->created_by_id    = AuthUser::getRecord()->id;

        return true;
    }
    
    public function beforeSave() {
        $this->updated_on       = date('Y-m-d H:i:s');
        $this->updated_by_id    = AuthUser::getRecord()->id;
        
        $this->price = (float) str_replace(',', '.', $this->price);
        
        return true;
    }
    
    public static function deleteByProductId($product_id) {
        $product_id = (int) $product_id;
        
        $variants = self::findByProductId($product_id);
        
        if (is_array($variants)) {
            foreach ($variants as $variant) {
                if (!$variant->delete()) {
                    return false;
                }
            }
        }
        elseif ($variants instanceof ProductVariant) {
            if (!$variants->delete()) {
                return false;
            }
        }
        
        return true;
    }
    
    public static function findAll() {
        return self::find(array(
            'order' => 'sku ASC'
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
            'id', 'sku', 'name', 'weight', 'price', 'vat_id', 'stock', 'product_id',
            'created_on', 'updated_on', 'created_by_id', 'updated_by_id'
        );
    }
    
    public function price($include_vat = false, $format = false) {
        if ($include_vat) {
            $price_incl_tax = $this->price * ($this->vat->percentage / 100 + 1);
        }
        else {
            $price_incl_tax = $this->price;
        }
        
        if (Plugin::getSetting('decimal_seperator', 'catalog') == 'comma') {
            return number_format($price_incl_tax, 2, ',', '');
        }
        else {
            return number_format($price_incl_tax, 2, '.', '');
        }
    }
    
}