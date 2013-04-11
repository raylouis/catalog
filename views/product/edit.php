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
 * @version     0.1.0
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

    <?php
    $variants = $product->variants;
    //$variants[] = new ProductVariant;

    $variant_key = 0;
    ?>

    <?php foreach ($variants as $variant): ?>
    <h3>Variant <?php echo $variant_key + 1; ?></h3>
    <?php if ($variant->id > 0): ?>
    <input type="hidden" name="variants[<?php echo $variant_key; ?>][id]" value="<?php echo $variant->id; ?>" />
    <?php endif; ?>
    <table>
        <tbody>
            <tr>
                <td class="label"><label for="variant_sku"><?php echo __('SKU'); ?></label></td>
                <td class="field">
                    <input class="textbox" type="text" name="variants[<?php echo $variant_key; ?>][sku]" id="variant_sku" value="<?php echo $variant->sku; ?>" />
                </td>
            </tr>
            <tr>
                <td class="label"><label for="variant_weight"><?php echo __('Weight'); ?></label></td>
                <td class="field">
                    <input class="textbox number" type="text" name="variants[<?php echo $variant_key; ?>][weight]" id="variant_weight" value="<?php echo $variant->weight; ?>" /> kg
                </td>
            </tr>
            <tr>
                <td class="label"><label for="variant_price"><?php echo __('Price'); ?></label></td>
                <td class="field">
                    <input class="textbox number" type="text" name="variants[<?php echo $variant_key; ?>][price]" id="variant_price" value="<?php echo $variant->price(); ?>" /> EUR
                </td>
            </tr>
            <tr>
                <td class="label"><label><?php echo __('VAT'); ?></label></td>
                <td class="field">
                    <select name="variants[<?php echo $variant_key; ?>][vat_id]">
                        <?php foreach ($vats as $vat): ?>
                        <option value="<?php echo $vat->id; ?>"<?php if ($vat->id == $variant->vat_id): ?> selected="selected"<?php endif; ?>><?php echo $vat->name; ?> (<?php echo $vat->percentage; ?>%)</option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label"><label for="variant_stock"><?php echo __('Stock'); ?></label></td>
                <td class="field">
                    <input class="textbox number" type="text" name="variants[<?php echo $variant_key; ?>][stock]" id="variant_stock" value="<?php echo $variant->stock; ?>" />
                </td>
            </tr>
        </tbody>
        <tbody class="product_variant_attributes">
            <?php
            echo new View('../../plugins/catalog/views/ajax/product_attribute_selector', array(
                'category' => $product->category,
                'product_variant' => $variant,
                'variant_key' => $variant_key
            ));
            ?>
        </tbody>
    </table>

    <?php $variant_key++; ?>
    <?php endforeach; ?>
    
    <p class="buttons">
        <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save and Close'); ?>" />
        <input class="button" name="continue" type="submit" accesskey="e" value="<?php echo __('Save and Continue Editing'); ?>" />
        <?php echo __('or'); ?> <a href="<?php echo get_url('plugin/catalog/products'); ?>"><?php echo __('Cancel'); ?></a>
    </p>
</form>