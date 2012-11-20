<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nic.wortel@nth-root.nl>
 * 
 * @file        /models/Vat.php
 * @date        30/09/2012
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
    public $description = '';
    public $type = '';
    public $unit;
    
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