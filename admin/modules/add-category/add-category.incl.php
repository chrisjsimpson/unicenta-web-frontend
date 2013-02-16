<h1>Add Category</h1>

<form name="add-category" id="add-category" action="<?php echo ADMIN_BASE_URL; ?>?p=add-category&save" method="POST">
<fieldset>
	<legend>Add new product category</legend>
	
	<label for="category-name">Category Name</label>
	<input type="text" name="category-name" required />

	<select name="parent-category">
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
