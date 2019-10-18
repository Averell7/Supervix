<?php
/* ----------------------------------------------------- *\
  - Réduction des multiples espaces consécutifs d'une chaîne
    en un seul espace
  - Suppression des espaces en début et fin de chaîne :
    $trim=true
\* ----------------------------------------------------- */

function trimSpaces($chaine, $trim=false) {
  if($trim) $chaine = trim($chaine);
  //return preg_replace('/\s{2,}/', ' ', $chaine);
  // ou
  return preg_replace('/\s\s+/', ' ', $chaine);
}
?>
