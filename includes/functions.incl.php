<?php
function load_header()
{

	//Include header image
	if(file_exists(TEMPLATE_BASE_URI . 'images/' . HEADER_IMAGE))	{
		echo '<img src="' . TEMPLATE_BASE_URL . 'images/' .HEADER_IMAGE . '" />';
	}else{
		echo '<img src="http://placehold.it/800x150" />';
	}
}

function load_footer()
{

}
