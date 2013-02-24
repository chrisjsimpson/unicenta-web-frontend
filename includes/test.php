<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
</head>
<body>
<?php
include('global-functions.incl.php');
$dbc = mysqli_connect('localhost', 'unicenta', 'password', 'unicenta');
define('BASE_URL', 'http://example.com/');
getParentCategories($dbc);

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
?>
</body>
</html>
