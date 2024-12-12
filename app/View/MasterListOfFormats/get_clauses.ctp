<option value="-1">Select</option>
<?php foreach ($clauses as $key => $value): ?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php endforeach; ?>