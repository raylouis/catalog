<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nd.wortel@gmail.com>
 * 
 * @file        /models/ProductVariableOption.php
 * @date        30/09/2012
 */

use_helper('ActiveRecord');

class ProductVariableOption extends ActiveRecord {
    const TABLE_NAME = 'catalog_product_variable_option';
    
    static $belongs_to = array(
        'variable_attribute' => array(
            'class_name' => 'ProductVariableAttribute',
            'foreign_key' => 'variable_id'
        )
    );
    
    public $id;
    public $variable_id;
    public $value;
    
    
}