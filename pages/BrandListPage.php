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
 * @version     0.2.0
 */

class BrandListPage extends CatalogNode
{
    public function __construct()
    {
        $this->title = Plugin::getSetting('brands_title', 'catalog');
        $this->slug = Plugin::getSetting('brands_slug', 'catalog');
    }

    public function breadcrumb()
    {
        return $this->title;
    }

    public function children()
    {
        return Brand::findAll();
    }

    public function content($part = 'body', $inherit = false)
    {
        if ($part == 'body') {
            $this->includeSnippet('brand_list');
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
        return $this->title;
    }

    public function parent($level = null)
    {
        return Page::findByUri('/');
    }

    public function path()
    {
        return $this->slug();
    }

    public function slug()
    {
        return $this->slug;
    }

    public function title()
    {
        return $this->title;
    }
}
