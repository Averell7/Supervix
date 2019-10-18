<?php
/* -------------------------------------------------------------------- *\
		à insérer sur toutes les pages APRÈS l'include du fichier init.php ?
		
		3 versions de suppression de dossier : tests en cours
		car parfois le fichier 'temp' n'est pas supprimé
		A surveiller
\* -------------------------------------------------------------------- */


/* -------------------------------------------------------------------- *\
		Suppression du dossier temporaire et de tous ses fichiers
		Source :
		https://jeanbaptistemarie.com/notes/code/php/supprimer-un-dossier-avec-php.html
\* -------------------------------------------------------------------- */

/* -------------------------------------------------------------------- *\
		Solution 1
\* -------------------------------------------------------------------- */

function supprimerDossierTemporaire($dossier) {
	//$dossier = 'mon_dossier';
	$dir_iterator = new RecursiveDirectoryIterator($dossier);
	$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::CHILD_FIRST);

	// On supprime chaque dossier et chaque fichier	du dossier cible
	foreach($iterator as $fichier){
		 $fichier->isDir() ? @rmdir($fichier) : unlink($fichier);
	}

	// On supprime le dossier cible
	@rmdir($dossier);
}

/* -------------------------------------------------------------------- *\
		Solution 2
\* -------------------------------------------------------------------- */

function supprimerDossierTemporaire2($path) {
    $files = glob($path . '/*');
    foreach ($files as $file) {
        is_dir($file) ? supprimerDossierTemporaire2($file) : unlink($file);
    }
    @rmdir($path);
    return;
}

/* -------------------------------------------------------------------- *\
		Solution 3
		http://php.net/manual/fr/function.rmdir.php#117354
\* -------------------------------------------------------------------- */

function supprimerDossierTemporaire3($dir) { 
   if (is_dir($dir)) { 
     $objects = scandir($dir); 
     foreach ($objects as $object) { 
       if ($object != "." && $object != "..") { 
         if (filetype($dir."/".$object) == "dir") supprimerDossierTemporaire3($dir."/".$object); else unlink($dir."/".$object); 
       } 
     } 
     reset($objects); 
     @rmdir($dir); 
   } 
}

/* -------------------------------------------------------------------- *\
		Solution 4
		http://php.net/manual/fr/function.rmdir.php#118821
\* -------------------------------------------------------------------- */

function supprimerDossierTemporaire4($dir) {		//echo $dir;exit;
	if(PHP_OS == 'Linux') {
		//exec(sprintf("rm -rf %s", escapeshellarg($dir)));
		$commande = "sudo mv ".$dir." /dev/NULL";
	} else {
		//exec(sprintf("rd /s /q %s", escapeshellarg($dir)));
		$commande = "rd /s /q ".$dir;
	}
	//echo $commande;exit;
	echo shell_exec($commande);
}

// -------------------------------------------------------- Exécution :

$temp = substr(DIR_TEMP, 0, -1);
if(file_exists($temp)) {
	//$temp = substr(DIR_TEMP, 0, -1);	// 'temp'
	supprimerDossierTemporaire4($temp);
}

?>
