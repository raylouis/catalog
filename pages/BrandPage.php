<?php
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