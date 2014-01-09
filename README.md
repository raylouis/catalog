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

* PHP 5.3 or higher
* MySQL
* Wolf CMS 0.7.6 or higher
* The [ActiveRecord helper](https://github.com/nicwortel/wolfcms-ActiveRecord)
* The [Media plugin](https://github.com/nicwortel/wolfcms-media)

Installation instructions
-------------------------

1. Download the [ActiveRecord helper](https://github.com/nicwortel/wolfcms-ActiveRecord) and put it in *CMS_ROOT/wolf/helpers/ActiveRecord.php*.
2. Download the [Media plugin](https://github.com/nicwortel/wolfcms-media) and place it in the plugin directory (*CMS_ROOT/wolf/plugins*).
3. In the backend of Wolf CMS, open the 'Administration' tab and enable the Media plugin by checking the checkbox.
4. Open the 'Media' tab and follow the instructions in the documentation.
5. Next, place the catalog plugin folder in the plugins directory (*CMS_ROOT/wolf/plugins*).
6. Enable the catalog plugin (see step 3).

**Important:** For the catalog plugin, as well as the media plugin, make sure that the plugin folder's name is the plugin's id. In case of the catalog plugin, it's folder name should be 'catalog', not 'wolfcms-catalog' or anything else.
