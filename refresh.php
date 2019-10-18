<?php

$content = time();
$fileopen = fopen("$/settings/subuser.txt",'w');
fwrite($fileopen, $content);	// Forcer fin de ligne LF
fclose($fileopen);

?>