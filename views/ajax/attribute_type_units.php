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
<?php if (count($attribute_type->units) > 0): ?>
<select name="attribute[default_unit_id]" id="attribute_default_unit_id">
    <?php foreach ($attribute_type->units as $unit): ?>
    <option value="<?php echo $unit->id; ?>"><?php echo strtolower($unit->name); ?></option>
    <?php endforeach; ?>
</select>
<?php endif; ?>