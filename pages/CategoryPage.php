<?php
class CategoryPage extends CatalogPage {
    public function __construct($category) {
        $this->category = $category;
        
        $this->title = $category->title;
        $this->breadcrumb = $category->title;
        $this->slug = $category->slug;
        $this->keywords = $category->title;
        $this->layout_id = Plugin::getSetting('layout_id', 'catalog');
        $this->layout_id = 2;
        
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