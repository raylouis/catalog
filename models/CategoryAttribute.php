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

class CategoryAttribute extends ActiveRecord {
    const TABLE_NAME = 'catalog_category_attribute';
    
    static $belongs_to = array(
        'category' => array(
            'class_name' => 'Category',
            'foreign_key' => 'category_id'
        ),
        'attribute' => array(
            'class_name' => 'Attribute',
            'foreign_key' => 'attribute_id'
        )
    );
    
    public $id;
    public $category_id;
    public $attribute_id;
    public $position;
    
    public static function findByCategoryId($id) {
        return self::find(array(
            'where' => array('category_id = ?', $id)
        ));
    }
    
    public static function findByCategoryIdAndAttributeId($category_id, $attribute_id) {
        return self::find(array(
            'where' => array('category_id = ? AND attribute_id = ?', $category_id, $attribute_id),
            'limit' => 1
        ));
    }
}