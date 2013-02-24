<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
</head>
<body>
<?php
$dbc = mysqli_connect('localhost', 'unicenta', 'password', 'unicenta');
define('BASE_URL', 'example.com');
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
                $titleString = 'Go to ' . $category['NAME'] . ' ';
                $link = BASE_URL . $category['NAME'] . '/';

                echo '<li><a href="#" title="' . $titleString . '">' . $link . '</a>';

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
                echo "\n<li>" . $category['NAME'];
                hasSubCategories($dbc, $category['ID']);
                echo "\n</ul>";
        }

        return false;
}//End hasSubCategories
?>
</body>
</html>
