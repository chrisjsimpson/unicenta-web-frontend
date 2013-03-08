<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="<?php
//Include stylesheet
echo TEMPLATE_BASE_URL . 'css/styles.css';
?>
" />
<title><?php echo $title ?></title>
<body>
<?php
//Load page header
load_header();

//Load primary navigation
load_primary_navigation($dbc);
