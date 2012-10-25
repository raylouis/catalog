<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nd.wortel@gmail.com>
 * 
 * @file        /models/Category.php
 * @date        13/09/2012
 */

use_helper('ActiveRecord');

class Category extends ActiveRecord {
    const TABLE_NAME = 'catalog_category';
    
    static $belongs_to = array(
        'parent' => array(
            'class_name' => 'Category',
            'foreign_key' => 'parent_id'
        )
    );
    static $has_many = array(
        'products' => array(
            'class_name' => 'Product',
            'foreign_key' => 'category_id',
            'order' => 'catalog_product.slug ASC'
        ),
        'category_filters' => array(
            'class_name' => 'CategoryFilter',
            'foreign_key' => 'category_id'
        ),
        'filters' => array(
            'class_name' => 'Attribute',
            'foreign_key' => 'category_id',
            'through' => 'category_filters'
        ),
        'subcategories' => array(
            'class_name' => 'Category',
            'foreign_key' => 'parent_id'
        )
    );
    
    public $id;
    public $title = '';
    public $slug = '';
    public $description = '';
    public $parent_id;
    public $position;
    
    public $parent;
    
    public $created_on;
    public $updated_on;
    public $created_by_id;
    public $updated_by_id;
    
    public $url = '';
    
    public function __construct() {
        if ($this->parent_id > 0) {
            $this->parent = self::findById($this->parent_id);
        }
        
        $this->setUrl();
    }
    
    public function beforeDelete() {
        $children = $this->children();
        
        foreach ($children as $child) {
            if (!$child->delete()) {
                return false;
            }
        }
        
        $products = Product::findByCategoryId($this->id);
        
        if (is_numeric($this->parent_id) && $this->parent_id > 0) {
            foreach ($products as $product) {
                $product->category_id = $this->parent_id;
                if (!$product->save()) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    public function beforeInsert() {
        $this->created_on       = date('Y-m-d H:i:s');
        $this->created_by_id    = AuthUser::getRecord()->id;

        return true;
    }
    
    public function beforeSave() {
        $this->slug             = Node::toSlug($this->title);
        
        $this->updated_on       = date('Y-m-d H:i:s');
        $this->updated_by_id    = AuthUser::getRecord()->id;
        
        return true;
    }
    
    public static function unlimitedChildren() {
        return self::find(array(
            
        ));
    }
    
    
    
    public function children() {
        return self::childrenOf($this->id);
    }
    
    public static function childrenOf($parent_id) {
        return self::findByParentId($parent_id);
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
            'where' => 'id > 1',
            'order' => 'title ASC'
        ));
    }
    
    public static function findById($id) {
        return self::find(array(
            'where' => array('id = ?', $id),
            'limit' => 1
        ));
    }
    
    public static function findByParentId($parent_id) {
        return self::find(array(
            'where' => array('parent_id = ?', $parent_id),
            'order' => 'position ASC'
        ));
    }
    
    public static function findBySlug($slug, &$parent = FALSE) {
        $parent_id = $parent ? $parent->id : 1;
        
        return self::find(array(
            'where' => array('slug = ? AND parent_id = ?', $slug, $parent_id),
            'limit' => 1,
            'include' => array(
                'filters' => array('filter_options'),
                'subcategories' => array('subcategories' => array('subcategories' => array('subcategories' => array('subcategories')))),
                'products' => array('brand', 'category', 'variant_prices')
            )
        ));
    }
    
    public static function findByUri($slugs) {
        $url = '';
        
        foreach($slugs as $slug) {
            $url = ltrim($url . '/' . $slug, '/');
            
            if ($category = self::findBySlug($slug, $parent)) {
                
            }
            else {
                break;
            }
            
            $parent = $category;
        }
        
        if (isset($category)) {
            return $category;
        }
        else {
            return false;
        }
        
        //die;
    }
    
    public function getColumns() {
        return array(
            'id', 'title', 'slug', 'description', 'parent_id', 'position',
            'created_on', 'updated_on', 'created_by_id', 'updated_by_id'
        );
    }
    
    public static function hasChildren($id) {
        return (boolean) self::countFrom('Category', 'parent_id = ?', array($id));
    }
    
    public function keywords() {
        return strtolower(implode(', ', explode(' ', $this->name . ' ' . $this->brand->name . ' ' . $this->category->title)));
    }
    
    public function url() {
        return URL_PUBLIC . $this->url . ($this->url != '' ? URL_SUFFIX: '');
    }
    
    protected function setUrl() {
        if ($this->parent_id > 0) {
            $this->url = trim($this->parent->url .'/'. $this->slug, '/');
        }
        else {
            $this->url = trim($this->slug, '/');
        }
    }
    
    public function parentIds() {
        if ($this->parent_id > 0) {
            $parents = $this->parent->parentIds();
        }
        else {
            $parents = array();
        }
        
        $array = array_merge(array($this->id), $parents);
        
        return $array;
    }
    
    
    
    public static function subcategoryIdsOf($category_id) {
        $array = array();
        $array[$category_id] = $category_id;
        
        $children = self::childrenOf($category_id);
        
        foreach ($children as $child) {
            $array = array_merge($array, self::subcategoryIdsOf($child->id));
        }
        
        return $array;
    }
    
    public function unlimitedFilters() {
        $category_ids = $this->parentIds();
        
        return Attribute::find(array(
            'select' => 'filter.*',
            'from' => 'catalog_attribute AS filter',
            'joins' => 'INNER JOIN catalog_category_filter AS category_filter ON category_filter.attribute_id = filter.id',
            'where' => 'category_filter.category_id IN (' . implode(',', $category_ids) . ')',
            'include' => array('filter_options')
        ));
    }
    
    public function unlimitedBrands() {
        $category_ids = self::subcategoryIdsOf($this->id);
        
        return Brand::find(array(
            'select' => 'brand.*, COUNT(brand.id) AS product_count',
            'from' => 'catalog_brand AS brand',
            'joins' => 'LEFT JOIN catalog_product AS prod ON prod.brand_id = brand.id',
            'where' => 'prod.category_id IN (' . implode(',', $category_ids) . ')',
            'group' => 'brand.name',
            'order' => 'brand.name ASC'
        ));
    }
    
    public function unlimitedProducts() {
        $category_ids = self::subcategoryIdsOf($this->id);
        
        return Product::find(array(
            'where' => 'category_id IN (' . implode(',', $category_ids) . ')',
            'order' => 'slug ASC',
            'include' => array('brand', 'category', 'variant_prices')
        ));
    }
}