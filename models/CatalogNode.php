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

use_helper('ActiveRecord');

abstract class CatalogNode extends ActiveRecord
{
    public $path = false;


    /**
     * Returns properties for backward compatibility reasons.
     * 
     * @param  [type] $var [description]
     * @return [type]      [description]
     */
    public function __get($var)
    {
        if ($var == 'url')
        {
            return $this->path();
        }
    }


    /**
     * Returns an array of ancestors.
     * 
     * @return  array               Array of ancestor Nodes
     */
    public function ancestors() {
        $ancestors = array();

        if ($this->parent()) {
            if ($this->parent()->parent()) {
                $ancestors = $this->parent()->ancestors();
            }
            $ancestors[] = $this->parent();
        }

        return $ancestors;
    }


    /**
     * Returns the node's breadcrumb.
     *
     * @abstract
     * @return  string              The node's breadcrumb
     */
    abstract public function breadcrumb();


    /**
     * Returns a trail of breadcrumbs as HTML.
     * 
     * @param  string   $seperator  The separator between breadcrumbs. Defaults to &gt;
     * @return string               The breadcrumbs as an html snippet.
     */
    public function breadcrumbs($seperator = '&gt;') {
        $seperator = '<span class="breadcrumb-seperator">' . $seperator . '</span>';
        
        $breadcrumbs = array();

        foreach ($this->ancestors() as $ancestor) {
            $breadcrumbs[] = '<a href="' . $ancestor->url() . '">' . $ancestor->breadcrumb() . '</a>';
        }

        // add current node
        $breadcrumbs[] = '<span class="breadcrumb-current">' . $this->breadcrumb() . '</span>';

        $html = implode($seperator, $breadcrumbs);

        return $html;
    }


    /**
     * Returns an array of this node's children.
     *
     * @abstract
     * @return  array               Array of children objects
     */
    abstract public function children();


    /**
     * Returns the node's content, or a specific content part.
     *
     * @abstract
     * @param  string   $part       Part to retrieve content for. Defaults to 'body'.
     * @param  boolean  $inherit    Check parents for part content if true.
     * @return string               Actual contents of the part.
     */
    abstract public function content($part = 'body', $inherit = false);


    /**
     * Returns the node's description.
     *
     * @abstract
     * @return  string              The node's description
     */
    abstract public function description();


    /**
     * Checks if a part exists and it has content.
     *
     * @abstract
     * @param  string   $part       Part to retrieve content for.
     * @param  boolean  $inherit    Check parents for part content if true.
     * @return boolean              Returns true if part was found or false if nothing was found.
     */
    abstract public function hasContent($part, $inherit = false);


    /**
     * Returns the node's keywords.
     *
     * @abstract
     * @return  string              The node's keywords, comma-seperated
     */
    abstract public function keywords();


    /**
     * Returns a numerical representation of this node's place in the page hierarchy.
     *
     * @return  int     The node's level.
     */
    public function level() {
        return count($this->ancestors());
    }


    /**
     * Returns an HTML anchor element for this node.
     * 
     * @param   string  $label      A custom label. Defaults to node's title.
     * @param   string  $options    Attributes that should be added.
     * @return  string              The HTML anchor element.
     */
    public function link($label = null, $options = '') {
        if ($label == null) {
            $label = $this->title();
        }

        return sprintf('<a href="%s" %s>%s</a>', $this->url(true), $options, $label);
    }


    /**
     * Returns the node's parent.
     *
     * The optional $level parameter allows the user to specify the level
     * of the parent. I.e. $node->parent(0) should return the Home page.
     *
     * @abstract
     * @param  mixed    $level      Optional level parameter, defaults to null
     * @return Node                 Returns the parent object
     */
    abstract public function parent($level = null);


    /**
     * Returns the path for this node.
     * 
     * For instance, for a page with the URL http://www.example.com/wolfcms/path/to/page.html,
     * the path is: path/to/page (without the URL_SUFFIX)
     *
     * Note: The path does not start nor end with a '/'.
     *
     * @return string   The node's full path.
     */
    public function path() {
        if ($this->path === false) {
            if ($this->parent() !== false) {
                $this->path = trim($this->parent()->uri() . '/' . $this->slug(), '/');
            } else {
                $this->path = trim($this->slug(), '/');
            }
        }

        return $this->path;
    }

    public function uri()
    {
        return $this->path();
    }


    /**
     * Returns the node's slug.
     *
     * @abstract
     * @return  string              The node's slug
     */
    abstract public function slug();


    /**
     * Returns the node's title.
     *
     * @abstract
     * @return  string              The node's title
     */
    abstract public function title();


    /**
     * Returns the current node's URL.
     *
     * Usage: <code><?php echo $node->url(); ?></code>
     * 
     * In certain contexts, $this can be a Node, so you can use:
     * <code><?php echo $this->url(); ?></code>
     * 
     * @param  boolean  $suffix     URL includes URL_SUFFIX when set to true
     * @return string               The URL of the Node object
     */
    public function url($suffix = true) {
        $base_url = URL_PUBLIC;

        if ($suffix === false) {
            return $base_url . $this->path();
        } else {
            return $base_url . $this->path() . ($this->path() != '' ? URL_SUFFIX : '');
        }
    }


    /**
     * Intended to eventually replace like-wise names JS function from wolf.js
     * 
     * Note: this function might undergo a name change in future...
     *
     * @param type $string
     * @return type 
     */
    public static function toSlug($string) {
        return Node::toSlug($string);
    }


    public function _executeLayout()
    {

        $sql = 'SELECT content_type, content FROM '.TABLE_PREFIX.'layout WHERE id = :layout_id';

        $stmt = Record::getConnection()->prepare($sql);

        //$stmt->execute(array(':layout_id' => Plugin::getSetting('layout_id', 'catalog')));
        //
        $stmt->execute(array(':layout_id' => 2));

        if ($layout = $stmt->fetchObject()) {
            // if content-type not set, we set html as default
            if ($layout->content_type == '')
                $layout->content_type = 'text/html';

            // set content-type and charset of the page
            header('Content-Type: '.$layout->content_type.'; charset=UTF-8');

            Observer::notify('page_before_execute_layout', $layout);

            // execute the layout code
            eval('?'.'>'.$layout->content);
            // echo $layout->content;
        }
    }


    /**
     * Allows people to include the parsed content from a Snippet in a Page.
     *
     * The method returns either true or false depending on whether the snippet
     * was found or not.
     *
     * @param   string  $name   Snippet name.
     * @return  boolean         Returns either true or false.
     */
    public function includeSnippet($name) {
        $snippet = Snippet::findByName($name);

        if (false !== $snippet) {
            eval('?'.'>'.$snippet->content_html);
            return true;
        }

        return false;
    }

}
