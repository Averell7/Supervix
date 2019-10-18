$(document).ready(function(){
	
	// Top 20
	$("#daily").on("click", function(e) {
    e.preventDefault();
    window.location="bandwidthd_jour.php";
  });

	$("#weekly").on("click", function(e) {
    e.preventDefault();
    window.location="bandwidthd_semaine.php";
  });

	$("#monthly").on("click", function(e) {
    e.preventDefault();
    window.location="bandwidthd_mois.php";
  });

	$("#yearly").on("click", function(e) {
    e.preventDefault();
    window.location="bandwidthd_annee.php";
  });
	
	// Subnet
	$("#daily_subnet").on("click", function(e) {
    e.preventDefault();
    window.location="bandwidthd_jour_subnet.php";
  });

	$("#weekly_subnet").on("click", function(e) {
    e.preventDefault();
    window.location="bandwidthd_semaine_subnet.php";
  });

	$("#monthly_subnet").on("click", function(e) {
    e.preventDefault();
    window.location="bandwidthd_mois_subnet.php";
  });

	$("#yearly_subnet").on("click", function(e) {
    e.preventDefault();
    window.location="bandwidthd_annee_subnet.php";
  });

// --------------------------------------------------------- 
  $("#deconnexion").on("click", function(e) {
    e.preventDefault();
    window.location="logout.php";
  });
// --------------------------------------------------------- 

	$("table a").on("click", function(e) {
		e.preventDefault();
		var ancre = $(this).attr('href');
		//var url = urlBase + ancre.substr(1);
		var url = urlBandwidthd + ancre.substr(1);
		
		var ref = ancre.substr(1, ancre.length-3);
		var codeGraphiques = "";
//		alert('urlBase : ' + urlBandwidthd);
		
		codeGraphiques += "<p class='legende'>"+ ref +"</p>";
		
		//codeGraphiques += '<img src='+ urlBase +'legend.gif alt="Legend" style="border:1px solid gray;" /><br />';
		codeGraphiques += '<img src='+ url +'-S.png alt="" class="graphique" /><br />';
		codeGraphiques += '<img src='+ url +'-R.png alt="" class="graphique" /><br />';

		$("#graphiques").html(codeGraphiques);
				
		$("table tbody tr:nth-child(even)").css({"background-color":"#FFFFE0"});
		$("table tbody tr:nth-child(odd)").css({"background-color":"#D3D3D3"});

		$(this).parent().parent().css( "background-color", "#C0E0FF" );
	});
// --------------------------------------------------------- 
	
	//$( "a[href='#Total-1']" ).trigger('click');
	//$( "tableau a:eq(1)" ).trigger('click');
	var z = $( "td>a:eq(0)" ).attr('href');		// 1ère ancre du tableau
	$( "a[href='" + z + "']" ).trigger('click');
//alert(z);

});

