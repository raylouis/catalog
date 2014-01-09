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

class Brand extends CatalogNode
{
    const TABLE_NAME = 'catalog_brand';
    
    static $has_many = array(
        'products' => array(
            'class_name' => 'Product',
            'foreign_key' => 'brand_id'
        )
    );
    static $belongs_to = array(
        'logo' => array(
            'class_name' => 'Attachment',
            'foreign_key' => 'logo_attachment_id'
        )
    );
    
    public $id;
    public $name = '';
    public $slug = '';
    public $description = '';
    public $website = '';

    public $logo_attachment_id;
    
    public $created_on;
    public $updated_on;
    public $created_by_id;
    public $updated_by_id;
    
    public $url = '';

    public function unsetLogo()
    {
        $this->logo_attachment_id = '';
        unset($this->logo);
    }

    public function breadcrumb()
    {
        return $this->name;
    }

    public function children()
    {
        return array();
    }

    public function content($part = 'body', $inherit = false)
    {
        if ($part == 'body') {
            $this->includeSnippet('brand');
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
        return $this->name;
    }

    public function parent($level = null)
    {
        return new BrandListPage(Brand::findAll());
    }

    public function slug()
    {
        return $this->slug;
    }

    public function title()
    {
        return $this->name;
    }
    
    public function beforeInsert()
    {
        $this->created_on       = date('Y-m-d H:i:s');
        $this->created_by_id    = AuthUser::getRecord()->id;

        return true;
    }
    
    public function beforeSave()
    {
        $this->slug             = Node::toSlug($this->name);
        
        $this->updated_on       = date('Y-m-d H:i:s');
        $this->updated_by_id    = AuthUser::getRecord()->id;
        
        return true;
    }
    
    public function date($format='%a, %e %b %Y', $which_one='created')
    {
        if ($which_one == 'update' || $which_one == 'updated') {
            return strftime($format, strtotime($this->updated_on));
        } else {
            return strftime($format, strtotime($this->created_on));
        }
    }
    
    public static function findAll()
    {
        return self::find(array(
            'order' => 'name ASC',
            'include' => array('logo')
        ));
    }
    
    public static function findById($id)
    {
        return self::find(array(
            'where' => array('id = :id', ':id' => $id),
            'limit' => 1,
            'include' => array('logo')
        ));
    }

    public static function findByLogoAttachmentId($attachment_id)
    {
        return self::find(array(
            'where' => array(
                'logo_attachment_id = :attachment_id',
                ':attachment_id' => $attachment_id
            )
        ));
    }
    
    public static function findBySlug($slug)
    {
        return self::find(array(
            'where' => array('slug = :slug', ':slug' => $slug),
            'limit' => 1,
            'include' => array('products' => array('brand', 'category'), 'logo')
        ));
    }
    
    public function getColumns()
    {
        return array(
            'id', 'name', 'slug', 'description', 'website', 'logo_attachment_id',
            'created_on', 'updated_on', 'created_by_id', 'updated_by_id'
        );
    }

    public function hasLogo()
    {
        return (boolean) $this->logo();
    }

    public function logo()
    {
        if (!isset($this->logo)) {
            if (!$this->logo = Attachment::findById($this->logo_attachment_id)) {
                $this->logo = false;
            }
        }

        return $this->logo;
    }
}
