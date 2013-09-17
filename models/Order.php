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

class Order extends ActiveRecord {
    const TABLE_NAME = 'catalog_order';
    
    static $has_many = array(
        'items' => array(
            'class_name' => 'OrderItem',
            'foreign_key' => 'order_id'
        )
    );
    
    public $id;
    public $status = '';
    public $user_id;
    
    public $created_on;
    public $paid_on;
    
    public $url = '';
    
    public function beforeInsert() {
        $this->created_on       = date('Y-m-d H:i:s');
        
        return true;
    }
    
    public function beforeSave() {
        return true;
    }
    
    public function date($format='%a, %e %b %Y', $which_one='created') {
        if ($which_one == 'update' || $which_one == 'updated') {
            return strftime($format, strtotime($this->updated_on));
        }
        else {
            return strftime($format, strtotime($this->created_on));
        }
    }
    
    public static function findAll() {
        return self::find(array(
            'order' => 'id ASC',
            'include' => array('items' => array('product_variant'))
        ));
    }
    
    public static function findById($id) {
        return self::find(array(
            'where' => array('id = ?', $id),
            'limit' => 1,
            'include' => array('items' => array('product_variant'))
        ));
    }
    
    public function getColumns() {
        return array(
            'id', 'status', 'user_id',
            'created_on', 'paid_on'
        );
    }
    
    public static function markupPrice($price) {
        if (Plugin::getSetting('decimal_seperator', 'catalog') == 'comma') {
            return number_format($price, 2, ',', '');
        }
        else {
            return number_format($price, 2, '.', '');
        }
        
    }
}