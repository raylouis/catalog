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
 * @version     0.0.1
 */

use_helper('ActiveRecord');

class AttributeUnit extends ActiveRecord {
    const TABLE_NAME = 'catalog_attribute_unit';
    
    static $belongs_to = array(
        'type' => array(
            'class_name' => 'AttributeType',
            'foreign_key' => 'attribute_type_id'
        ),
        'parent' => array(
            'class_name' => 'AttributeUnit',
            'foreign_key' => 'parent_id'
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
    
    public function compareParent() {
        $this->setParent();
        
        if (isset($this->parent)) {
            return '1 ' . $this->abbreviation . ' = ' . $this->multiplier . ' ' . $this->parent->abbreviation;
        }
        else {
            return false;
        }
    }
    
    public function setParent() {
        if (!isset($this->parent)) {
            $this->parent = AttributeUnit::findById($this->parent_id);
        }
    }
}