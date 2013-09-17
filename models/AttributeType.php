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