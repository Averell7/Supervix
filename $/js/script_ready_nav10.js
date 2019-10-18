$j142(document).ready(function(){
	// https://plaza.quickbox.io/t/update-chart-to-show-both-rx-tx-and-scale-to-show-load/705
  var options = {
			lines: { show: true },
			points: { show: false },	// à l'origine : true
			xaxis: { mode: "time" },
			yaxis: { min: 0 },
			legend:{ position: "nw" },
			colors: ["#6600AA", "#10D2E5"]
	};
	
	var data = [];
	var placeholder = $j142("#placeholder");

	$j142.plot(placeholder, data, options);

	var iteration = 0;

	function fetchData() {
			++iteration;

			function onDataReceived(series) {
				// we get all the data in one go, if we only got partial
				// data, we could merge it with what we already got
				data = series;
				var updateInterval = 2000;
				$j142.plot($("#placeholder"), data, options);
				fetchData();
			}

			$j142.ajax({
					url				: "ajax_data_bandwidth.php",
					method		: 'GET',
					dataType	: 'json',
					success		: onDataReceived
			});
			
			//setTimeout(fetchData, 2060);
	}

	setTimeout(fetchData, 2000);

	
// --------------------------------------------------------- 
  $("#deconnexion").on("click", function(e) {
    e.preventDefault();
    window.location="logout.php";
  });
// --------------------------------------------------------- 

});
