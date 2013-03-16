<?php
/*
 * Takes the product id ($_GET['id'])
 * 	Gets the attribute set assigned to product
 * 
 *  Loads any existing product attributes / stock levels assigned to this product
 * 
 * 	Ask user to assign stock levels using product attributes sets
 * 
 * 	If no product set yet assigned, give link to set one.
 */

 $productId = cleanString($dbc, $_GET['id']);
 
 $productDetails = getProductDetails($dbc, $productId);
  
//Get attribute set for this product
$q = "SELECT ATTRIBUTESET_ID FROM PRODUCTS WHERE ID = '$productId' AND ATTRIBUTESET_ID IS NOT NULL";
$r = mysqli_query($dbc, $q);

if(mysqli_num_rows($r) == 1) //Got attribute set ID. Use this to show product variations options.
{
	list($attributeSetId) = mysqli_fetch_array($r);
	echo 'Product: ' . $productDetails['NAME'] . '<br />';
	echo 'Buy Price: &pound;' . $productDetails['PRICEBUY'] . '<br />';
	echo 'Base Sell Price: &pound;' . $productDetails['PRICESELL'] . '<br />';
	echo 'In category: ' . getCategoryName($dbc, $productDetails['CATEGORY']) . '<br />';
	echo '<img src="' . BASE_URL . 'includes/getImage.php?id=' . $productDetails['ID'];
	echo '" width="150" height="150"  />';
	
	//Load any existing variations of this product
	
	//End load any existing variations of this product
	
	//First get number of attributes. e.g Size & Colour = two attributes
		//Then, for each attribute echo drop-down of values
	
	//Query to get number of attributes:
	$q_getNumAttributes ='
	SELECT ATTRIBUTESET.ID AS "AttrSetId", 
	ATTRIBUTESET.NAME AS "Set Name",
	ATTRIBUTE.NAME AS "AttrName",
	ATTRIBUTE.ID AS "AttrId"
	FROM ATTRIBUTESET
	JOIN ATTRIBUTEUSE ON
	ATTRIBUTESET.ID = ATTRIBUTEUSE.ATTRIBUTESET_ID
	JOIN ATTRIBUTE ON
	ATTRIBUTEUSE.ATTRIBUTE_ID = ATTRIBUTE.ID
	WHERE ATTRIBUTESET.ID = "' . $attributeSetId . '" 
	GROUP BY ATTRIBUTESET.ID, ATTRIBUTE.NAME';
	
	$r_getNumAttributes = mysqli_query($dbc, $q_getNumAttributes);

	if(mysqli_num_rows($r_getNumAttributes) > 0)
	{
		//Begin chose attributes <form>
		echo "\n<form name=\"chooseAttributes\" method=\"post\" action=\"";
		echo ADMIN_BASE_URL . '?p=add-attributes&save' . '">';
		echo "\n<fieldset>";
		echo "\n<legend>Add Product attributes based on stock</legend>";
		//Description of forms purpose:
		echo "\n<p>Use this form to specify which vartiations of a product are in stock.\n
			  \n For example, A T-shirt in color red, size 'Large' stock level 40.</p>";
		
		
		//Echo each attribute into dropdowns and show their values to choose from
		while($attribute = mysqli_fetch_array($r_getNumAttributes))
		{
			echo "\n";
			echo $attribute['AttrName'] . ': ';
			echo '<select name="' . $attribute['AttrName'] . '[]" required>';
			
			//Promt input for user:
			echo "\n\t";
			echo '<option value="">Select value for ' . $attribute['AttrName'] . '</option>';
			//Get possible values for this attribute from database!
			$attributeValues =  getAttributeValuesFromAttrId($dbc, $attribute['AttrId']);
			
			//Echo each attribute value into <option> tag in dropdown
			foreach ($attributeValues as $key => $value) {
				echo "\n\t\t";
				echo '<option value="' . $key . '">' . $value .  '</option>';
			}
			
			echo "</select>\n";//End select list for attribute
		}//End echo each attribute into dropdowns showing their values
		
		//Echo stock & price input boxes for selected variation of product in stock:
		echo "\n";
		echo 'Price: &pound;<input type="text" name="price[]" required /> ';
		echo "\n";
		echo 'Stock: <input type="text" name="stock[]" required />';
		
		//Add another variation option (javascript?)
		echo '<br /><input type="button" name="addAnoth" value="Add another variation (Not yet implimented!)" />';
		echo '<br /><input type="submit" name="submit" value="Save" />';
		echo '<input type="hidden" name="submitted" value="TRUE" />';
		
		//End add attributes <form>
		echo "\n</fieldset>\n</form>";
		
	}else{	//End show attributes and values drop downs
		echo '<p>No Attributes found for attribute sets';
	}//End show error if no attributes for attributes set
	//Use attribute id to get variation options & let user state stock.
	$q = '
	SELECT ATTRIBUTESET.ID AS "AttrSetId", 
	ATTRIBUTESET.NAME AS "Set Name",
	ATTRIBUTE.NAME AS "Attr Name",
	VALUE
	FROM ATTRIBUTESET
	JOIN ATTRIBUTEUSE ON
	ATTRIBUTESET.ID = ATTRIBUTEUSE.ATTRIBUTESET_ID
	JOIN ATTRIBUTE ON
	ATTRIBUTEUSE.ATTRIBUTE_ID = ATTRIBUTE.ID
	JOIN ATTRIBUTEVALUE ON
	ATTRIBUTE.ID = ATTRIBUTEVALUE.ATTRIBUTE_ID
	WHERE ATTRIBUTESET.ID = "1b258e7e-bb69-482d-9597-a4ecf1f03cfb"
	GROUP BY ATTRIBUTE.NAME, VALUE';
	
	$r = mysqli_query($dbc, $q);
	
	//echo '<br />' . $q;
	
	//Echo each option into drop-down lists
	
}else{//Ask to assign an attribute set first
	echo '<p>Attribute set not assigned yet: Assign attribute set here.';
}//End echo link to assign attribute set to product if not set already.

