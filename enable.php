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
  UNIQUE INDEX `title_UNIQUE` (`title` ASC) ,
  UNIQUE INDEX `slug_UNIQUE` (`slug` ASC) ,
  CONSTRAINT `fk_category_parent`
    FOREIGN KEY (`parent_id` )
    REFERENCES `" . TABLE_PREFIX . "catalog_category` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
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
  UNIQUE INDEX `slug_UNIQUE` (`slug` ASC) ,
  CONSTRAINT `fk_product_category`
    FOREIGN KEY (`category_id` )
    REFERENCES `" . TABLE_PREFIX . "catalog_category` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_product_brand`
    FOREIGN KEY (`brand_id` )
    REFERENCES `" . TABLE_PREFIX . "catalog_brand` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
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
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB");

$PDO->exec("CREATE  TABLE IF NOT EXISTS `" . TABLE_PREFIX . "catalog_attribute_group` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB");

$PDO->exec("CREATE  TABLE IF NOT EXISTS `" . TABLE_PREFIX . "catalog_attribute` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `type` VARCHAR(64) NOT NULL ,
  `unit` VARCHAR(20) NULL DEFAULT NULL ,
  `attribute_group_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_attribute_attribute_group` (`attribute_group_id` ASC) ,
  CONSTRAINT `fk_attribute_attribute_group`
    FOREIGN KEY (`attribute_group_id` )
    REFERENCES `" . TABLE_PREFIX . "catalog_attribute_group` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB");