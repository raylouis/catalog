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
 * @version     0.0.1
 */

Plugin::setAllSettings(array(
    'layout_id' => 0,
    'decimal_seperator' => 'point',
    'brands_title' => 'Brands',
    'brands_slug' => 'brands'
), 'catalog');

$PDO = Record::getConnection();

$PDO->exec("CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "catalog_brand` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `slug` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `website` VARCHAR(255) NULL DEFAULT NULL ,
  `created_on` DATETIME NOT NULL ,
  `updated_on` DATETIME NOT NULL ,
  `created_by_id` INT UNSIGNED NOT NULL ,
  `updated_by_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  UNIQUE INDEX `slug_UNIQUE` (`slug` ASC) )
ENGINE = InnoDB");

$PDO->exec("CREATE  TABLE IF NOT EXISTS `" . TABLE_PREFIX . "catalog_category` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `slug` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `parent_id` INT UNSIGNED NULL DEFAULT NULL ,
  `position` INT UNSIGNED NULL DEFAULT NULL ,
  `created_on` DATETIME NOT NULL ,
  `updated_on` DATETIME NOT NULL ,
  `created_by_id` INT UNSIGNED NOT NULL ,
  `updated_by_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_category_parent` (`parent_id` ASC) ,
  UNIQUE INDEX `slug_UNIQUE` (`parent_id` ASC, `slug` ASC) ,
  CONSTRAINT `fk_category_parent`
    FOREIGN KEY (`parent_id` )
    REFERENCES `" . TABLE_PREFIX . "catalog_category` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB");

$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_category` (`id`,`title`,`slug`,`description`,`parent_id`,`position`,`created_on`,`updated_on`,`created_by_id`,`updated_by_id`) VALUES (1,'Products','products',NULL,NULL,NULL,NOW(),NOW(),1,1);");

$PDO->exec("CREATE  TABLE IF NOT EXISTS `" . TABLE_PREFIX . "catalog_product` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `slug` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `type` VARCHAR(25) NOT NULL DEFAULT 'simple' ,
  `category_id` INT UNSIGNED NOT NULL DEFAULT 1 ,
  `brand_id` INT UNSIGNED NULL DEFAULT NULL ,
  `created_on` DATETIME NOT NULL ,
  `updated_on` DATETIME NOT NULL ,
  `created_by_id` INT UNSIGNED NOT NULL ,
  `updated_by_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_product_category` (`category_id` ASC) ,
  INDEX `fk_product_brand` (`brand_id` ASC) ,
  UNIQUE INDEX `slug_UNIQUE` (`category_id` ASC, `slug` ASC) ,
  CONSTRAINT `fk_product_category`
    FOREIGN KEY (`category_id` )
    REFERENCES `" . TABLE_PREFIX . "catalog_category` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_brand`
    FOREIGN KEY (`brand_id` )
    REFERENCES `" . TABLE_PREFIX . "catalog_brand` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB");

$PDO->exec("CREATE  TABLE IF NOT EXISTS `" . TABLE_PREFIX . "catalog_product_variant` (
  `id` INT NOT NULL ,
  `sku` VARCHAR(50) NULL DEFAULT NULL ,
  `name` VARCHAR(255) NULL DEFAULT NULL ,
  `weight` FLOAT UNSIGNED NULL DEFAULT NULL ,
  `price` DOUBLE UNSIGNED NULL DEFAULT NULL ,
  `vat_id` INT UNSIGNED NULL DEFAULT NULL ,
  `stock` INT UNSIGNED NULL DEFAULT NULL ,
  `product_id` INT UNSIGNED NOT NULL ,
  `created_on` DATETIME NOT NULL ,
  `updated_on` DATETIME NOT NULL ,
  `created_by_id` INT UNSIGNED NOT NULL ,
  `updated_by_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_product_variant_product` (`product_id` ASC) ,
  UNIQUE INDEX `sku_UNIQUE` (`sku` ASC) ,
  CONSTRAINT `fk_product_variant_product`
    FOREIGN KEY (`product_id` )
    REFERENCES `" . TABLE_PREFIX . "catalog_product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB");

$PDO->exec("CREATE  TABLE IF NOT EXISTS `" . TABLE_PREFIX . "catalog_attribute_unit_system` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB");

$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_unit_system` (`id`, `name`) VALUES (1, 'Metric');");

$PDO->exec("CREATE  TABLE IF NOT EXISTS `" . TABLE_PREFIX . "catalog_attribute_type` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `data_type` ENUM('BOOLEAN','INT','FLOAT','VARCHAR','DATE','DATETIME') NOT NULL DEFAULT INT ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = InnoDB");

$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_type` (`id`, `name`, `data_type`) VALUES (1, 'Distance', 'FLOAT');");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_type` (`id`, `name`, `data_type`) VALUES (2, 'Weight', 'FLOAT');");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_type` (`id`, `name`, `data_type`) VALUES (3, 'Volume', 'FLOAT');");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_type` (`id`, `name`, `data_type`) VALUES (4, 'Temperature', 'FLOAT');");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_type` (`id`, `name`, `data_type`) VALUES (5, 'Surface', 'FLOAT');");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_type` (`id`, `name`, `data_type`) VALUES (6, 'Text', 'VARCHAR');");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_type` (`id`, `name`, `data_type`) VALUES (7, 'Color', 'VARCHAR');");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_type` (`id`, `name`, `data_type`) VALUES (8, 'Yes/No', 'BOOLEAN');");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_type` (`id`, `name`, `data_type`) VALUES (9, 'Duration', 'FLOAT');");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_type` (`id`, `name`, `data_type`) VALUES (10, 'Number', 'FLOAT');");

$PDO->exec("CREATE  TABLE IF NOT EXISTS `" . TABLE_PREFIX . "catalog_attribute_unit` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `abbreviation` VARCHAR(10) NOT NULL ,
  `multiplier` FLOAT UNSIGNED NULL DEFAULT NULL ,
  `parent_id` INT UNSIGNED NULL DEFAULT NULL ,
  `attribute_unit_system_id` INT UNSIGNED NOT NULL ,
  `attribute_type_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_attribute_unit_attribute_unit` (`parent_id` ASC) ,
  INDEX `fk_attribute_unit_attribute_unit_system` (`attribute_unit_system_id` ASC) ,
  INDEX `fk_attribute_unit_attribute_type` (`attribute_type_id` ASC) ,
  CONSTRAINT `fk_attribute_unit_attribute_unit`
    FOREIGN KEY (`parent_id` )
    REFERENCES `" . TABLE_PREFIX . "catalog_attribute_unit` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_attribute_unit_attribute_unit_system`
    FOREIGN KEY (`attribute_unit_system_id` )
    REFERENCES `" . TABLE_PREFIX . "catalog_attribute_unit_system` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_attribute_unit_attribute_type`
    FOREIGN KEY (`attribute_type_id` )
    REFERENCES `" . TABLE_PREFIX . "catalog_attribute_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB");

$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_unit` (`id`, `name`, `abbreviation`, `multiplier`, `parent_id`, `attribute_unit_system_id`, `attribute_type_id`) VALUES (1, 'Millimeter', 'mm', NULL, NULL, 1, 1);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_unit` (`id`, `name`, `abbreviation`, `multiplier`, `parent_id`, `attribute_unit_system_id`, `attribute_type_id`) VALUES (2, 'Centimeter', 'cm', NULL, NULL, 1, 1);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_unit` (`id`, `name`, `abbreviation`, `multiplier`, `parent_id`, `attribute_unit_system_id`, `attribute_type_id`) VALUES (3, 'Decimeter', 'dm', NULL, NULL, 1, 1);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_unit` (`id`, `name`, `abbreviation`, `multiplier`, `parent_id`, `attribute_unit_system_id`, `attribute_type_id`) VALUES (4, 'Meter', 'm', NULL, NULL, 1, 1);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_unit` (`id`, `name`, `abbreviation`, `multiplier`, `parent_id`, `attribute_unit_system_id`, `attribute_type_id`) VALUES (5, 'Decameter', 'dam', NULL, NULL, 1, 1);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_unit` (`id`, `name`, `abbreviation`, `multiplier`, `parent_id`, `attribute_unit_system_id`, `attribute_type_id`) VALUES (6, 'Hectometer', 'hm', NULL, NULL, 1, 1);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_unit` (`id`, `name`, `abbreviation`, `multiplier`, `parent_id`, `attribute_unit_system_id`, `attribute_type_id`) VALUES (7, 'Kilometer', 'km', NULL, NULL, 1, 1);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_unit` (`id`, `name`, `abbreviation`, `multiplier`, `parent_id`, `attribute_unit_system_id`, `attribute_type_id`) VALUES (8, 'Milligram', 'mg', NULL, NULL, 1, 2);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_unit` (`id`, `name`, `abbreviation`, `multiplier`, `parent_id`, `attribute_unit_system_id`, `attribute_type_id`) VALUES (9, 'Centigram', 'cg', NULL, NULL, 1, 2);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_unit` (`id`, `name`, `abbreviation`, `multiplier`, `parent_id`, `attribute_unit_system_id`, `attribute_type_id`) VALUES (10, 'Decigram', 'dg', NULL, NULL, 1, 2);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_unit` (`id`, `name`, `abbreviation`, `multiplier`, `parent_id`, `attribute_unit_system_id`, `attribute_type_id`) VALUES (11, 'Gram', 'g', NULL, NULL, 1, 2);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_unit` (`id`, `name`, `abbreviation`, `multiplier`, `parent_id`, `attribute_unit_system_id`, `attribute_type_id`) VALUES (12, 'Decagram', 'dag', NULL, NULL, 1, 2);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_unit` (`id`, `name`, `abbreviation`, `multiplier`, `parent_id`, `attribute_unit_system_id`, `attribute_type_id`) VALUES (13, 'Hectagram', 'hg', NULL, NULL, 1, 2);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute_unit` (`id`, `name`, `abbreviation`, `multiplier`, `parent_id`, `attribute_unit_system_id`, `attribute_type_id`) VALUES (14, 'Kilogram', 'kg', NULL, NULL, 1, 2);");


$PDO->exec("CREATE  TABLE IF NOT EXISTS `" . TABLE_PREFIX . "catalog_attribute` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `attribute_type_id` INT UNSIGNED NOT NULL ,
  `default_unit_id` INT UNSIGNED NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_attribute_attribute_unit` (`default_unit_id` ASC) ,
  INDEX `fk_attribute_attribute_type` (`attribute_type_id` ASC) ,
  CONSTRAINT `fk_attribute_attribute_unit`
    FOREIGN KEY (`default_unit_id` )
    REFERENCES `" . TABLE_PREFIX . "catalog_attribute_unit` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_attribute_attribute_type`
    FOREIGN KEY (`attribute_type_id` )
    REFERENCES `" . TABLE_PREFIX . "catalog_attribute_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB");

$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute` (`id`, `name`, `description`, `attribute_type_id`, `default_unit_id`) VALUES (1, 'Height', NULL, 1, 2);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute` (`id`, `name`, `description`, `attribute_type_id`, `default_unit_id`) VALUES (2, 'Width', NULL, 1, 2);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute` (`id`, `name`, `description`, `attribute_type_id`, `default_unit_id`) VALUES (3, 'Depth', NULL, 1, 2);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_attribute` (`id`, `name`, `description`, `attribute_type_id`, `default_unit_id`) VALUES (4, 'Weight', NULL, 2, 14);");

$PDO->exec("CREATE  TABLE IF NOT EXISTS `" . TABLE_PREFIX . "catalog_category_attribute` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `category_id` INT UNSIGNED NOT NULL ,
  `attribute_id` INT UNSIGNED NOT NULL ,
  `is_filter` TINYINT(1) NOT NULL DEFAULT 0 ,
  `position` INT UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_category_attribute_attribute` (`attribute_id` ASC) ,
  INDEX `fk_category_attribute_category` (`category_id` ASC) ,
  CONSTRAINT `fk_category_attribute_attribute`
    FOREIGN KEY (`attribute_id` )
    REFERENCES `" . TABLE_PREFIX . "catalog_attribute` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_category_attribute_category`
    FOREIGN KEY (`category_id` )
    REFERENCES `" . TABLE_PREFIX . "catalog_category` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB");

$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_category_attribute` (`id`, `category_id`, `attribute_id`, `is_filter`, `position`) VALUES (1, 1, 1, 0, NULL);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_category_attribute` (`id`, `category_id`, `attribute_id`, `is_filter`, `position`) VALUES (2, 1, 2, 0, NULL);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_category_attribute` (`id`, `category_id`, `attribute_id`, `is_filter`, `position`) VALUES (3, 1, 3, 0, NULL);");
$PDO->exec("INSERT INTO `" . TABLE_PREFIX . "catalog_category_attribute` (`id`, `category_id`, `attribute_id`, `is_filter`, `position`) VALUES (4, 1, 4, 0, NULL);");