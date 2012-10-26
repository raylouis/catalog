<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nd.wortel@gmail.com>
 * 
 * @file        /CatalogController.php
 * @date        13/09/2012
 */

/**
 * Class CatalogController
 */
class CatalogController extends PluginController {
    const PLUGIN_NAME = 'catalog';
    
    public function __construct() {
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/catalog/views/backend/sidebar'));
    }
    
    private function _store($model, $action, $id = FALSE) {
        $models = array(
            'product' => 'products',
            'category' => 'categories',
            'brand' => 'brands',
            'attribute' => 'attributes'
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
            if (empty($data['name'])) {
                $errors[] = __('You have to specify a name!');
            }
            
            if ($action == 'add') {
                $obj = new Brand($data);
                $obj->setFromData($data);
            }
            else {
                $obj = Brand::findById($id);
                $obj->setFromData($data);
            }
        }
        elseif ($model == 'attribute') {
            $data['name'] = trim($data['name']);
            if (empty($data['name'])) {
                $errors[] = __('You have to specify a name!');
            }
            
            if ($action == 'add') {
                $obj = new Attribute();
                $obj->setFromData($data);
            }
            else {
                $obj = Attribute::findById($id);
                $obj->setFromData($data);
            }
        }
        
        if (false !== $errors) {
            Flash::setNow('error', implode('<br/>', $errors));
            
            if ($model == 'product') {
                $this->display('catalog/views/backend/editProduct', array(
                    'action' => $action,
                    'product' => $obj,
                    'categories' => Category::findAll()
                ));
            }
            elseif ($model == 'category') {
                $this->display('catalog/views/backend/editCategory', array(
                    'action' => $action,
                    'category' => $obj
                ));
            }
            elseif ($model == 'brand') {
                $this->display('catalog/views/backend/editBrand', array(
                    'action' => $action,
                    'product' => $obj
                ));
            }
            elseif ($model == 'attribute') {
                $this->display('catalog/views/backend/editAttribute', array(
                    'action' => $action,
                    'attribute' => $obj
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
            
            print_r($obj);
            die;
            
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
    
    public function attribute($action, $id = NULL) {
        if ($action == 'add') {
            if (get_request_method() == 'POST') {
                return $this->_store('attribute', 'add', $id);
            }
            
            $data = Flash::get('post_data');
            $attribute = new Attribute($data);

            $this->display('catalog/views/backend/editAttribute', array(
                'action' => 'add',
                'attribute' => $attribute
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
                    $this->display('catalog/views/backend/editAttribute', array(
                        'action' => 'edit',
                        'attribute' => $attribute
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
            'name' => 'name',
            'type' => 'type',
            'unit' => 'unit'
        );
        
        if (!isset($allowed_columns[$order_by])) {
            $order_by = 'id';
        }
        
        $order_sql = $allowed_columns[$order_by];
        
        if ($order_direction != 'desc') {
            $order_direction = 'asc';
        }
        
        $attributes = Attribute::find(array(
            'order' => $order_sql . ' ' . strtoupper($order_direction)
        ));
        
        $this->display('catalog/views/backend/attributes', array(
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
            $brand = new Brand($data);

            $this->display('catalog/views/backend/editBrand', array(
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
                    $this->display('catalog/views/backend/editBrand', array(
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
            'name' => 'name'
        );
        
        if (!isset($allowed_columns[$order_by])) {
            $order_by = 'id';
        }
        
        $order_sql = $allowed_columns[$order_by];
        
        if ($order_direction != 'desc') {
            $order_direction = 'asc';
        }
        
        $brands = Brand::find(array(
            'select' => 'catalog_brand.*',
            'order' => $order_sql . ' ' . strtoupper($order_direction)
        ));
        
        $this->display('catalog/views/backend/brands', array(
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
        
        $this->display('catalog/views/backend/categories', array(
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
            $category = new Category($data);
            $category->parent_id = (int) $id;
            $category->parent = Category::findById($category->parent_id);

            $this->display('catalog/views/backend/editCategory', array(
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
                    $this->display('catalog/views/backend/editCategory', array(
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

        $content = new View('../../plugins/catalog/views/backend/category_children', array(
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
        $path = PLUGINS_ROOT . '/catalog/views/backend/documentation/';

        if (!file_exists( $path . $local_doc . '.php' ))
            $local_doc = 'en-documentation';

        $this->display('catalog/views/backend/documentation/' . $local_doc );
    }
    
    public function export($model = null, $format = null) {
        if (!is_null($model)) {
            $data = array();
            
            if ($model == 'product') {
                $data[] = array(
                    __('ID'),
                    __('Name'),
                    __('Brand'),
                    __('Category'),
                    __('URL')
                );
                
                $products = Product::findAll();
                foreach ($products as $product) {
                    if (is_object($product->brand)) {
                        $brand_name = $product->brand->name;
                    }
                    else {
                        $brand_name = '';
                    }
                    
                    $data[] = array(
                        $product->id,
                        $product->name,
                        $brand_name,
                        $product->category->title,
                        $product->url()
                    );
                }
            }
            
            if ($format == 'csv') {
                echo new View('../../plugins/catalog/views/backend/export_csv', array(
                    'model' => $model,
                    'data' => $data
                ));
            }
            else {
                $this->display('catalog/views/backend/export', array(
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
            $product = new Product($data);

            $this->display('catalog/views/backend/editProduct', array(
                'action' => 'add',
                'product' => $product,
                'brands' => Brand::findAll(),
                'categories' => Category::findAll()
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
                    $this->display('catalog/views/backend/editProduct', array(
                        'action' => 'edit',
                        'product' => $product,
                        'brands' => Brand::findAll(),
                        'categories' => Category::findAll(),
                        'variant' => new ProductVariant()
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
            'brand' => 'brand.name',
            'category' => 'category.title',
            'variants' => 'variant_count',
            'price' => 'min_price',
            'stock' => 'total_stock'
        );
        
        if (!isset($allowed_columns[$order_by])) {
            $order_by = 'id';
        }
        
        $order_sql = $allowed_columns[$order_by];
        
        if ($order_direction != 'desc') {
            $order_direction = 'asc';
        }
        
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
        
        $this->display('catalog/views/backend/products', array(
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
    
    public function settings() {
        if (isset($_POST['save']) && $_POST['save'] == __('Save Settings')) {
            Plugin::setAllSettings($_POST['setting'], self::PLUGIN_NAME);
            Flash::setNow('success', __('Settings have been saved!'));
        }
        
        $this->display('catalog/views/backend/settings', array(
            'settings' => Plugin::getAllSettings(self::PLUGIN_NAME),
            'layouts'  => Layout::findAll(array())
        ));
    }
}