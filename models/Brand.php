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

class Brand extends ActiveRecord {
    const TABLE_NAME = 'catalog_brand';
    
    static $has_many = array(
        'products' => array(
            'class_name' => 'Product',
            'foreign_key' => 'brand_id'
        )
    );
    static $belongs_to = array(
        'logo' => array(
            'class_name' => 'Image',
            'foreign_key' => 'logo_id'
        )
    );
    
    public $id;
    public $name = '';
    public $slug = '';
    public $description = '';
    public $website = '';
    
    public $created_on;
    public $updated_on;
    public $created_by_id;
    public $updated_by_id;
    
    public $url = '';
    
    public function __construct() {
        $this->setUrl();
    }
    
    public function beforeInsert() {
        $this->created_on       = date('Y-m-d H:i:s');
        $this->created_by_id    = AuthUser::getRecord()->id;

        return true;
    }
    
    public function beforeSave() {
        $this->slug             = Node::toSlug($this->name);
        
        $this->updated_on       = date('Y-m-d H:i:s');
        $this->updated_by_id    = AuthUser::getRecord()->id;
        
        return true;
    }
    
    public function date($format='%a, %e %b %Y', $which_one='created') {
        if ($which_one == 'update' || $which_one == 'updated') {
            return strftime($format, strtotime($this->updated_on));
        }
        else {
            return strftime($format, strtotime($this->created_on));
        }
    }
    
    public static function findAll() {
        return self::find(array(
            'order' => 'name ASC',
            'include' => array('logo')
        ));
    }
    
    public static function findById($id) {
        return self::find(array(
            'where' => array('id = ?', $id),
            'limit' => 1,
            'include' => array('logo')
        ));
    }
    
    public static function findBySlug($slug) {
        return self::find(array(
            'where' => array('slug = ?', $slug),
            'limit' => 1,
            'include' => array('products' => array('brand', 'category'), 'logo')
        ));
    }
    
    public function getColumns() {
        return array(
            'id', 'name', 'slug', 'description', 'website', 'logo_id',
            'created_on', 'updated_on', 'created_by_id', 'updated_by_id'
        );
    }
    
    public function keywords() {
        return strtolower(implode(', ', explode(' ', $this->name . ' ' . $this->brand->name . ' ' . $this->category->title)));
    }
    
    public function url() {
        return URL_PUBLIC . $this->url . ($this->url != '' ? URL_SUFFIX: '');
    }
    
    public function setUrl() {
        $brands_slug = Plugin::getSetting('brands_slug', 'catalog');
        $this->url = trim($brands_slug . '/' . $this->slug, '/');
    }
}