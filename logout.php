<?php session_start();
	session_unset();
	
	setcookie("menu_section", 0, strtotime( '+1 years' ), "/" );
	setcookie("menu_item", -1, strtotime( '+1 years' ), "/" );	// Pour neutraliser la mise en valeur des items
	
	header("location: index.php");
?>
