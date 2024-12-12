<option value="-1">Select</option>
<?php foreach ($projectActivities as $key => $value): ?>
    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php endforeach; ?>