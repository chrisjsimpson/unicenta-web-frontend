<h2>Select a Category to delete:</h2>


<?php

$categories = getProductCategories($dbc);


echo "\n<ul>\n";

foreach($categories as $category)
{
	echo "<li><a href=\"" . ADMIN_BASE_URL . '?p=delete-category&delete&id=' . 
	$category['ID'] . '">' . $category['NAME'] . "</a></li>\n";
}//End echo each category into a list item

echo "\n</ul>";//End unordered list.
