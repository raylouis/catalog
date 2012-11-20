<?php
class ProductPage extends CatalogPage {
    public function __construct($product) {
        $this->product = $product;
        
        $this->title = $product->name();
        $this->breadcrumb = $product->name();
        $this->slug = $product->slug;
        $this->keywords = str_replace(' ', ', ', $product->name());
        $this->description = $product->description;
        $this->layout_id = Plugin::getSetting('layout_id', 'catalog');
        
        if ($product->category_id > 0) {
            $product->category = Category::findById($product->category_id);
            $this->parent = new CategoryPage($product->category);
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
            $this->includeSnippet('product');
        }
    }
}