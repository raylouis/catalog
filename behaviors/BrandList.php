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

class BrandList
{
    protected $page;

    public function __construct(&$page, $params)
    {
        $this->page = &$page;

        switch (count($params)) {
            case 0:
                break;
            case 1:
                if ($brand = Brand::findBySlug($params[0])) {
                    $brand->_executeLayout();
                    exit;
                } elseif ($this->page = find_page_by_slug($params[0], $this->page)) {

                } else {
                    page_not_found();
                }
                break;
            default:
                page_not_found();
        }
    }
}
