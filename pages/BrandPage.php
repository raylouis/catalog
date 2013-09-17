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

class BrandPage extends CatalogPage {
    public function __construct($brand) {
        $this->brand = $brand;
        
        $this->title = $brand->name;
        $this->breadcrumb = $brand->name;
        $this->slug = $brand->slug;
        $this->keywords = $brand->name;
        $this->description = $brand->description;
        $this->layout_id = Plugin::getSetting('layout_id', 'catalog');
        
        $this->parent = new BrandListPage(Brand::findAll());
        
        if ($this->parent) {
            $this->setUrl();
        }
    }
    
    public function content($part = 'body', $inherit = false) {
        if ($part == 'body') {
            $this->includeSnippet('brand');
        }
    }
}