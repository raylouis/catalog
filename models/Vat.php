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

class Vat extends ActiveRecord {
    const TABLE_NAME = 'catalog_vat';
    
    static $has_many = array(
        'product_variant' => array(
            'class_name' => 'ProductVariant',
            'foreign_key' => 'vat_id'
        )
    );
    
    public $id;
    public $name = '';
    public $percentage;
    
    public static function findAll() {
        return self::find(array(
            'order' => 'percentage DESC'
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
            'id', 'name', 'percentage'
        );
    }
}