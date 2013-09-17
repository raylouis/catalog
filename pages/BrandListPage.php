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

class BrandListPage extends CatalogPage {
    public function __construct($brands) {
        $this->brands = $brands;
        
        $brands_slug = Plugin::getSetting('brands_slug', 'catalog');
        $brands_title = Plugin::getSetting('brands_title', 'catalog');
        
        $this->title = $brands_title;
        $this->breadcrumb = $brands_title;
        $this->slug = $brands_slug;
        $this->keywords = $brands_title;
        $this->description = '';
        $this->layout_id = Plugin::getSetting('layout_id', 'catalog');
        
        $this->parent = Page::find('/');
        
        if ($this->parent) {
            $this->setUrl();
        }
    }
    
    public function content($part = 'body', $inherit = false) {
        if ($part == 'body') {
            $this->includeSnippet('brand_list');
        }
    }
}