/*!
 * JavaScript jauge.js
 * http://bl.ocks.org/tomerd/1499279
 *
 * Copyright 2019
 * 
 */						
var cpu_t;					/* température CPU */
var mem_totale;			/* Mémoire totale */
var mem_dispo;			/* Mémoire disponible */
var mem_utilisee		/* Mémoire % utilisée */

var hd_total;				/* Taille totale disque dur */
var hd_pourcent_utilise;		/* % utilisée disque dur */
				
			var gauges = [];
			
			function createGauge(name, label, min, max)
			{
				var config = 
				{
					size: 100,
					label: label,
					min: undefined != min ? min : 0,
					max: undefined != max ? max : 100,
					minorTicks: 5
				}
				
				var range = config.max - config.min;
				config.yellowZones = [{ from: config.min + range*0.75, to: config.min + range*0.9 }];
				config.redZones = [{ from: config.min + range*0.9, to: config.max }];
				
				gauges[name] = new Gauge(name + "GaugeContainer", config);
				gauges[name].render();
			}
			
			function createGauges()
			{
				createGauge("memory", "");
				createGauge("cpu", "");
				createGauge("hd", "");
			}
			
			function updateGauges()
			{
				for (var key in gauges)
				{
					var value;
					switch(key) {
							case "memory" : value = mem_utilisee;
															break;
							case "cpu" 		: value = cpu_t;
															break;
							case "hd" 		: value = hd_pourcent_utilise;
															break;
					}
					gauges[key].redraw(value);
				}
				
			}
		
			function initialize()
			{
				createGauges();
//				setInterval(updateGauges, 2000);
			}