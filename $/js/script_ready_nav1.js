$(document).ready(function(){

// --------------------------------------------------------- 
  $("#i18n").on("change", function() {
		// Changer le cookie
		Cookies.set('langue', $(this).val(), { expires: 365, path: '/' });
		
		// Sauvegarde de la langue dans un fichier
		var demande = {
					url			: "ajax_ecrire_langue.php",
					type		: "POST",
          async 	: false,        /* On attend le retour */
					dataType: 'text',
					data		: {	langue	: $(this).val()
										},
					success	: function(retour){ },
					error		: function(retour){ alert('Erreur SP_17.'); }
					};
    $.ajax(demande);
		
		// Recharge de la page
		self.location['replace'](location);
  });

// --------------------------------------------------------- 
  $("#deconnexion").on("click", function(e) {
    e.preventDefault();
    window.location="logout.php";
  });
// --------------------------------------------------------- 
	
});
