<?php
$dbc = mysqli_connect('localhost', 'unicenta', 'password','unicenta');

$q_parentCategories = "
SELECT NAME, ID FROM CATEGORIES WHERE
PARENTID IS NULL";

$r_parentCategories = mysqli_query($dbc, $q_parentCategories);

echo '<ul>';
//Foreach category echo category header and sub categories
while($category = mysqli_fetch_array($r_parentCategories))
{
  
  echo '<li>' . $category['NAME'] . '</li>';
  
   //Get sub categories (if any)
    $q_getFirstSubcategories = '
    SELECT NAME, ID FROM CATEGORIES
    WHERE PARENTID = "' . $category['ID'] . '"' ;
     
    $r_getFirstSubcategories = mysqli_query($dbc, $q_getFirstSubcategories);

    if(mysqli_num_rows($r_getFirstSubcategories) > 0) //Echo first subcategory
    {
      echo '<ul>';
      while($firstSubcat =  mysqli_fetch_array($r_getFirstSubcategories))
      {
	echo '<li>' . $firstSubcat['NAME'] . '</li>'; 

	load_subcategory($dbc, $firstSubcat['ID']);
	 
      }//End echo each subcategory
      echo '</ul>';
    }//End echo if a subcategory exists.
    
}//Echo each category

echo '</ul>';


function load_subcategory($dbc, $id)
{
   $q = "SELECT NAME, ID FROM CATEGORIES WHERE
		  PARENTID = '$id'";
		  		  
	    $r = mysqli_query($dbc, $q);
	    
	    echo '<ul>';
	      while($subcategory = mysqli_fetch_array($r))
	      {
		echo '<li>' . $subcategory['NAME'] . '</li>';
	      }
	    echo '</ul>';		
}



/*
//Query all categories
$q_all_categories = "SELECT ID, NAME, PARENTID FROM CATEGORIES";
$r = mysqli_query($dbc, $q_all_categories);

$categories = array();

while($category = mysqli_fetch_array($r))
{
  //Check if it has a parent id
  if(is_null($category['PARENTID']))
  {
    //Add this category as top level category
   $categories['ID'] = $category['NAME']; 

  }else{//End if category parent is null

   //Check if category already exists in $categories array
    if(array_key_exists($category['NAME'], $categories))
    {
      //Add this category id as a sub to the $categories array
      $categories[$category['NAME']] = $category['NAME'];
    }//End check if key already exists in $categories array.
    
  }//End if category has a parent id.
  
}//End loop through all queries.
*/
//echo print_r($categories);