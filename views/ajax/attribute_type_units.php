<?php if (count($attribute_type->units) > 0): ?>
<select name="attribute[default_unit_id]" id="attribute_default_unit_id">
    <?php foreach ($attribute_type->units as $unit): ?>
    <option value="<?php echo $unit->id; ?>"><?php echo strtolower($unit->name); ?></option>
    <?php endforeach; ?>
</select>
<?php endif; ?>