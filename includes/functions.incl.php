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
  
  echo "\t<li><a href=\""; 
  echo BASE_URL . category_to_urlsafe($dbc, $category['ID']);
  echo '" title="Go to ' . category_to_urlsafe($dbc, $category['ID']) . ' section">' . $category['NAME'] . '</a>';
  
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
	echo "\n\t\t<li><a href=\"";  
	//add build link to this category
	echo category_to_urlsafe($dbc, $category['ID']) . '/'; //Top level link
	echo category_to_urlsafe($dbc, $firstSubcat['ID']); //Subcategory link
	echo '" title="Go to ' . category_to_urlsafe($dbc, $category['ID']) . ' ' . category_to_urlsafe($dbc, $firstSubcat['ID']) . ' section';
	echo '">';
	echo $firstSubcat['NAME'] . '</a>'; 

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
		  echo "\n\t<li>";
		  echo '<a href="' . category_to_urlsafe($dbc, $subcategory['ID']);
		  echo '">' . $subcategory['NAME'] . '</a></li>';
		}
	      echo "\n</ul>";
	    }else{
	      
		echo "</li>";
	    }
}//End load_subcategory($dbc, $id)


function getParentCategories($dbc, $id)
{
	/* 
		Returns an arry of parent category names and ids or false if no parent
	*/
	//Clean id
	$id = mysqli_real_escape_string($dbc, $id);

	$q = "SELECT NAME, PARENT, ID FROM CATEGORIES WHERE ID = '$id' AND PARENT IS NULL";
	$r = mysqli_query($dbc, $q);
	
	echo '<ul id="nav1">';
	while($category = mysqli_fetch_array($r))
	{
		$titleString = 'Go to ' . $category['NAME'] . ' ';
		$link = BASE_URL . $category['NAME'] . '/';

		echo '<li><a href="#" title="' . $titleString . '">' . $link . '</a>';

		while(hasSubCategories($dbc, $category['ID'])
		{
			hasSubCategories($dbc, $category['ID']);
		}
		echo "\n</ul>\n";
	}
	

	echo '</ul>';	
		
	
}//End getParentCategories

function hasSubCategories($dbc, $id)
{
	$q = "SELECT NAME FROM CATEGORIES WHERE PARENTID = '$id'";
	$r = mysqli_query($dbc, $q);

	echo "\n<ul>";

	while($category = mysqli_fetch_array($r))
	{
		echo "\n<li>" . $category['NAME'];	
		hasSubCategories($dbc, $category['ID'];
		echo "\n</ul>";
	}		

	return false;
}//End hasParent

function load_footer()
{

}
