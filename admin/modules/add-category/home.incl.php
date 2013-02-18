<h2>Add Category</h2>

<form name="add-category" id="add-category" action="<?php echo ADMIN_BASE_URL; ?>?p=add-category&save" method="POST">
<fieldset>
	<legend>Add new product category</legend>
	
	<label for="category-name">Category Name</label>
	<input type="text" name="category-name" required />

	Parent Category: 
	<select name="parent-category">
		<option value="0">-Optional-</option>
		<?php
		foreach(getProductCategories($dbc) as $key=>$category)
		{
			echo "<option value=\"{$category['ID']}\">{$category['NAME']}</option>";
		}
		?>
	</select>	
	<input type="hidden" name="submitted" value="TRUE" />
	<input type="submit" name="Submit" value="Save" />

</fieldset>
</form>
