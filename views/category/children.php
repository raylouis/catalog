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
<ul<?php if ($level == 1) echo ' id="site-map" class="sortable tree-root"'; else echo ' class="sortable child"'; ?>>
<?php foreach($children as $child): ?> 
    <li id="page_<?php echo $child->id; ?>" class="node level-<?php echo $level; if ( ! $child->has_children) echo ' no-children'; else if ($child->is_expanded) echo ' children-visible'; else echo ' children-hidden'; ?>">
      <span>
      <div class="page">
        <span class="w1">
          <?php if ($child->has_children): ?><img align="middle" alt="toggle children" class="expander<?php if($child->is_expanded) echo ' expanded'; ?>" src="<?php echo URI_PUBLIC;?>wolf/admin/images/<?php echo $child->is_expanded ? 'collapse': 'expand'; ?>.png" title="" /><?php endif; ?>
            <?php if (AuthUser::hasPermission('catalog_category_edit')) { ?>
            <a class="edit-link" href="<?php echo get_url('plugin/catalog/category/edit/'.$child->id); ?>" title="<?php echo $child->id.' | '.$child->slug; ?>"><img align="middle" class="icon" src="<?php echo URI_PUBLIC;?>wolf/icons/file-folder-32.png" alt="page icon" /> <span class="title"><?php echo $child->title; ?></span></a> <img class="handle_reorder" src="<?php echo URI_PUBLIC;?>wolf/admin/images/drag_to_sort.gif" alt="<?php echo __('Drag and Drop'); ?>" /> <img class="handle_copy" src="<?php echo URI_PUBLIC;?>wolf/admin/images/drag_to_copy.gif" alt="<?php echo __('Drag to Copy'); ?>" align="middle" />
            <?php } else { ?>
            <img class="icon" src="<?php echo URI_PUBLIC;?>wolf/icons/file-folder-32.png" alt="page icon" /> <span class="title"><?php echo $child->title; ?></span>
            <?php } ?>
          <img alt="" class="busy" id="busy-<?php echo $child->id; ?>" src="<?php echo URI_PUBLIC; ?>wolf/admin/images/spinner.gif" title="" />
        </span>
      </div>
        <div class="view-page">
            <a href="<?php echo $child->url(); ?>" target="_blank"><img src="<?php echo CATALOG_IMAGES; ?>action-open-16.png" align="middle" alt="<?php echo __('View category'); ?>" title="<?php echo __('View category'); ?>" /></a>
        </div>
      <div class="modify">
        <?php if (AuthUser::hasPermission('catalog_category_add')): ?>
            <a class="add-child-link" href="<?php echo get_url('plugin/catalog/category/add', $child->id); ?>"><img src="<?php echo CATALOG_IMAGES; ?>action-add-16.png" align="middle" title="<?php echo __('Add child'); ?>" alt="<?php echo __('Add child'); ?>" /></a>&nbsp;
        <?php endif; ?>
        
        <?php if (AuthUser::hasPermission('catalog_category_delete')): ?>
            <a class="remove" href="<?php echo get_url('plugin/catalog/category/delete', $child->id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete :categorytitle and its underlying categories?', array(':categorytitle' => $child->title)); ?>');"><img src="<?php echo CATALOG_IMAGES; ?>action-delete-16.png" alt="<?php echo __('Delete'); ?>" title="<?php echo __('Delete'); ?>" /></a>
        <?php endif; ?>
      </div>
      </span>
<?php if ($child->has_children && $child->is_expanded) echo $child->children_rows; ?>
    </li>
<?php endforeach; ?>
</ul>