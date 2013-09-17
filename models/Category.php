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
        'category_attributes' => array(
            'class_name' => 'CategoryAttribute',
            'foreign_key' => 'category_id'
        ),
        'attributes' => array(
            'class_name' => 'Attribute',
            'foreign_key' => 'category_id',
            'through' => 'category_attributes'
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
    
    /**
     * Dirty solution
     * 
     * @todo improve
     */
    public function afterSave() {
        if (isset($_POST['attribute_ids'])) {
            $old_attributes = CategoryAttribute::findByCategoryId($this->id);
            $old_ids = array();
            
            foreach ($old_attributes as $cat_attr) {
                $old_ids[] = $cat_attr->attribute_id;
            }
            
            $new_ids = $_POST['attribute_ids'];
            
            $delete_ids = array_diff($old_ids, $new_ids);
            $insert_ids = array_diff($new_ids, $old_ids);
            
            foreach ($delete_ids as $delete_id) {
                if ($category_attribute = CategoryAttribute::findByCategoryIdAndAttributeId($this->id, $delete_id)) {
                    //$category_attribute->delete();
                }
            }
            
            foreach ($insert_ids as $id) {
                $category_attribute = new CategoryAttribute();
                $category_attribute->category_id = $this->id;
                $category_attribute->attribute_id = $id;
                $category_attribute->save();
            }
        }
        return true;
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
            'limit' => 1,
            'include' => array('attributes')
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
        if ($this->parent_id > 1) {
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
    
    public function unlimitedAttributes() {
        $category_ids = $this->parentIds();
        
        return Attribute::find(array(
            'select' => 'attribute.*',
            'from' => 'catalog_attribute AS attribute',
            'joins' => 'INNER JOIN catalog_category_attribute AS category_attribute ON category_attribute.attribute_id = attribute.id',
            'where' => 'category_attribute.category_id IN (' . implode(',', $category_ids) . ')',
            'include' => array('type' => array('units'))
        ));
    }
    
    public function unlimitedFilters() {
        $category_ids = $this->parentIds();
        
        return Attribute::find(array(
            'select' => 'filter.*',
            'from' => 'catalog_attribute AS filter',
            'joins' => 'INNER JOIN catalog_category_attribute AS category_attribute ON category_attribute.attribute_id = filter.id',
            'where' => 'category_attribute.category_id IN (' . implode(',', $category_ids) . ') AND category_attribute.is_filter = 1',
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