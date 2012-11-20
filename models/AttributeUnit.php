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

class AttributeUnit extends ActiveRecord {
    const TABLE_NAME = 'catalog_attribute_unit';
    
    static $belongs_to = array(
        'type' => array(
            'class_name' => 'AttributeType',
            'foreign_key' => 'attribute_type_id'
        )
    );
    
    public $id;
    public $name = '';
    public $abbreviation = '';
    public $multiplier;
    public $parent_id;
    public $attribute_unit_system_id;
    public $attribute_type_id;
    
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
            'id', 'name', 'abbreviation', 'multiplier', 'parent_id', 'attribute_unit_system_id', 'attribute_type_id'
        );
    }
}