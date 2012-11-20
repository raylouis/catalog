<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nic.wortel@nth-root.nl>
 * 
 * @file        /models/AttributeUnit.php
 * @date        30/09/2012
 */

use_helper('ActiveRecord');

class AttributeType extends ActiveRecord {
    const TABLE_NAME = 'catalog_attribute_type';
    
    static $has_many = array(
        'units' => array(
            'class_name' => 'AttributeUnit',
            'foreign_key' => 'attribute_type_id'
        )
    );
    
    public $id;
    public $name = '';
    public $data_type = '';
    
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
            'id', 'name', 'data_type'
        );
    }
}