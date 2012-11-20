<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nic.wortel@nth-root.nl>
 * 
 * @file        /index.php
 * @date        11/09/2012
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
    'version'               =>    '0.0.1',
    'website'               =>    'http://www.wolfcms.org/',
    'require_wolf_version'  =>    '0.7.4'
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