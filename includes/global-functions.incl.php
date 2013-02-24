<?php
function report_errors($errors)
{
	echo '<ul>';
	foreach($errors as $error)
	{
		echo '<li>' . $error . '</li>';
	}
	echo '</ul>';
}

function cleanString($dbc, $string, $allowable_tags = null)
{
	//Remove any html
	$string = strip_tags($string, $allowable_tags);

	//Escape string for mysql insertion
	$string = mysqli_real_escape_string($dbc, $string);

	return $string;
}
