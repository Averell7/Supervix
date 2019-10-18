<?php session_start();
//
//	Login form
//	==========
//
$fname = "$/settings/dbpsw.avm";
$fnum = fopen($fname,"r");
$password = fgets($fnum);
fclose($fnum);

//echo "Session referer 2 : ".	$_SESSION['referer'] ; exit;

//if ($_POST["Password"] == $password) {
if (password_verify($_POST["Password"], $password)) {
	//$_SESSION['Password'] = $password;
	$_SESSION['lastActivity'] = time();
	//echo "--- 3 --- <br />";exit;
	
	if( isset($_SESSION['referer']) ) {
		/*
		//echo "--- 4 --- <br />";exit;
		//echo "Session referer 4 : ".	$_SESSION['referer'] ; //exit;
		
		$path_parts = pathinfo($_SESSION['referer']);
		$dirname = $path_parts['dirname'];
		$dirname = str_replace('\\', '/', $dirname);
		if(	$dirname == '/') $dirname = '';
		//echo $dirname.$_SESSION['referer'];exit;
		header("location: ".$dirname.$_SESSION['referer']);
		*/
		header("location: ".$_SESSION['referer']);
		
	} else {
																										//echo "--- 5 --- <br />";exit;
		//echo "Session referer 5 : ".	$_SESSION['referer'] ; exit;
		session_unset();
		header("location: /index.php");
	}
} else {
																										//echo "--- 6 --- <br />";exit;
	session_unset();
	header("location: /index.php");
}
exit;
?>
