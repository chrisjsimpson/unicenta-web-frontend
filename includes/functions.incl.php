<?php
function load_header()
{
	echo "<header>\n";
	
	//Print site name in header
	echo '<span id="siteName">' . SITE_NAME . "</span>\n";
	
	echo "</header>\n";
}

function load_primary_navigation($dbc)
{
/*
	This function (load_primary_navigation)
	is simply a wrapper function which calls
	getParentCategories($dbc), and in turn
	hasSubcategories($dbc)
*/
	echo "\n<nav>";
	getParentCategories($dbc);
	echo "\n</nav>";
}

function getParentCategories($dbc)
{
        /* 
                Returns an arry of parent category names and ids or false if no parent
        */

        $q = "SELECT NAME, ID FROM CATEGORIES WHERE PARENTID IS NULL";
        $r = mysqli_query($dbc, $q);

	//Print link to basket
	echo '<a href="' . BASE_URL . 'Basket"><p class="myBasketLink">My Basket</p></a>';

        echo "\n" . '<ul id="nav1">';
        while($category = mysqli_fetch_array($r))
        {
                $parents = 'Go to ' . $category['NAME'] . ' ';
		//Echo hrefs for parents
                echo "<li><a href=\"";
		getCategoryString($dbc, $category['ID'], $title = null, $baseNameFlag = false, $prefix = rtrim(BASE_URL, '/'), $seperator = '/', $encodeSpaces = true);
		echo "\" title=\"" . $parents . '">';
                echo $category['NAME'] . '</a>';

                while(hasSubCategories($dbc, $category['ID']))
                {
                        hasSubCategories($dbc, $category['ID']);
                }
		
                echo "\n</ul>\n";
        }


        echo '</ul>';


}//End getParentCategories

function hasSubCategories($dbc, $id)
{
        $q = "SELECT NAME, ID FROM CATEGORIES WHERE PARENTID = '$id'";
        $r = mysqli_query($dbc, $q);

        echo "\n<ul>";

        while($category = mysqli_fetch_array($r))
       {
                echo "\n<li>";
                //Anchor
                echo '<a href="';
		getCategoryString($dbc, $category['ID'], $title = null, $baseNameFlag = false, $prefix = rtrim(BASE_URL, '/'), $seperator = '/', $encodeSpaces = 'yes');
                echo '" title="';
                //Title tag
                getCategoryString($dbc, $category['ID'], null, false, 'Go to:', ' -> ');
                echo  '">' . $category['NAME'];
                //Close Anchor
                echo '</a>';
                hasSubCategories($dbc, $category['ID']);
                echo "\n</ul>";
        }

        return false;
}//End hasSubCategories


function getCategoryString($dbc, $id, $title = null, $baseNameFlag = false, $prefix = null, $seperator = null, $encodeSpaces = false)
{
	if(!$baseNameFlag)
	{
	//Get name of own category
	$q = "SELECT NAME FROM CATEGORIES WHERE ID = '$id'";
	$r = mysqli_query($dbc, $q);

	list($name) = mysqli_fetch_array($r);
	   $title = $name . $title;
	}
	//Get name and ID of parent of $id (if exists)
	$q = "SELECT PARENTID FROM CATEGORIES WHERE ID = '$id'";
	$r = mysqli_query($dbc, $q);

	if(mysqli_num_rows($r) == 1)
	{
	//Get parent name from parentid
 	  list($id) = mysqli_fetch_array($r);
	$q = "SELECT NAME, ID FROM CATEGORIES WHERE ID = '$id'";
	$r = mysqli_query($dbc, $q);

	  list($parentName, $id) = mysqli_fetch_array($r);
	  $title = $parentName . $seperator  . $title;
	  getCategoryString($dbc, $id, $title, $baseNameFlag = true, $prefix, $seperator, $encodeSpaces);
	}else{
		
	$title = $prefix . $title;

		if($encodeSpaces)
		{
			echo str_replace(' ', '%20', $title);
		}else{
			echo  $title;
		}

	}//End if has parent
}

function load_footer()
{

}
