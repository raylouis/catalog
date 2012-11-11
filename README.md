Catalog plugin for Wolf CMS
===========================

The catalog plugin adds a catalog or webshop to Wolf CMS.

Features
--------

* Manage products and categories
* Assign attributes such as color, size, etc.
* Divide products into multiple unique variants based on those attributes
* Keep track of stock for each unique product variant

Requirements
------------

* The [ActiveRecord helper](https://github.com/NicNLD/ActiveRecord) (which requires PHP 5.3+)
* Wolf CMS 0.7.5 or higher (lower might be possible, but is not tested)
* MySQL

Installation instructions
-------------------------

1. Download the [ActiveRecord helper](https://github.com/NicNLD/ActiveRecord) and upload it to **CMS_ROOT/wolf/helpers**.
2. Upload the 'catalog' plugin folder to **CMS_ROOT/wolf/plugins**. Make sure it's named 'catalog', nothing else.
3. In the backend of Wolf CMS, go to **Administration** and enable the Catalog plugin by checking the checkbox.