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

if (!defined('CATALOG')) {
    define('CATALOG', PLUGINS_ROOT.'/catalog');
}
if (!defined('CATALOG_IMAGES')) {
    define('CATALOG_IMAGES', URL_PUBLIC . 'wolf/plugins/catalog/images/');
}

Plugin::setInfos(array(
    'id'                    =>    'catalog',
    'title'                 =>    __('Catalog'),
    'description'           =>    __('The catalog plugin adds a catalog or webshop to Wolf CMS.'),
    'type'                  =>    'both',
    'author'                =>    'Nic Wortel',
    'version'               =>    '0.1.5',
    'website'               =>    'http://www.wolfcms.org/',
    'require_wolf_version'  =>    '0.7.6'
));

Plugin::addController('catalog', __('Catalog'), 'catalog_view', true);

AutoLoader::addFolder(CATALOG.'/models');
AutoLoader::addFolder(CATALOG.'/pages');

$brands_slug = Plugin::getSetting('brands_slug', 'catalog');

Dispatcher::addRoute(array(
    '/' . $brands_slug => '/plugin/catalog/frontendBrandList',
    '/' . $brands_slug . '/:any' => '/plugin/catalog/frontendBrand/$1'
));

if ($categories = Category::findByParentId(1)) {
    foreach ($categories as $category) {
        Dispatcher::addRoute(array(
            '/' . $category->slug              => '/plugin/catalog/frontend/' . $category->slug,
            '/' . $category->slug . '/:any'    => '/plugin/catalog/frontend/' . $category->slug . '/$1'
        ));
    }
}
