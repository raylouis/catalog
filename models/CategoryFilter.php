<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nd.wortel@gmail.com>
 * 
 * @file        /models/CategoryFilter.php
 * @date        15/10/2012
 */

use_helper('ActiveRecord');

class CategoryFilter extends ActiveRecord {
    const TABLE_NAME = 'catalog_category_filter';
    
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
}