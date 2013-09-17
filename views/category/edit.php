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

?>
<h1><?php echo __(ucfirst($action).' category'); ?></h1>

<form method="post" action="<?php if ($action == 'add') echo get_url('plugin/catalog/category/add'); else echo get_url('plugin/catalog/category/edit/' . $category->id); ?>">
    <input id="category_parent_id" name="category[parent_id]" type="hidden" value="<?php echo $category->parent_id; ?>" />    
    <table border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td class="label"><label for="category_title"><?php echo __('Title'); ?></label></td>
                <td class="field"><input class="textbox" type="text" name="category[title]" id="category_title" value="<?php echo $category->title; ?>" /></td>
            </tr>
            <tr>
                <td class="label"><label for="category_description"><?php echo __('Description'); ?></label></td>
                <td class="field">
                    <textarea name="category[description]" id="category_description"><?php echo $category->description; ?></textarea>
                </td>
            </tr>
        </tbody>
    </table>
    
    <?php if (isset($category->parent)): ?>
    <?php $category->parent->attributes = $category->parent->unlimitedAttributes(); ?>
    <?php if ($category->parent->attributes > 0): ?>
    <h3><?php echo __('Inherited attributes'); ?></h3>
    
    <ul>
        <?php foreach ($category->parent->attributes as $inherited_attribute): ?>
        <li><?php echo $inherited_attribute->name; ?></li>
        <?php endforeach; ?>
    </ul>
    
    <?php endif; ?>
    <?php endif; ?>
    
    <h3><?php echo __('Attributes'); ?></h3>
    
    <ul id="category_attributes">
        <?php $i = 1; ?>
        <?php foreach ($category->attributes as $category_attribute): ?>
<?php echo new View('../../plugins/catalog/views/category/attribute', array(
    'attributes' => $attributes,
    'category_attribute' => $category_attribute,
    'i' => $i
)); ?>
        <?php $i++; ?>
        <?php endforeach; ?>
    </ul>
    
    <p><a href="#" class="add-attribute">Add a new attribute <img width="16" height="16" alt="Verwijderen" src="http://localhost/zandbak/wolf/icons/add-16.png"></a></p>
    
<script language="javascript">
var i = <?php echo $i; ?>;

$('.add-attribute').click(function() {
    var append = '<?php echo new View('../../plugins/catalog/views/category/attribute', array(
        'attributes' => $attributes,
        'category_attribute' => NULL,
        'i' => $i
    )); ?>';
    
    $('#category_attributes').append(append);
    return false;
});
</script>
    
    <p class="buttons">
        <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save and Close'); ?>" />
        <input class="button" name="continue" type="submit" accesskey="e" value="<?php echo __('Save and Continue Editing'); ?>" />
        <?php echo __('or'); ?> <a href="<?php echo get_url('plugin/catalog/categories'); ?>"><?php echo __('Cancel'); ?></a>
    </p>
</form>