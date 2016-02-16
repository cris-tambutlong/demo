<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $page_title ?></title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha256-7s5uDGW3AHqw6xtJmNNtr+OBRJUlgkNJEo78P4b0yRw= sha512-nNo+yCHEyn0smMxSswnf/OnX6/KwJuZTlNZBjauKhTK0c+zT+q5JOCx0UFhXQ6rJR9jg6Es8gPuD2uZcYDLqSw==" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css">
	<style>
		i.fa { font-size: 18px; }
		i.icon-spacer { padding-left: 20px; }
		i.indent-spacer { padding-left: 25px; }
		i.hyphen-spacer { padding-left: 15px; }
		.auto-width { 
			display:inline-block; 
			width: auto;
		}
		.footer-buttons { margin:-10px 0px 30px 0px; }
		.pad-top { padding-top: 10px; }
		.pad-bottom { padding-bottom: 20px; }
		.trash-bin-container { 
			padding-left: 30px; 
			color: #000!important;
		}
		.trash-bin { 
			font-size: 21px!important; 
			color: #777!important;
		}
		.head-caption { font-size: 16px; }
		.icon-cursor { cursor: pointer; }
		.disabled {
			color: #555;;
			background-color: #bbb;
		}
		.form {
			border: 7px solid #f7f7f9;
			width: 50%;
			padding: 20px;
			margin: 0 auto;
		}
		.validation-errors p { 
			padding: 4px;
			font-size: 90%;
			color: #c7254e;
			background-color: #f9f2f4;
			border-radius: 4px;
		}
	</style>
</head>
<body>