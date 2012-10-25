<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * @author Nic Wortel <nd.wortel@gmail.com>
 * 
 * @file        /enable.php
 * @date        28/09/2012
 */

Plugin::setAllSettings(array(
    'layout_id' => Page::findById(1)->layout_id
), 'catalog');

$PDO = Record::getConnection();

$PDO->exec("CREATE TABLE `catalog_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `type` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

$PDO->exec("CREATE TABLE `catalog_brand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `logo_image_id` int(10) unsigned DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by_id` int(11) NOT NULL,
  `updated_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_brand_image1` (`logo_image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

$PDO->exec("CREATE TABLE `catalog_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by_id` int(11) NOT NULL,
  `updated_by_id` int(11) NOT NULL,
  `image_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_category_category1` (`parent_id`),
  KEY `fk_category_image1` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

$PDO->exec("INSERT INTO `catalog_category` (title, slug, parent_id, created_on, updated_on, created_by_id, updated_by_id) VALUES ('Root', '', NULL, NOW(), NOW(), 1, 1);");

$PDO->exec("CREATE TABLE `catalog_category_attribute` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(1) unsigned NOT NULL,
  `attribute_id` int(1) unsigned NOT NULL,
  `filter` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `position` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

$PDO->exec("CREATE TABLE `catalog_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'simple',
  `category_id` int(10) unsigned NOT NULL DEFAULT '1',
  `brand_id` int(10) unsigned DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by_id` int(11) NOT NULL,
  `updated_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product_category` (`category_id`),
  KEY `fk_product_brand1` (`brand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

$PDO->exec("CREATE TABLE `catalog_product_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product_attribute_value_attribute1` (`attribute_id`),
  KEY `fk_product_attribute_value_product1` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

$PDO->exec("CREATE TABLE `catalog_product_variable_attribute` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(1) unsigned NOT NULL,
  `attribute_id` int(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

$PDO->exec("CREATE TABLE `catalog_product_variable_option` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `variable_id` int(1) unsigned NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

$PDO->exec("CREATE TABLE `catalog_product_variant` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `weight` float unsigned DEFAULT NULL,
  `price` double unsigned DEFAULT NULL,
  `stock` int(10) unsigned DEFAULT NULL,
  `product_id` int(1) unsigned NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by_id` int(10) unsigned NOT NULL,
  `updated_by_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

$PDO->exec("CREATE TABLE `catalog_product_variant_attribute` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `variant_id` int(1) NOT NULL,
  `attribute_id` int(1) NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");