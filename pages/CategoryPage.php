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

class CategoryPage extends CatalogPage {
    public function __construct($category) {
        $this->category = $category;
        
        $this->title = $category->title;
        $this->breadcrumb = $category->title;
        $this->slug = $category->slug;
        $this->keywords = $category->title;
        $this->layout_id = Plugin::getSetting('layout_id', 'catalog');
        
        if ($category->parent_id > 1) {
            $category->parent = Category::findById($category->parent_id);
            $this->parent = new CategoryPage($category->parent);
        }
        else {
            $this->parent = Page::find('/');
        }
        
        if ($this->parent) {
            $this->setUrl();
        }
    }
    
    public function content($part = 'body', $inherit = false) {
        if ($part == 'body') {
            $this->includeSnippet('category');
        }
        elseif ($part == 'sidebar') {
            $this->includeSnippet('category_filters');
        }
    }
}