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