$(document).ready(function(){

	var fichiersZip = new Array();
	var numberOfChecked = 0;
	var totalCheckboxes = 0;
	
	$("input:checkbox").change(function() {
      
			var fic_backup = $(this).attr('id');

				numberOfChecked = $('input:checkbox:checked').length;
				totalCheckboxes = $('input:checkbox').length;
				//var numberNotChecked = totalCheckboxes - numberOfChecked;
				//var numberNotChecked = $('input:checkbox:not(":checked")').length;
	
				if (numberOfChecked == 0) {
					$("#_btnSupprime").html("");
				} else {
					
					$("#_btnSupprime").html("<input type='button' id='btnSupprime' value='Supprimer la sélection' />");
				}
  });
	
	//$("#btnSupprime").on("click", function() {	
	$("#_btnSupprime").on("click", "input", function() {	
		
		for(var k = 0; k < totalCheckboxes; k++) {
			if( $("#checkbox"+k).is(":checked") ) {
				fichiersZip.push( $("#checkbox"+k).val() );
			}
		}
		
				var demande = {
				url			: "ajax_config_backups_suppression.php",
				type		: "POST",
				data		: {	fichiersZip: fichiersZip
									},
				success	: function(retour){
										$(".colonne_1").html("<div class='message'><p><b>"+retour+"</b></p></div>");
									},
				error		: function(retour){ alert( alerte["erreur_SP"]+' SP_19.' ); }
				};
		$.ajax(demande);

  });
	
// --------------------------------------------------------- 
  $(".colonne_1").on("click", "#reload", function(e) {
		location.reload(true);
  });
// --------------------------------------------------------- 
  $("#deconnexion").on("click", function(e) {
    e.preventDefault();
    window.location="logout.php";
  });
// --------------------------------------------------------- 

});

