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

class OrderItem extends ActiveRecord {
    const TABLE_NAME = 'catalog_order_item';
    
    static $belongs_to = array(
        'order' => array(
            'class_name' => 'Order',
            'foreign_key' => 'order_id'
        ),
        'product_variant' => array(
            'class_name' => 'ProductVariant',
            'foreign_key' => 'product_variant_id'
        )
    );
    
    public $id;
    public $status = '';
    public $user_id;
    
    public $created_on;
    public $paid_on;
    
    public $url = '';
    
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
    
    public function getColumns() {
        return array(
            'id', 'order_id', 'product_variant_id', 'quantity'
        );
    }
    
    public function price() {
        return $this->product_variant->price;
    }
    
    public function total() {
        return $this->price() * $this->quantity;
    }
}