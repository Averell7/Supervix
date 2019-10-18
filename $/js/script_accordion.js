	// ----------------------------------------------------- Cookies -------------
	/*
	function getCookie(key) {
		//var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
		//return keyValue ? keyValue[2] : null;
		return Cookies.get(key);
	}
	*/
	/*
	function setCookie(key, value) {
		//document.cookie = key + '=' + value +';path=/'+ ';';
		Cookies.set(key, value, { expires: 365, path: '/' });
	}*/
	/*
	function deleteCookie( key ) {
		//document.cookie = key + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
		Cookies.remove(key);
	}*/
	// ---------------------------------------------------------------------------

	var nav_index_section = parseInt( Cookies.get('menu_section') );
	if (isNaN(nav_index_section) || nav_index_section == null || nav_index_section === undefined ) {
		nav_index_section = 0;
		// alert('nav_index_section 1 = ' + nav_index_section);
		//setCookie('menu_section', nav_index_section);
		Cookies.set('menu_section', nav_index_section, { expires: 365, path: '/' });
		Cookies.set('menu_item', -1, { expires: 365, path: '/' });
	}//alert('nav_index_section 2 = ' + nav_index_section);

	$( function() {
		$("#accordion").accordion({
			heightStyle: "content",
			active: nav_index_section
		});
	});

	var nav_index_item = parseInt( Cookies.get('menu_item') );
	if (nav_index_item == null) {
		nav_index_item = 1;
		// alert('nav_index_item = ' + nav_index_item);
		//setCookie('menu_item', nav_index_item);
		Cookies.set('menu_item', nav_index_item, { expires: 365, path: '/' });
	}

$(document).ready(function(){
	// Passage des éléments sélectionnés par cookies

	// Récupération de l'id de la section active
	var id = $('.ui-accordion-header-active').attr('id');
	// Mise en valeur de l'item
	if(nav_index_item >= 0) {
		$('#'+id+"+div li").eq(nav_index_item).css({"background-color":"#C0E0FF"});
	} else {
		$('#'+id+"+div li").css({"background-color":"#FFFFFF"});
	}

	$("#accordion").on("click", "li", function() {
		// index section du menu cliqué
		var nav_index_section = $("#accordion").accordion( "option", "active" );
		//setCookie('menu_section', nav_index_section);
		Cookies.set('menu_section', nav_index_section, { expires: 365, path: '/' });
		
		// index du <li> cliqué dans cette section		
		var nav_index_item = $(this).index();
		//setCookie('menu_item', nav_index_item);
		Cookies.set('menu_item', nav_index_item, { expires: 365, path: '/' });
	});

	$(".menu0").on("click", function() {
		//setCookie("menu_section", 0);
		Cookies.set('menu_section', 0, { expires: 365, path: '/' });
		//setCookie("menu_item", -1);	// Pour neutraliser la mise en valeur des items
		Cookies.set('menu_item', -1, { expires: 365, path: '/' });
    window.location="index.php";
  });

});


