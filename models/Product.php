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
 * @version     0.1.0
 */

use_helper('ActiveRecord');

class Product extends ActiveRecord {
    const TABLE_NAME = 'catalog_product';
    
    static $belongs_to = array(
        'brand' => array(
            'class_name' => 'Brand',
            'foreign_key' => 'brand_id'
        ),
        'category' => array(
            'class_name' => 'Category',
            'foreign_key' => 'category_id'
        )
    );
    static $has_many = array(
        'variants' => array(
            'class_name' => 'ProductVariant',
            'foreign_key' => 'product_id'
        )
    );
    
    public $id;
    public $name = '';
    public $slug = '';
    public $description = '';
    public $type;
    public $category_id;
    public $brand_id;
    
    public $created_on;
    public $updated_on;
    public $created_by_id;
    public $updated_by_id;
    
    public $brand;
    public $category;
    public $variants = array();
    public $variable_attributes = array();
   
    public $url = '';
    
    public function __construct() {
        $this->setUrl();
    }
    
    public function afterSave() {
        if ($this->type == 'simple' && isset($_POST['variants'])) {
            
            foreach ($_POST['variants'] as $data) {
                if ($data['sku'] != '' && $data['price'] > 0) {
                    if (isset($data['id'])) {
                        $variant = ProductVariant::findById($data['id']);
                        $variant->setFromData($data);
                    }
                    else {
                        $variant = new ProductVariant();
                        $variant->setFromData($data);
                    }
                    $variant->name = $this->name();
                    $variant->product_id = $this->id;

                    if (!$variant->save()) {
                        return false;
                    }
                }   
            }
        }
        
        return true;
    }
    
    public function beforeDelete() {
        if (!ProductVariant::deleteByProductId($this->id)) {
            return false;
        }
        
        return true;
    }
    
    public function beforeInsert() {
        $this->created_on       = date('Y-m-d H:i:s');
        $this->created_by_id    = AuthUser::getRecord()->id;

        return true;
    }
    
    public function beforeSave() {
        $this->brand            = Brand::findById($this->brand_id);
        $this->slug             = Node::toSlug($this->brand->name . ' ' . $this->name);
        
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
            'order' => 'id ASC',
            'include' => array('brand', 'category', 'variants')
        ));
    }
    
    public static function findByCategoryId($category_id) {
        return self::find(array(
            'where' => array('category_id = ?', $category_id),
            'order' => 'slug ASC',
            'include' => array('brand')
        ));
    }
    
    public static function findByCategoryIdAndSlug($category_id, $slug) {
        return self::find(array(
            'where' => array('category_id = ? AND slug = ?', $category_id, $slug),
            'limit' => 1,
            'include' => array(
                'brand',
                'category',
                'product_attributes' => array('attribute'),
                'product_variable_attributes' => array('attribute', 'options'),
                'variants' => array('vat'),
                'variable_attributes'
            )
        ));
    }
    
    public static function findBySubcategories($category_id) {
        $category_ids = CatalogCategory::subcategories($category_id);
        $category_ids = implode(',', $category_ids);
        
        return self::find(array(
            'where' => "category_id IN ($category_ids)",
            'order' => 'slug ASC',
            'include' => array(
                'brand',
                'category',
                'photos',
                'colors'
            )
        ));
    }
    
    public static function findByBrandId($brand_id) {
        return self::find(array(
            'where' => array('brand_id = ?', $brand_id),
            'order' => 'name ASC',
            'include' => array(
                'brand',
                'category',
                'colors',
                'photos'
            )
        ));
    }
    
    public static function findById($id) {
        return self::find(array(
            'where' => array('id = ?', $id),
            'limit' => 1,
            'include' => array(
                'category',
                'variants'
            )
        ));
    }
    
    public static function findBySlug($slug) {
        return self::find(array(
            'where' => array('slug = ?', $slug),
            'limit' => 1
        ));
    }
    
    public static function findByUri($slugs) {
        $slug = end($slugs);
        $category_slugs = array_pop($slugs);
        
        $category = Category::findByUri($slugs);
        
        return self::findByCategoryIdAndSlug($category->id, $slug);
    }
    
    public function getColumns() {
        return array(
            'id', 'name', 'slug', 'description', 'category_id', 'brand_id', 'type',
            'created_on', 'updated_on', 'created_by_id', 'updated_by_id'
        );
    }
    
    public function keywords() {
        return strtolower(implode(', ', explode(' ', $this->name . ' ' . $this->brand->name . ' ' . $this->category->title)));
    }
    
    public function name() {
        if (isset($this->brand)) {
            return $this->brand->name . ' ' . $this->name;
        }
        else {
            return $this->name;
        }
    }
    
    public function url() {
        return URL_PUBLIC . $this->url . ($this->url != '' ? URL_SUFFIX: '');
    }
    
    public function price() {
        if (count($this->variant_prices) > 0) {
            return $this->variant_prices[0]->price;
        }
        
        return false;
    }
    
    public function setUrl() {
        if (isset($this->category)) {
            $this->url = trim($this->category->url . '/' . $this->slug, '/');
        }
    }
    
    public function stock() {
        $stock = NULL;
        
        foreach ($this->variants as $variant) {
            if (is_null($stock) || $stock > $variant->stock) {
                $stock = $variant->stock;
            }
        }
        
        return $stock;
    }
}