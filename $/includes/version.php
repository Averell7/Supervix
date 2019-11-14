<p style="margin:0;padding:0;float:left;">&copy; O. Cart. 2019</p>

<span style="display:inline-block;"><?php echo _("Version"); ?> 1.5.3 &bull; 29-10-2019</span>
 
<span style="display:inline-block;float:right;">
<?php
switch(PHP_OS) {
	case "WINNT"	: echo "Windows <img src='$/icones/windows.png' alt='' />"; break;
	case "Linux"	: echo "Linux <img src='$/icones/tux.png' alt='' />"; break;
	case "Darwin" : echo "Mac Os <img src='$/icones/mac.png' alt='' />"; break;
}
/*
function getSizePHP() {
 	switch(PHP_INT_SIZE) {
		case 4	: return '32';
		case 8	: return '64';
		case 16	: return '128';
	}
}
echo "OS Serveur: ".PHP_OS."<br />";
echo "Version PHP : ".PHP_VERSION." - ".getSizePHP()."-bits<br />";
*/
?>
</span>
