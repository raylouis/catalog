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

<h1><?php echo __(ucfirst($action).' product'); ?></h1>

<form method="post" action="<?php if ($action == 'add') echo get_url('plugin/catalog/product/add'); else echo get_url('plugin/catalog/product/edit/'.$product->id); ?>">
    <h3><?php echo __('General product info'); ?></h3>
    
    <table border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td class="label"><label for="product_name"><?php echo __('Name'); ?></label></td>
                <td class="field"><input class="textbox" type="text" name="product[name]" id="product_name" value="<?php echo $product->name; ?>" /></td>
            </tr>
            <tr>
                <td class="label"><label><?php echo __('Brand'); ?></label></td>
                <td class="field">
                    <select class="selectbox" name="product[brand_id]">
                        <option value=""><?php echo __('None'); ?></option>
                        <?php foreach ($brands as $brand): ?>
                        <option value="<?php echo $brand->id; ?>"<?php if ($brand->id == $product->brand_id) { ?> selected="selected"<?php } ?>><?php echo $brand->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td class="label"><label><?php echo __('Category'); ?></label></td>
                <td class="field">
                    <select class="selectbox" name="product[category_id]" id="product_category_id">
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>"<?php if ($category->id == $product->category_id) { ?> selected="selected"<?php } ?>><?php echo $category->title; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label"><label for="product_description"><?php echo __('Description'); ?></label></td>
                <td class="field">
                    <textarea class="textarea" name="product[description]" id="product_description"><?php echo $product->description; ?></textarea>
                </td>
            </tr>
        </tbody>
    </table>

    <h3><?php echo __('Product properties'); ?></h3>

    <table>
        <tbody>
            <?php foreach ($product->productAttributes() as $attribute): ?>
            <?php
            $attribute->values = $attribute->findValuesByProductId($product->id);
            if (count($attribute->values) > 0) {
                $attribute->value = $attribute->values[0];
            }
            else {
                unset($attribute->value);
            }

            $selected_unit_id = $attribute->default_unit_id;
                if (isset($attribute->value)) {
                    $selected_unit_id = $attribute->value->attribute_unit_id;
                }
            ?>
            <tr>
                <td class="label">
                    <label for="product_attribute_<?php echo $attribute->id; ?>"><?php echo $attribute->name; ?></label>


                    <a href="#" class="move-to-variants">
                        <img width="16" height="16" title="<?php echo __('Break down between variants'); ?>" alt="<?php echo __('Break down between variants'); ?>" src="<?php echo CATALOG_IMAGES; ?>action-download-16.png">
                    </a>
                </td>
                <td class="field">
                    <?php if (in_array($attribute->type->data_type, array('INT', 'FLOAT'))): ?>
                        <input class="textbox" name="product[attributes][<?php echo $attribute->id; ?>][value]" type="text" id="product_attribute_<?php echo $attribute->id; ?>" value="<?php echo (isset($attribute->value)) ? $attribute->value->flat_value : ''; ?>" /> 

                        <?php if (count($attribute->type->units) > 0): ?>
                        <select name="product[attributes][<?php echo $attribute->id; ?>][unit]">
                            <?php foreach ($attribute->type->units as $unit): ?>
                            <option value="<?php echo $unit->id; ?>"<?php echo ($unit->id == $selected_unit_id) ? ' selected="selected"' : ''; ?>><?php echo $unit->abbreviation; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php endif; ?>
                    <?php elseif($attribute->type->data_type == 'BOOLEAN'): ?>
                        <input type="checkbox" name="product[attributes][<?php echo $attribute->id; ?>][value]" value="1" id="product_attribute_<?php echo $attribute->id; ?>" <?php echo (isset($attribute->value->flat_value) && $attribute->value->flat_value == 1) ? ' checked="checked"' : ''; ?> />
                    <?php elseif($attribute->type->data_type == 'VARCHAR'): ?>
                        <input class="textbox" name="product[attributes][<?php echo $attribute->id; ?>][value]" type="text" id="product_attribute_<?php echo $attribute->id; ?>" value="<?php echo (isset($attribute->value)) ? $attribute->value->flat_value : ''; ?>" /> 
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php $variant_key = 0; ?>

    <h3><?php echo __('Variants'); ?></h3>

    <table id="variants">
        <thead>
            <tr>
                <th><?php echo __('SKU'); ?></th>
                <th><?php echo __('Weight'); ?></th>
                <th><?php echo __('Price'); ?></th>
                <th><?php echo __('Stock'); ?></th>
                <?php foreach ($product->variantAttributes() as $attribute): ?>
                <th><?php echo $attribute->name; ?></th>
                <?php endforeach; ?>
                <th class="icon"><?php echo __('Delete'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $variants = $product->variants; ?>
            <?php if (count($variants) < 1) $variants[] = new ProductVariant(); ?>
            <?php foreach ($variants as $variant): ?>
            <tr>
                <?php if ($variant->id > 0): ?>
                <input type="hidden" name="variants[<?php echo $variant_key; ?>][id]" value="<?php echo $variant->id; ?>" />
                <?php endif; ?>
                <td><input class="textbox" type="text" name="variants[<?php echo $variant_key; ?>][sku]" value="<?php echo $variant->sku; ?>" /></td>
                <td><input class="textbox number" type="text" name="variants[<?php echo $variant_key; ?>][weight]" value="<?php echo $variant->weight; ?>" /> kg</td>
                <td><input class="textbox number" type="text" name="variants[<?php echo $variant_key; ?>][price]" value="<?php echo $variant->price(); ?>" /> EUR</td>
                <td><input class="textbox number" type="text" name="variants[<?php echo $variant_key; ?>][stock]" value="<?php echo $variant->stock; ?>" /></td>
                <?php foreach ($product->variantAttributes() as $attribute): ?>
                <?php
                $attribute->values = $attribute->findValuesByProductVariantId($variant->id);
                if (count($attribute->values) > 0) {
                    $attribute->value = $attribute->values[0];
                }
                else {
                    unset($attribute->value);
                }
                ?>
                <td>
                <input name="variants[<?php echo $variant_key; ?>][attributes][<?php echo $attribute->id; ?>][id]" type="hidden" value="<?php echo (isset($attribute->value)) ? $attribute->value->id : ''; ?>" />
                        
                <?php if (in_array($attribute->type->data_type, array('INT', 'FLOAT'))): ?>
                    <input class="textbox" name="variants[<?php echo $variant_key; ?>][attributes][<?php echo $attribute->id; ?>][value]" type="text" value="<?php echo (isset($attribute->value)) ? $attribute->value->flat_value : ''; ?>" /> 

                    <?php if (count($attribute->type->units) > 0): ?>
                    <select name="variants[<?php echo $variant_key; ?>][attributes][<?php echo $attribute->id; ?>][unit]">
                        <?php foreach ($attribute->type->units as $unit): ?>
                        <option value="<?php echo $unit->id; ?>"<?php echo ($unit->id == $selected_unit_id) ? ' selected="selected"' : ''; ?>><?php echo $unit->abbreviation; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php endif; ?>
                <?php elseif($attribute->type->data_type == 'BOOLEAN'): ?>
                    <input type="checkbox" name="variants[<?php echo $variant_key; ?>][attributes][<?php echo $attribute->id; ?>][value]" value="1" <?php echo (isset($attribute->value->flat_value) && $attribute->value->flat_value == 1) ? ' checked="checked"' : ''; ?> />
                <?php elseif($attribute->type->data_type == 'VARCHAR'): ?>
                    <input class="textbox" name="variants[<?php echo $variant_key; ?>][attributes][<?php echo $attribute->id; ?>][value]" type="text" value="<?php echo (isset($attribute->value)) ? $attribute->value->flat_value : ''; ?>" /> 
                <?php endif; ?>
                </td>
                <?php endforeach; ?>
                <td class="icon">
                    <a href="#" class="remove-variant">
                        <img width="16" height="16" title="<?php echo __('Delete'); ?>" alt="<?php echo __('Delete'); ?>" src="<?php echo CATALOG_IMAGES; ?>action-delete-16.png">
                    </a>
                </td>
            </tr>
            <?php $variant_key++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p><a class="add-variant" href="#"><?php echo __('Add a new variant'); ?> <img width="16" height="16" src="<?php echo CATALOG_IMAGES; ?>action-add-16.png" alt="Add"></a></p>

    
    
    <p class="buttons">
        <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save and Close'); ?>" />
        <input class="button" name="continue" type="submit" accesskey="e" value="<?php echo __('Save and Continue Editing'); ?>" />
        <?php echo __('or'); ?> <a href="<?php echo get_url('plugin/catalog/products'); ?>"><?php echo __('Cancel'); ?></a>
    </p>
</form>