<?php
function load_header()
{
	echo "<header>\n";
	
	//Print site name in header
	echo '<h1 id="siteName">' . SITE_NAME . "<h1>\n";
	
	echo "</header>\n";
}

function load_primary_navigation($dbc)
{
	$q_parentCategories = "
SELECT NAME, ID FROM CATEGORIES WHERE
PARENTID IS NULL";

$r_parentCategories = mysqli_query($dbc, $q_parentCategories);


//Begin html list of categories
echo "<ul id=\"primaryNav\">\n";
/* Foreach parent category echo category parent and check for sub categories.
    If a subcategory exists for the parent, echo this into the html list markup.
    
    Note- This is not a recursive solution, it only goes down three levels
    (Parent = level1, subcategory 1 = level2,  subcategory2 = level3)
*/
while($category = mysqli_fetch_array($r_parentCategories))
{
  
  echo "\t<li>" . $category['NAME'];
  
   //Get sub categories (if any)
    $q_getFirstSubcategories = '
    SELECT NAME, ID FROM CATEGORIES
    WHERE PARENTID = "' . $category['ID'] . '"' ;
     
    $r_getFirstSubcategories = mysqli_query($dbc, $q_getFirstSubcategories);

    if(mysqli_num_rows($r_getFirstSubcategories) > 0) //Echo first subcategory
    {
      echo "\n\t<ul>";
      while($firstSubcat =  mysqli_fetch_array($r_getFirstSubcategories))
      {
	echo "\n\t\t<li>" . $firstSubcat['NAME']; 

	load_subcategory($dbc, $firstSubcat['ID']);
	 
      }//End echo each subcategory
      echo "</ul>\n";
    }//End echo if a subcategory exists.
    
    echo "</li>\n";
    
}//Echo each category

echo '</ul>';

}//End load_primary_navigation($dbc)

function load_subcategory($dbc, $id)
{
   $q = "SELECT NAME, ID FROM CATEGORIES WHERE
		  PARENTID = '$id'";
		  		  
	    $r = mysqli_query($dbc, $q);
	    
	    if(mysqli_num_rows($r) > 0)
	    {
	    
	      echo "\n\t<ul>";
		while($subcategory = mysqli_fetch_array($r))
		{
		  echo "\n\t<li>" . $subcategory['NAME'] . '</li>';
		}
	      echo "\n</ul>";
	    }else{
	      
		echo "</li>";
	    }
}//End load_subcategory($dbc, $id)


function load_footer()
{

}
