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
 * @version     0.2.1
 */

class Product extends CatalogNode
{
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
        ),
        'images' => array(
            'class_name' => 'ProductImage',
            'foreign_key' => 'product_id'
        )
    );
    
    public $id;
    public $name = '';
    public $slug = '';
    public $description = '';
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

    public $images = array();

    public function breadcrumb()
    {
        return $this->name();
    }

    public function children()
    {
        return array();
    }

    public function content($part = 'body', $inherit = false)
    {
        if ($part == 'body') {
            $this->includeSnippet('product');
        }
    }

    public function description()
    {
        return $this->description;
    }

    public function hasContent($part, $inherit = false)
    {
        if ($part == 'body') {
            return true;
        }
    }
    
    public function keywords()
    {
        return strtolower(implode(', ', explode(' ', $this->name . ' ' . $this->brand->name . ' ' . $this->category->title)));
    }

    public function parent($level = null)
    {
        return $this->category();
    }

    public function slug()
    {
        return $this->slug;
    }

    public function title()
    {
        return $this->name();
    }

    public function brand()
    {
        if (!isset($this->brand) && $this->brand_id > 0) {
            $this->brand = Brand::findById($this->brand_id);
        }

        if ($this->brand instanceOf Brand) {
            return $this->brand;
        } else {
            return false;
        }
    }

    public function category()
    {
        if (!isset($this->category) && $this->category_id > 1) {
            $this->category = Category::findById($this->category_id);
        }

        if ($this->category instanceOf Category) {
            return $this->category;
        } else {
            return false;
        }
    }
    
    public function afterSave()
    {
        $old_variants = $this->variants;
        
        foreach ($old_variants as $old_variant) {
            $not_in = true;

            if (isset($_POST['variants'])) {
                foreach ($_POST['variants'] as $key => $variant) {
                    if ($old_variant->id == $variant['id']) {
                        $not_in = false;

                        if (isset($_POST['product']['attributes']) && isset($variant['attributes'])) {
                            $variant['attributes'] = $variant['attributes'] + $_POST['product']['attributes'];
                        } elseif (isset($_POST['product']['attributes'])) {
                            $variant['attributes'] = $_POST['product']['attributes'];
                        }

                        $old_variant->setFromData($variant);
                        $old_variant->name = $this->name();
                        $old_variant->save();

                        unset($_POST['variants'][$key]);

                        break;
                    }
                }
            }

            if ($not_in) {
                if (!$old_variant->delete()) {
                    print_r($old_variant);
                    die;
                }
            }
        }

        foreach ($_POST['variants'] as $variant) {
            if (isset($_POST['product']['attributes']) && isset($variant['attributes'])) {
                $variant['attributes'] = $variant['attributes'] + $_POST['product']['attributes'];
            } elseif (isset($_POST['product']['attributes'])) {
                $variant['attributes'] = $_POST['product']['attributes'];
            }

            $product_variant = new ProductVariant();
            $product_variant->setFromData($variant);
            $product_variant->name = $this->name();
            $product_variant->product_id = $this->id;

            $product_variant->save();
        }
        
        return true;
    }
    
    public function beforeDelete()
    {
        if (!ProductVariant::deleteByProductId($this->id)) {
            return false;
        }
        
        return false;
    }
    
    public function beforeInsert()
    {
        $this->created_on       = date('Y-m-d H:i:s');
        $this->created_by_id    = AuthUser::getRecord()->id;

        return true;
    }
    
    public function beforeSave()
    {
        $this->brand            = Brand::findById($this->brand_id);
        $this->slug             = Node::toSlug($this->name());
        
        $this->updated_on       = date('Y-m-d H:i:s');
        $this->updated_by_id    = AuthUser::getRecord()->id;
        
        return true;
    }
    
    public function date($format='%a, %e %b %Y', $which_one='created')
    {
        if ($which_one == 'update' || $which_one == 'updated') {
            return strftime($format, strtotime($this->updated_on));
        } else {
            return strftime($format, strtotime($this->created_on));
        }
    }
    
    public static function findAll()
    {
        return self::find(array(
            'order' => 'id ASC',
            'include' => array('brand', 'category', 'variants')
        ));
    }
    
    public static function findByCategoryId($category_id)
    {
        return self::find(array(
            'where' => array('category_id = :category_id', ':category_id' => $category_id),
            'order' => 'slug ASC',
            'include' => array('brand')
        ));
    }
    
    public static function findByCategoryIdAndSlug($category_id, $slug)
    {
        return self::find(array(
            'where' => array(
                'category_id = :category_id AND slug = :slug',
                ':category_id' => $category_id,
                ':slug' => $slug
            ),
            'limit' => 1,
            'include' => array(
                'brand' => array('logo'),
                'category',
                'images' => array('file'),
                //'product_attributes' => array('attribute'),
                //'product_variable_attributes' => array('attribute', 'options'),
                'variants' => array('vat'),
                'variable_attributes'
            )
        ));
    }
    
    public static function findBySubcategories($category_id)
    {
        $category_ids = CatalogCategory::subcategories($category_id);
        $category_ids = implode(',', $category_ids);
        
        return self::find(array(
            'where' => "category_id IN ($category_ids)",
            'order' => 'slug ASC',
            'include' => array(
                'brand',
                'category',
                'images' => array('file'),
                'colors'
            )
        ));
    }
    
    public static function findByBrandId($brand_id)
    {
        return self::find(array(
            'where' => array('brand_id = :brand_id', ':brand_id' => $brand_id),
            'order' => 'name ASC',
            'include' => array(
                'brand',
                'category',
                'colors',
                'images' => array('file')
            )
        ));
    }
    
    public static function findById($id)
    {
        return self::find(array(
            'where' => array('id = :id', ':id' => $id),
            'limit' => 1,
            'include' => array(
                'category',
                'variants',
                'images' => array('file')
            )
        ));
    }
    
    public static function findBySlug($slug)
    {
        return self::find(array(
            'where' => array('slug = :slug', ':slug' => $slug),
            'limit' => 1
        ));
    }
    
    public static function findByUri($slugs)
    {
        $slug = end($slugs);
        $category_slugs = array_pop($slugs);
        
        $category = Category::findByUri($slugs);
        
        return self::findByCategoryIdAndSlug($category->id, $slug);
    }
    
    public function getColumns()
    {
        return array(
            'id',
            'name',
            'slug',
            'description',
            'category_id',
            'brand_id',
            'created_on',
            'updated_on',
            'created_by_id',
            'updated_by_id'
        );
    }
    
    public function name()
    {
        if ($this->brand() && $this->brand()->name != $this->name) {
            return $this->brand()->name . ' ' . $this->name;
        } else {
            return $this->name;
        }
    }
    
    public function price()
    {
        if (count($this->variant_prices) > 0) {
            return $this->variant_prices[0]->price;
        }
        
        return false;
    }
    
    public function stock()
    {
        $stock = NULL;
        
        foreach ($this->variants as $variant) {
            if (is_null($stock) || $stock > $variant->stock) {
                $stock = $variant->stock;
            }
        }
        
        return $stock;
    }

    public function productAttributes($with_value_only = false)
    {
        if ($this->category instanceOf Category) {
            $category_ids = $this->category->parentIds();
        } else {
            return array();
        }

        if ($with_value_only) {
            return Attribute::find(array(
                'select' => 'DISTINCT attribute.*',
                'from' => 'catalog_attribute AS attribute',
                'joins' => 'INNER JOIN catalog_category_attribute AS category_attribute ON category_attribute.attribute_id = attribute.id
                            INNER JOIN catalog_product_variant_value AS product_variant_value ON product_variant_value.attribute_id = attribute.id
                            INNER JOIN catalog_product_variant AS product_variant ON product_variant.id = product_variant_value.product_variant_id AND product_variant.product_id = ' . $this->id,
                'where' => array('attribute.id NOT IN (
                                SELECT attribute.id
                                FROM catalog_attribute AS attribute
                                INNER JOIN catalog_product_variant_value AS product_variant_value ON product_variant_value.attribute_id = attribute.id
                                INNER JOIN catalog_product_variant AS product_variant ON product_variant.id = product_variant_value.product_variant_id
                                INNER JOIN catalog_product AS product ON product.id = product_variant.product_id
                                WHERE product_id = :product_id
                                GROUP BY attribute.id
                                HAVING COUNT(DISTINCT flat_value) > 1
                            )
                            AND category_attribute.category_id IN (' . implode(',', $category_ids) . ')', ':product_id' => $this->id),
                'group' => 'attribute.id',
                'include' => array('type' => array('units'))
            ));
        } else {
            return Attribute::find(array(
                'select' => 'attribute.*',
                'from' => 'catalog_attribute AS attribute',
                'joins' => 'INNER JOIN catalog_category_attribute AS category_attribute ON category_attribute.attribute_id = attribute.id',
                'where' => array('attribute.id NOT IN (
                                SELECT attribute.id
                                FROM catalog_attribute AS attribute
                                INNER JOIN catalog_product_variant_value AS product_variant_value ON product_variant_value.attribute_id = attribute.id
                                INNER JOIN catalog_product_variant AS product_variant ON product_variant.id = product_variant_value.product_variant_id
                                INNER JOIN catalog_product AS product ON product.id = product_variant.product_id
                                WHERE product_id = :product_id
                                GROUP BY attribute.id
                                HAVING COUNT(DISTINCT flat_value) > 1
                            )
                            AND category_attribute.category_id IN (' . implode(',', $category_ids) . ')', ':product_id' => $this->id),
                'include' => array('type' => array('units'))
            ));
        }
    }

    public function variantAttributes()
    {
        return Attribute::find(array(
            'select' => 'attribute.*',
            'from' => 'catalog_attribute AS attribute',
            'joins' => 'INNER JOIN catalog_product_variant_value AS product_variant_value ON product_variant_value.attribute_id = attribute.id
                        INNER JOIN catalog_product_variant AS product_variant ON product_variant.id = product_variant_value.product_variant_id
                        INNER JOIN catalog_product AS product ON product.id = product_variant.product_id',
            'where' => array('product_id = :product_id', ':product_id' => $this->id),
            'group' => 'attribute.id',
            'having' => 'COUNT(DISTINCT flat_value) > 1',
            'include' => array('type' => array('units'))
        ));
    }

    /*
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
    */
}
