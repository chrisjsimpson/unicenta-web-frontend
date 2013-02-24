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
/*
	This function (load_primary_navigation)
	is simply a wrapper function which calls
	getParentCategories($dbc), and in turn
	hasSubcategories($dbc)
*/
	getParentCategories($dbc);
}

function getParentCategories($dbc)
{
        /* 
                Returns an arry of parent category names and ids or false if no parent
        */

        $q = "SELECT NAME, ID FROM CATEGORIES WHERE PARENTID IS NULL";
        $r = mysqli_query($dbc, $q);

        echo '<ul id="nav1">';
        while($category = mysqli_fetch_array($r))
        {
                $title = 'Go to ' . $category['NAME'] . ' ';
                $link = BASE_URL . $category['NAME'] . '/';

                echo "<li><a href=\"$link\" title=\"" . $title . '">';
                echo $category['NAME'] . '</a>';

                while(hasSubCategories($dbc, $category['ID'], $title, $link))
                {
                        hasSubCategories($dbc, $category['ID'], $title, $link);
                }
                echo "\n</ul>\n";
        }


        echo '</ul>';


}//End getParentCategories

function hasSubCategories($dbc, $id, $title, $link)
{
        $q = "SELECT NAME, ID FROM CATEGORIES WHERE PARENTID = '$id'";
        $r = mysqli_query($dbc, $q);

        echo "\n<ul>";

        $preserveTitleBase = $title;

        while($category = mysqli_fetch_array($r))
       {
                $title = ' ' . $preserveTitleBase . '-> ' . $category['NAME'];
                echo "\n<li>";
                //Anchor
                echo '<a href="' . $link;
                echo category_to_urlsafe($dbc, $category['ID']) . '" ';
                //Title tag
                echo 'title="' . $title . '">';
                echo  $category['NAME'];
                //Close Anchor
                echo '</a>';
                hasSubCategories($dbc, $category['ID'], $title, $link);
                echo "\n</ul>";
        }

        return false;
}//End hasSubCategories


function load_footer()
{

}
