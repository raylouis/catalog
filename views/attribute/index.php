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

?>
<h1><?php echo __('Attributes'); ?></h1>

<p>
    <a href="<?php echo get_url('plugin/catalog/attribute/add'); ?>"><?php echo __('Add attribute'); ?></a> |
    <a href="<?php echo get_url('plugin/catalog/units'); ?>"><?php echo __('Manage units'); ?></a>
</p>

<table class="attribute list">
    <thead>
        <tr>
            <th class="number">
                <?php echo __('ID'); ?>
            </th>
            <th class="fill">
                <?php echo __('Name'); ?>
            </th>
            <th class="fill">
                <?php echo __('Type'); ?>
            </th>
            <th class="fill">
                <?php echo __('Default unit'); ?>
            </th>
            <th class="icon"><?php echo __('Delete'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($attributes as $attribute): ?>
        <tr>
            <td class="number">
                <?php echo $attribute->id; ?>
            </td>
            <td class="fill">
                <a href="<?php echo get_url('plugin/catalog/attribute/edit', $attribute->id); ?>"><?php echo $attribute->name; ?></a>
            </td>
            <td class="fill">
                <?php echo strtolower($attribute->type->name); ?>
            </td>
            <td class="fill">
                <?php if (isset($attribute->default_unit)): ?>
                <?php echo strtolower($attribute->default_unit->name); ?> (<?php echo $attribute->default_unit->abbreviation; ?>)
                <?php endif; ?>
            </td>
            <td class="icon">
                <?php if (AuthUser::hasPermission('catalog_attribute_delete')): ?>
                    <a href="<?php echo get_url('plugin/catalog/attribute/delete', $attribute->id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete :name?', array(':name' => $attribute->name)); ?>');">
                        <img width="16" height="16" src="<?php echo CATALOG_IMAGES; ?>action-delete-16.png" alt="<?php echo __('Delete'); ?>" title="<?php echo __('Delete'); ?>" />
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
