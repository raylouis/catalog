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

/**
 * Class CatalogController
 */
class CatalogController extends PluginController {
    const PLUGIN_NAME = 'catalog';
    
    public function __construct() {
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/catalog/views/sidebar'));
    }
    
    private function _store($model, $action, $id = FALSE) {
        $models = array(
            'product' => 'products',
            'category' => 'categories',
            'brand' => 'brands',
            'attribute' => 'attributes',
            'vat' => 'vat_rates',
            'unit' => 'units'
        );
        
        if (!isset($models[$model])) {
            return false;
        }
        
        if ($action == 'edit' && !$id)
            throw new Exception('Trying to edit ' . $model . ' when $id is false.');
        
        // Add pre-save checks here
        $errors = false;
        
        use_helper('Validate');
        $data = $_POST[$model];
        Flash::set('post_data', (object) $data);
        
        if ($model == 'product') {
            $data['name'] = trim($data['name']);
            if (empty($data['name'])) {
                $errors[] = __('You have to specify a name!');
            }
            
            if (empty($data['category_id']) || $data['category_id'] < 1) {
                $errors[] = __('You have to choose a category!');
            }
            
            if ($action == 'add') {
                $obj = new Product($data);
                $obj->setFromData($data);
            }
            else {
                $obj = Product::findById($id);
                $obj->setFromData($data);
            }
        }
        elseif ($model == 'category') {
            $data['title'] = trim($data['title']);
            if (empty($data['title'])) {
                $errors[] = __('You have to specify a title!');
            }
            
            if ($action == 'add') {
                $obj = new Category($data);
                $obj->setFromData($data);
            }
            else {
                $obj = Category::findById($id);
                $obj->setFromData($data);
            }
        }
        elseif ($model == 'brand') {
            $data['name'] = trim($data['name']);
            $data['website'] = trim($data['website']);
            if (empty($data['name'])) {
                $errors[] = __('You have to specify a name!');
            }
            if (!empty($data['website']) && !Validate::url($data['website'])) {
                $errors[] = __('Website is not a valid URL!');
            }
            
            if ($action == 'add') {
                $obj = new Brand($data);
                $obj->setFromData($data);
            }
            else {
                $obj = Brand::findById($id);
                $obj->setFromData($data);
            }
            
            if (isset($_FILES['logo'])) {
                $uploaded_file = new UploadedFile($_FILES['logo']);

                if ($uploaded_file->error > 0) {
                    if ($uploaded_file->error == 4) {

                    }
                    else {
                        $errors[] = $uploaded_file->errorMessage();
                    }
                }
                else {
                    if (isset($obj->logo)) {
                        $image = $obj->logo;
                    }
                    else {
                        $image = new Image();
                    }
                    $image->title = $obj->name;
                    $image->save();
                    
                    $image->deleteFiles();
            
                    $image->createSlug();

                    $extension = pathinfo($uploaded_file->name, PATHINFO_EXTENSION);
                    $to = CMS_ROOT . DS . 'public' . DS . 'images' . DS . $image->slug . '.' . strtolower($extension);

                    $uploaded_file->moveTo($to);
                    
                    $obj->logo_id = $image->id;
                }
            }
        }
        elseif ($model == 'attribute') {
            $data['name'] = trim($data['name']);
            $data['attribute_type_id'] = (int) $data['attribute_type_id'];
            if (empty($data['name'])) {
                $errors[] = __('You have to specify a name!');
            }
            if ($data['attribute_type_id'] <= 0) {
                $errors[] = __('You have to choose an attribute type!');
            }
            
            if ($action == 'add') {
                $obj = new Attribute();
                $obj->setFromData($data);
                $obj->type = new AttributeType();
                $obj->type->units = array();
            }
            else {
                $obj = Attribute::findById($id);
                $obj->setFromData($data);
            }
        }
        elseif ($model == 'vat') {
            $data['name'] = trim($data['name']);
            $data['percentage'] = (float) $data['percentage'];
            if (empty($data['name'])) {
                $errors[] = __('You have to specify a name!');
            }
            
            if ($action == 'add') {
                $obj = new Vat();
                $obj->setFromData($data);
            }
            else {
                $obj = Vat::findById($id);
                $obj->setFromData($data);
            }
        }
        elseif ($model == 'unit') {
            $data['name'] = trim($data['name']);
            $data['abbreviation'] = trim($data['abbreviation']);
            $data['attribute_type_id'] = (int) $data['attribute_type_id'];
            if (empty($data['name'])) {
                $errors[] = __('You have to specify a name!');
            }
            if ($data['attribute_type_id'] <= 0) {
                $errors[] = __('You have to choose an attribute type!');
            }
            
            if ($action == 'add') {
                $obj = new AttributeUnit();
                $obj->setFromData($data);
            }
            else {
                $obj = AttributeUnit::findById($id);
                $obj->setFromData($data);
            }
        }
        
        if (false !== $errors) {
            Flash::setNow('error', implode('<br/>', $errors));
            
            if ($model == 'product') {
                $this->display('catalog/views/product/edit', array(
                    'action' => $action,
                    'product' => $obj,
                    'categories' => Category::findAll()
                ));
            }
            elseif ($model == 'category') {
                $this->display('catalog/views/category/edit', array(
                    'action' => $action,
                    'category' => $obj
                ));
            }
            elseif ($model == 'brand') {
                $this->display('catalog/views/brand/edit', array(
                    'action' => $action,
                    'brand' => $obj
                ));
            }
            elseif ($model == 'attribute') {
                $this->display('catalog/views/attribute/edit', array(
                    'action' => $action,
                    'attribute' => $obj,
                    'attribute_types' => AttributeType::findAll()
                ));
            }
            elseif ($model == 'vat') {
                $this->display('catalog/views/vat/edit', array(
                    'action' => $action,
                    'vat' => $obj
                ));
            }
            elseif ($model == 'unit') {
                $this->display('catalog/views/unit/edit', array(
                    'action' => $action,
                    'unit' => $obj,
                    'types' => AttributeType::findAll()
                ));
            }
        }
        
        if ($action == 'add') {
            Observer::notify($model . '_add_before_save', $obj);
        }
        else {
            Observer::notify($model . '_edit_before_save', $obj);
        }
        
        if ($obj->save()) {
            Flash::set('success', __(ucfirst($model) . ' has been saved!'));
        }
        else {
            Flash::set('error', __(ucfirst($model) . ' has not been saved!'));
            
            $url = 'plugin/catalog/' . $model . '/';
            $url .= ( $action == 'edit') ? 'edit/' . $id : 'add/';
            redirect(get_url($url));
        }
        
        if ($action == 'add') {
            Observer::notify($model . '_add_after_save', $obj);
        }
        else {
            Observer::notify($model . '_edit_after_save', $obj);
        }
        
        if (isset($_POST['commit'])) {
            redirect(get_url('plugin/catalog/' . $models[$model]));
        }
        else {
            redirect(get_url('plugin/catalog/' . $model . '/edit/' . $obj->id));
        }
    }
    
    public function ajax($action, $id) {
        if ($action == 'attribute_type_units') {
            if ($attribute_type = AttributeType::find(array(
                'where' => array('id = ?', $id),
                'limit' => 1,
                'include' => array('units')
            ))) {
            
                echo new View('../../plugins/catalog/views/ajax/attribute_type_units', array(
                    'attribute_type' => $attribute_type
                ));
                
            }
        }
        elseif ($action == 'product_attribute_selector') {
            if ($category = Category::find(array(
                'where' => array('id = ?', $id),
                'limit' => 1,
                'include' => array('attributes')
            ))) {
            
                echo new View('../../plugins/catalog/views/ajax/product_attribute_selector', array(
                    'category' => $category
                ));
                
            }
        }
    }
    
    public function attribute($action, $id = NULL) {
        if ($action == 'add') {
            if (get_request_method() == 'POST') {
                return $this->_store('attribute', 'add', $id);
            }
            
            $data = Flash::get('post_data');
            $attribute = new Attribute();
            if (!is_null($data)) $attribute->setFromData($data);
            $attribute->type = new AttributeType();
            $attribute->type->units = array();
            
            $this->display('catalog/views/attribute/edit', array(
                'action' => 'add',
                'attribute' => $attribute,
                'attribute_types' => AttributeType::findAll()
            ));
            
        }
        elseif ($action == 'delete') {
            if (!is_numeric($id)) {
                Flash::set('error', __('The attribute could not be found!'));
                redirect(get_url('plugin/catalog/attributes'));
            }
            
            if ($attribute = Attribute::findById($id)) {
                if ($attribute->delete()) {
                    Observer::notify('attribute_delete', $product);
                    Flash::set('success', __("Attribute ':name' has been deleted!", array(':name' => $attribute->name)));
                }
                else {
                    Flash::set('error', __("An error has occured, therefore ':name' could not be deleted!", array(':name' => $attribute->name)));
                }
            }
            else {
                Flash::set('error', __('The attribute could not be found!'));
            }

            redirect(get_url('plugin/catalog/attributes'));
        }
        elseif ($action == 'edit') {
            if (is_numeric($id)) {
                if (get_request_method() == 'POST') {
                    return $this->_store('attribute', 'edit', $id);
                }
                
                if ($attribute = Attribute::findById($id)) {
                    $this->display('catalog/views/attribute/edit', array(
                        'action' => 'edit',
                        'attribute' => $attribute,
                        'attribute_types' => AttributeType::findAll()
                    ));
                }
                else {
                    Flash::set('error', __('The attribute could not be found!'));
                    redirect(get_url('plugin/catalog/attributes'));
                }
                
            }
            else {
                Flash::set('error', __('The attribute could not be found!'));
                redirect(get_url('plugin/catalog/attributes'));
            }
        }
        else {
            $this->attributes();
        }
    }
    
    public function attributes($order_by = NULL, $order_direction = 'asc', $page = 1) {
        $allowed_columns = array(
            'id' => 'id',
            'name' => 'name'
        );
        
        if (!isset($allowed_columns[$order_by])) {
            $order_by = 'id';
        }
        
        $order_sql = $allowed_columns[$order_by];
        
        if ($order_direction != 'desc') {
            $order_direction = 'asc';
        }
        
        $attributes = Attribute::find(array(
            'order' => $order_sql . ' ' . strtoupper($order_direction),
            'include' => array('type', 'default_unit')
        ));
        
        $this->display('catalog/views/attribute/index', array(
            'attributes' => $attributes,
            'order_by' => $order_by,
            'order_direction' => $order_direction
        ));
    }
    
    public function brand($action, $id = NULL) {
        if ($action == 'add') {
            if (get_request_method() == 'POST') {
                return $this->_store('brand', 'add', $id);
            }
            
            $data = Flash::get('post_data');
            $brand = new Brand();
            if (!is_null($data)) $brand->setFromData($data);

            $this->display('catalog/views/brand/edit', array(
                'action' => 'add',
                'brand' => $brand
            ));
            
        }
        elseif ($action == 'delete') {
            if (!is_numeric($id)) {
                Flash::set('error', __('The brand could not be found!'));
                redirect(get_url('plugin/catalog/brands'));
            }
            
            if ($brand = Brand::findById($id)) {
                if ($brand->delete()) {
                    Observer::notify('brand_delete', $product);
                    Flash::set('success', __("Brand ':name' has been deleted!", array(':name' => $brand->name)));
                }
                else {
                    Flash::set('error', __("An error has occured, therefore ':name' could not be deleted!", array(':name' => $brand->name)));
                }
            }
            else {
                Flash::set('error', __('The brand could not be found!'));
            }

            redirect(get_url('plugin/catalog/brands'));
        }
        elseif ($action == 'edit') {
            if (is_numeric($id)) {
                if (get_request_method() == 'POST') {
                    return $this->_store('brand', 'edit', $id);
                }
                
                if ($brand = Brand::findById($id)) {
                    $this->display('catalog/views/brand/edit', array(
                        'action' => 'edit',
                        'brand' => $brand
                    ));
                }
                else {
                    Flash::set('error', __('The brand could not be found!'));
                    redirect(get_url('plugin/catalog/brands'));
                }
                
            }
            else {
                Flash::set('error', __('The brand could not be found!'));
                redirect(get_url('plugin/catalog/brands'));
            }
        }
        else {
            $this->brands();
        }
    }
    
    public function brands($order_by = NULL, $order_direction = 'asc', $page = 1) {
        $allowed_columns = array(
            'id' => 'id',
            'name' => 'name',
            'website' => 'CASE WHEN website IS NULL THEN 1 ELSE 0 END, website'
        );
        
        if (!isset($allowed_columns[$order_by])) {
            $order_by = 'name';
        }
        
        $order_sql = $allowed_columns[$order_by];
        
        if ($order_direction != 'desc') {
            $order_direction = 'asc';
        }
        
        if (isset($_POST['search'])) {
            $search_string = $_POST['search'];
            $search_string = '%' . $search_string . '%';
            
            $brands = Brand::find(array(
                'select' => 'catalog_brand.*',
                'where' => array('name LIKE ?', $search_string),
                'order' => $order_sql . ' ' . strtoupper($order_direction)
            ));
        }
        else {
            $brands = Brand::find(array(
                'select' => 'catalog_brand.*',
                'order' => $order_sql . ' ' . strtoupper($order_direction)
            ));
        }
        
        $this->display('catalog/views/brand/index', array(
            'brands' => $brands,
            'order_by' => $order_by,
            'order_direction' => $order_direction
        ));
    }
    
    public function categories() {
        $root = Category::find(array(
            'where' => 'parent_id IS NULL',
            'order' => 'id ASC',    
            'limit' => 1
        ));
        
        $this->display('catalog/views/category/index', array(
            'root' => $root,
            'content_children' => $this->categoryChildren(1, 0, true)
        ));
    }
    
    public function category($action, $id = NULL) {
        if ($action == 'add') {
            if (get_request_method() == 'POST') {
                return $this->_store('category', 'add', $id);
            }
            
            if (!is_numeric($id)) {
                redirect(get_url('plugin/catalog/categories'));
            }
            
            $data = Flash::get('post_data');
            $category = new Category();
            $category->attributes = array();
            if (!is_null($data)) $category->setFromData($data);
            
            $category->parent_id = (int) $id;
            $category->parent = Category::findById($category->parent_id);

            $this->display('catalog/views/category/edit', array(
                'action' => 'add',
                'category' => $category,
                'attributes' => Attribute::findAll()
            ));
            
        }
        elseif ($action == 'delete') {
            if (!is_numeric($id)) {
                Flash::set('error', __('The category could not be found!'));
                redirect(get_url('plugin/catalog/categories'));
            }
            
            if ($category = Category::findById($id)) {
                if ($category->delete()) {
                    Observer::notify('category_delete', $product);
                    Flash::set('success', __("Category ':title' has been deleted!", array(':title' => $category->title)));
                }
                else {
                    Flash::set('error', __("An error has occured, therefore ':title' could not be deleted!", array(':title' => $category->title)));
                }
            }
            else {
                Flash::set('error', __('The category could not be found!'));
            }

            redirect(get_url('plugin/catalog/categories'));
        }
        elseif ($action == 'edit') {
            if (is_numeric($id)) {
                if (get_request_method() == 'POST') {
                    return $this->_store('category', 'edit', $id);
                }
                
                if ($category = Category::findById($id)) {
                    $this->display('catalog/views/category/edit', array(
                        'action' => 'edit',
                        'category' => $category,
                        'attributes' => Attribute::findAll()
                    ));
                }
                else {
                    Flash::set('error', __('The category could not be found!'));
                    redirect(get_url('plugin/catalog/categories'));
                }
                
            }
            else {
                Flash::set('error', __('The category could not be found!'));
                redirect(get_url('plugin/catalog/categories'));
            }
        }
        else {
            $this->categories();
        }
    }
    
    public function categoryChildren($parent_id, $level, $return=false) {
        $expanded_rows = isset($_COOKIE['catalog_category_expanded_rows']) ? explode(',', $_COOKIE['catalog_category_expanded_rows']) : array();

        // get all children of the page (parent_id)
        $children = Category::childrenOf($parent_id);

        foreach ($children as $index => $child) {
            $children[$index]->has_children = Category::hasChildren($child->id);
            $children[$index]->is_expanded = in_array($child->id, $expanded_rows);

            if ($children[$index]->has_children && $children[$index]->is_expanded) {
                $children[$index]->children_rows = $this->categoryChildren($child->id, $level + 1, true);
            }
        }

        $content = new View('../../plugins/catalog/views/category/children', array(
            'children' => $children,
            'level' => $level + 1,
            'settings' => Plugin::getAllSettings(self::PLUGIN_NAME)
        ));

        if ($return) {
            return $content;
        }
        else {
            echo $content;
        }
    }
    
    public function documentation() {
        $locale = strtolower(i18n::getLocale());
        $local_doc = $locale . '-documentation';
        $path = PLUGINS_ROOT . '/catalog/views/documentation/';

        if (!file_exists( $path . $local_doc . '.php' ))
            $local_doc = 'en-documentation';

        $this->display('catalog/views/documentation/' . $local_doc );
    }
    
    public function export($model = null, $format = null) {
        if (!is_null($model)) {
            $data = array();
            
            if ($model == 'product') {
                $data[] = array(
                    __('SKU'),
                    __('Price'),
                    __('Brand'),
                    __('Name'),
                    __('Category'),
                    __('URL')
                );
                
                $products = Product::find(array(
                    'select' => '*, prod_var.name AS name',
                    'from' => 'catalog_product AS prod',
                    'joins' => 'LEFT JOIN catalog_product_variant AS prod_var ON prod_var.product_id = prod.id',
                    'include' => array('category', 'brand')
                    
                ));
                
                foreach ($products as $product) {
                    if (is_object($product->brand)) {
                        $brand_name = $product->brand->name;
                    }
                    else {
                        $brand_name = '';
                    }
                    
                    $data[] = array(
                        $product->sku,
                        number_format($product->price, 2, ',', ''),
                        $brand_name,
                        $product->name,
                        $product->category->title,
                        $product->url()
                    );
                }
            }
            
            if ($format == 'csv') {
                echo new View('../../plugins/catalog/views/export/csv', array(
                    'model' => $model,
                    'data' => $data
                ));
            }
            else {
                $this->display('catalog/views/export/index', array(
                    'model' => $model
                ));
            }
        }
        else {
            redirect(get_url('plugin/catalog'));
        }
    }
    
    public function frontend() {
        $uri = func_get_args();
        
        if ($category = Category::findByUri($uri)) {
            $page = new CategoryPage($category);
        }
        elseif($product = Product::findByUri($uri)) {
            $page = new ProductPage($product);
        }
        else {
            page_not_found();
        }
        
        $page->_executeLayout();
    }
    
    public function frontendBrand($slug) {
        if ($brand = Brand::findBySlug($slug)) {
            $page = new BrandPage($brand);
        }
        else {
            page_not_found();
        }
        
        $page->_executeLayout();
    }
    
    public function frontendBrandList() {
        if ($brands = Brand::findAll()) {
            $page = new BrandListPage($brands);
        }
        else {
            page_not_found();
        }
        
        $page->_executeLayout();
    }
    
    public function index() {
        $this->products();
    }
    
    /*
     * Adds, edits or deletes a product.
     * 
     * @param string $action The action (add/delete/edit)
     * @param int $id The id of the product
     */
    public function product($action, $id = NULL) {
        if ($action == 'add') {
            if (get_request_method() == 'POST') {
                return $this->_store('product', 'add', $id);
            }
            
            $data = Flash::get('post_data');
            $product = new Product();
            if (!is_null($data)) $product->setFromData($data);

            $this->display('catalog/views/product/edit', array(
                'action' => 'add',
                'product' => $product,
                'brands' => Brand::findAll(),
                'categories' => Category::findAll(),
                'vats' => Vat::findAll()
            ));
            
        }
        elseif ($action == 'delete') {
            if (!is_numeric($id)) {
                Flash::set('error', __('The product could not be found!'));
                redirect(get_url('plugin/catalog/products'));
            }
            
            if ($product = Product::findById($id)) {
                if ($product->delete()) {
                    Observer::notify('product_delete', $product);
                    Flash::set('success', __("The product ':name' has been deleted!", array(':name' => $product->name)));
                }
                else {
                    Flash::set('error', __("An error has occured, therefore product ':name' could not be deleted!", array(':name' => $product->name)));
                }
            }
            else {
                Flash::set('error', __('The product could not be found!'));
            }

            redirect(get_url('plugin/catalog/products'));
        }
        elseif ($action == 'edit') {
            if (is_numeric($id)) {
                if (get_request_method() == 'POST') {
                    return $this->_store('product', 'edit', $id);
                }
                
                if ($product = Product::findById($id)) {
                    $this->display('catalog/views/product/edit', array(
                        'action' => 'edit',
                        'product' => $product,
                        'brands' => Brand::findAll(),
                        'categories' => Category::findAll(),
                        'variant' => new ProductVariant(),
                        'vats' => Vat::findAll()
                    ));
                }
                else {
                    Flash::set('error', __('The product could not be found!'));
                    redirect(get_url('plugin/catalog/products'));
                }
                
            }
            else {
                Flash::set('error', __('The product could not be found!'));
                redirect(get_url('plugin/catalog/products'));
            }
        }
        else {
            $this->products();
        }
    }
    
    /*
     * Displays a list of products.
     * 
     * @param string $order_by
     * @param string $order_direction
     * @param int $page
     * @return View
     */
    public function products($order_by = NULL, $order_direction = 'asc', $page = 1) {
        $allowed_columns = array(
            'id' => 'product.id',
            'name' => 'product.name',
            'brand' => 'CASE WHEN brand.id IS NULL THEN 1 ELSE 0 END, brand.name',
            'category' => 'category.title',
            'variants' => 'variant_count',
            'price' => 'CASE WHEN variant.id IS NULL THEN 1 ELSE 0 END, min_price',
            'stock' => 'CASE WHEN variant.id IS NULL THEN 1 ELSE 0 END, total_stock'
        );
        
        if (!isset($allowed_columns[$order_by])) {
            $order_by = 'id';
        }
        
        $order_sql = $allowed_columns[$order_by];
        
        if ($order_direction != 'desc') {
            $order_direction = 'asc';
        }
        
        if (isset($_POST['search'])) {
            $q = $_POST['search'];
            $q = '%' . $q . '%';
            
            $products = Product::find(array(
                'select' => '
                    product.*, COUNT(variant.id) AS variant_count,
                    SUM(variant.stock) AS total_stock,
                    MIN(variant.stock) AS min_stock,
                    MIN(variant.price) AS min_price',
                'from' => 'catalog_product AS product',
                'joins' => '
                    INNER JOIN catalog_category AS category ON category.id = product.category_id
                    LEFT JOIN catalog_brand AS brand ON brand.id = product.brand_id
                    LEFT JOIN catalog_product_variant AS variant ON variant.product_id = product.id
                    ',
                'where' => array('product.name LIKE ?
                        OR product.description LIKE ?
                        OR category.title LIKE ?
                        OR brand.name LIKE ?
                        OR variant.sku LIKE ?
                        OR variant.name LIKE ?', $q, $q, $q, $q, $q, $q),
                'group' => 'product.id',
                'order' => $order_sql . ' ' . strtoupper($order_direction),
                'include' => array(
                    'brand',
                    'category',
                    'variants'
                )
            ));
        }
        else {
            
            $products = Product::find(array(
                'select' => '
                    product.*, COUNT(variant.id) AS variant_count,
                    SUM(variant.stock) AS total_stock,
                    MIN(variant.stock) AS min_stock,
                    MIN(variant.price) AS min_price',
                'from' => 'catalog_product AS product',
                'joins' => '
                    INNER JOIN catalog_category AS category ON category.id = product.category_id
                    LEFT JOIN catalog_brand AS brand ON brand.id = product.brand_id
                    LEFT JOIN catalog_product_variant AS variant ON variant.product_id = product.id
                    ',
                'group' => 'product.id',
                'order' => $order_sql . ' ' . strtoupper($order_direction),
                'include' => array(
                    'brand',
                    'category',
                    'variants'
                )
            ));
            
        }
        
        $this->display('catalog/views/product/index', array(
            'products' => $products,
            'order_by' => $order_by,
            'order_direction' => $order_direction
        ));
    }
    
    public function reorder($model = NULL) {
        if ($model == 'category') {
            if ($categories = $_POST['page']) {
                $i = 1;
                foreach ($categories as $category_id => $parent_id) {
                    $category = Category::findById($category_id);
                    $category->position = (int) $i;
                    $category->parent_id = (int) $parent_id;
                    $category->save();
                    $i++;
                }
            }
        }
    }
    
    public function settings($d = '') {
        if ($d == 'general') {
            if (isset($_POST['save']) && $_POST['save'] == __('Save Settings')) {
                $settings = $_POST['setting'];
                $settings['brands_slug'] = Node::toSlug($settings['brands_title']);

                Plugin::setAllSettings($settings, self::PLUGIN_NAME);
                Flash::setNow('success', __('Settings have been saved!'));
            }

            $this->display('catalog/views/settings/general', array(
                'settings' => Plugin::getAllSettings(self::PLUGIN_NAME),
                'layouts'  => Layout::findAll(array())
            ));
        }
        else {
            $this->display('catalog/views/settings/index');
        }
    }
    
    public function unit($action, $id = NULL) {
        if ($action == 'add') {
            if (get_request_method() == 'POST') {
                return $this->_store('unit', 'add', $id);
            }
            
            $data = Flash::get('post_data');
            $unit = new AttributeUnit();
            if (!is_null($data)) $unit->setFromData($data);

            $this->display('catalog/views/unit/edit', array(
                'action' => 'add',
                'unit' => $unit,
                'types' => AttributeType::findAll()
            ));
            
        }
        elseif ($action == 'delete') {
            if (!is_numeric($id)) {
                Flash::set('error', __('The unit could not be found!'));
                redirect(get_url('plugin/catalog/units'));
            }
            
            if ($unit = AttributeUnit::findById($id)) {
                if ($unit->delete()) {
                    Observer::notify('unit_delete', $product);
                    Flash::set('success', __("Unit ':name' has been deleted!", array(':name' => $unit->name)));
                }
                else {
                    Flash::set('error', __("An error has occured, therefore ':name' could not be deleted!", array(':name' => $unit->name)));
                }
            }
            else {
                Flash::set('error', __('The unit could not be found!'));
            }

            redirect(get_url('plugin/catalog/units'));
        }
        elseif ($action == 'edit') {
            if (is_numeric($id)) {
                if (get_request_method() == 'POST') {
                    return $this->_store('unit', 'edit', $id);
                }
                
                if ($unit = AttributeUnit::findById($id)) {
                    $this->display('catalog/views/unit/edit', array(
                        'action' => 'edit',
                        'unit' => $unit,
                        'types' => AttributeType::findAll()
                    ));
                }
                else {
                    Flash::set('error', __('The unit could not be found!'));
                    redirect(get_url('plugin/catalog/units'));
                }
                
            }
            else {
                Flash::set('error', __('The unit could not be found!'));
                redirect(get_url('plugin/catalog/units'));
            }
        }
        else {
            $this->units();
        }
    }
    
    public function units() {
        $units = AttributeUnit::find(array(
            'order' => 'attribute_type_id ASC, multiplier ASC',
            'include' => array('type', 'parent')
        ));
        
        $this->display('catalog/views/unit/index', array(
            'units' => $units
        ));
    }
    
    public function vat($action, $id = NULL) {
        if ($action == 'add') {
            if (get_request_method() == 'POST') {
                return $this->_store('vat', 'add', $id);
            }
            
            $data = Flash::get('post_data');
            $vat = new Vat();
            if (!is_null($data)) $vat->setFromData($data);

            $this->display('catalog/views/vat/edit', array(
                'action' => 'add',
                'vat' => $vat
            ));
            
        }
        elseif ($action == 'delete') {
            if (!is_numeric($id)) {
                Flash::set('error', __('The VAT rate could not be found!'));
                redirect(get_url('plugin/catalog/vat_rates'));
            }
            
            if ($vat = Vat::findById($id)) {
                if ($vat->delete()) {
                    Observer::notify('vat_delete', $vat);
                    Flash::set('success', __("VAT rate ':name' has been deleted!", array(':name' => $vat->name)));
                }
                else {
                    Flash::set('error', __("An error has occured, therefore ':name' could not be deleted!", array(':name' => $vat->name)));
                }
            }
            else {
                Flash::set('error', __('The VAT rate could not be found!'));
            }

            redirect(get_url('plugin/catalog/vat_rates'));
        }
        elseif ($action == 'edit') {
            if (is_numeric($id)) {
                if (get_request_method() == 'POST') {
                    return $this->_store('vat', 'edit', $id);
                }
                
                if ($vat = Vat::findById($id)) {
                    $this->display('catalog/views/vat/edit', array(
                        'action' => 'edit',
                        'vat' => $vat
                    ));
                }
                else {
                    Flash::set('error', __('The VAT rate could not be found!'));
                    redirect(get_url('plugin/catalog/vat_rates'));
                }
                
            }
            else {
                Flash::set('error', __('The VAT rate could not be found!'));
                redirect(get_url('plugin/catalog/vat_rates'));
            }
        }
        else {
            $this->vat_rates();
        }
    }
    
    public function vat_rates() {
        
        $vat_rates = Vat::find(array(
            'order' => 'percentage DESC'
        ));
        
        $this->display('catalog/views/vat/index', array(
            'vat_rates' => $vat_rates
        ));
    }
}