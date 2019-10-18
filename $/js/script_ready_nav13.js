/**
 *  script_ready_nav13.js
 */
 
$(document).ready(function() {
	
var time_start, dtUnix, x;

// à voir l'initialisation depuis init.php ?
var langue;
switch( Cookies.get('langue') ) {
	case "de_DE" : langue = "Deutsch";		break;
	case "en_US" : langue = "English";		break;
	case "es_ES" : langue = "Spanish";		break;
	case "it_IT" : langue = "Italian";		break;
	case "ko_KR" : langue = "Korean";			break;
	case "pt_PT" : langue = "Portuguese"; break;
	case "sl_SI" : langue = "Slovenian";	break;
	case "fr_FR" : 
	case "la_LA" : 
	default			 : langue = "French";
}


// --------------------------------------------------------- 


//var ip="192.168.1.14";
//var time_start = 12.32;


// --------------------------------------------------------- 

	$("#start").on("click", function() {
		var dt					= new Date();
				dtUnix			= dt.getTime() / 1000;
		var time = formatDate(dt, '', 0);	// ou pas 'utc'
		$("#time_start").val(time);
				time_start	= timeStringToFloat(time);
		//alert(time + '\n\n' + dt + '\n\n' + time_start + '\n\n' + dtUnix );
  });
	
	$("#stop").on("click", function(e) {
    e.preventDefault();
		/*
		var dt = new Date();
		var time = formatDate(dt, '', 1);	// ou pas 'utc'
		//time = "23:59";
		$("#time_stop").val(time);
		time_end = timeStringToFloat(time);
		*/
		//alert('Saisie manuelle ?');
		alert('Non actif.');
  });
	
	$("#proxy").on("click", function(e) {
    e.preventDefault();
		alert('Non actif.');
  });
	
	$("#firewall").on("click", function(e) {
    e.preventDefault();
		alert('Non actif.');
  });
	
	$("#start").trigger('click');
	
// --------------------------------------------------------- 

	$("#analyser").on("click", function() {
		/*
		var bouton = $(this).attr("id");
		var cmd = $(this).attr("cmd");
		cmd = cmd.replace(/_/g, " ");
		*/
		//var cmd 				= "squid-log.py";
		var ip					= $("#ip").val();
		var time_start 	= $("#time_start").val();
		var proxy 			= $("#proxy").is(':checked');
		var firewall		= $("#firewall").is(':checked');
		
		//var actualisation	= $( "#actual option:selected" ).val();
		
		//alert(proxy + " --- " + firewall);
		//if(time_start == "" || time_end == "") alert("Vérifier heure début-fin");
		
		if( time_start == "" ) {
			//alert("Vérifier l'heure de début");
			alert( alerte["Vérifier l'heure de début"] );
		/*
		} else if(time_end < 0 ) {
			alert("Vérifier l'heure de fin");
		} else if( time_end <= time_start){
			alert("Vérifier l'heure de début et de fin");*/
		} else {
			// On lance..
			/*
			alert("Commande py lancée : "
							+ "\navec les paramètres suivants : \n"
							+ "\n\t- ip : " + ip
							+ "\n\t- time_start : " + time_start
							+ "\n\t- time_end : " + time_end
							+ "\n\t- proxy : " + proxy
							+ "\n\t- firewall : " + firewall
							+ "\n\t- actualisation : " + actualisation
			);
			*/
			// https://www.xul.fr/ecmascript/settimeout.php
			//x = setInterval(ajaxCall, actualisation); // actualisation en secondes

			// interrompre :
			//clearInterval(x);
			//ajaxCall(ip, time_start, proxy, firewall);
			ajaxCall(ip, dtUnix, proxy, firewall);
		}
		
		
/*		
		if(isNaN(time_start) || isNaN(time_end) || time_start >= time_end) {
			alert("Vérifier heure début-fin");
		} else {

			alert("Commande py lancée : "
							+ "\navec les paramètres suivants : \n"
							+ "\n\t- ip : " + ip
							+ "\n\t- time_start : " + time_start
							+ "\n\t- time_end : " + time_end
							+ "\n\t- proxy : " + proxy
							+ "\n\t- firewall : " + firewall
							+ "\n\t- actualisation : " + actualisation
			);

			// https://www.xul.fr/ecmascript/settimeout.php
			x = setInterval(ajaxCall, actualisation); // actualisation en secondes
			*/
		
	});
	

	
	/* ------------------------------------------------------------ *\
		how do you set interval to ajax call in jquery
		https://makitweb.com/how-to-fire-ajax-request-on-regular-interval/
	\* ------------------------------------------------------------ */

	function ajaxCall(ip, dtUnix, proxy, firewall) {
			

		//alert("Il faut compléter en passant l'option : --ip=xxx.xxx.xxx.xxx");
			
		var demande = {
				url			: "ajax_analyse_blocage_sites.php",
				type		: "POST",
				dataType: 'html',
				data		: {	ip					:	ip,
										time_start	:	dtUnix,
										/*time_end		:	time_end,*/
										proxy				:	proxy,
										firewall		:	firewall
									},
				success	: function(retour){
										$(".cadrevisu").html(retour);
										//alert(retour);
										//afficheDataTable(retour) ;
									},
				error		: function(retour){
										alert( alerte["erreur_SP"]+' SP_22.' );
									}
		};
		$.ajax(demande);
	}
	
// --------------------------------------------------------- 

function formatDate(date,utc,plus) {
    utc = utc ? 'UTC' : '';
    var /*y = date['get'+utc+'FullYear'](),
        m = date['get'+utc+'Month']()+1,
        d = date['get'+utc+'Date'](),*/
        h = date['get'+utc+'Hours']() + plus,
        min = date['get'+utc+'Minutes'](),
				sec = date['get'+utc+'Seconds'](),
        str = '';
    /* 
    str += (m<10?'0'+m:m) + '/';
    str += (d<10?'0'+d:d) + '/';
    str += y + ' ';*/
    str += (h<10?'0'+h:h) + ':';
    str += (min<10?'0'+min:min);
    str +=  ':' + (sec<10?'0'+sec:sec);
    return str;
}
/*var parts = date_match_UTC.split('/');
var date_courante = new Date (parts[2],parts[0]-1,parts[1],heure_match_UTC,minute_match_UTC);
 
alert(formatDate(date_courante)); // Local
alert(formatDate(date_courante,true)); // UTC
*/

// --------------------------------------------------------- 
function timeStringToFloat(time) {
  var hoursMinutes = time.split(/[.:]/);
  var hours = parseInt(hoursMinutes[0], 10);
  var minutes = hoursMinutes[1] ? parseInt(hoursMinutes[1], 10) : 0;
  var secondes = hoursMinutes[2] ? parseInt(hoursMinutes[2], 10) : 0;
	//alert(hoursMinutes[0]+" --- " + hoursMinutes[1]+" --- " +hoursMinutes[2]);
  return hours + minutes / 60 + secondes / 3600;
}
// --------------------------------------------------------- 
  $("#deconnexion").on("click", function(e) {
    e.preventDefault();
    window.location="logout.php";
  });
// --------------------------------------------------------- 

/*

https://makitweb.com/how-to-fire-ajax-request-on-regular-interval/

how do you set interval to ajax call in jquery
setInterval(ajaxCall, 300000); //300000 MS == 5 minutes

function ajaxCall() {
    //do your AJAX stuff here
}
*/

});
