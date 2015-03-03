<?php
	function add_map()
	{
		global $wpdb;
		$sql = "INSERT INTO ".$wpdb->prefix ."g_maps (name , type, zoom, center_lat, center_lng) VALUES ('New Map', 'ROADMAP', '2', '0', '0')";
		$wpdb->query($sql);
		$rowsldcc = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."g_maps ORDER BY id ASC");
		$last_key = key(array_slice($rowsldcc, -1, 1 , TRUE));
		foreach($rowsldcc as $key=>$rowsldccs)
		{
			if($last_key == $key)
			{
				if (headers_sent()) {
					die("Redirect failed.");
				}
				else{
					exit(header('Location: admin.php?page=hugeitgooglemaps_main&id='.$rowsldccs->id.'&task=edit_cat'));
				}
			}
		}
	}
	function remove_map($id)
	{
		global $wpdb;
		$removeMap = $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."g_maps WHERE id=%s",$id));
		$removeMarkers = $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."g_markers WHERE map=%s",$id));
		$removePolygons = $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."g_polygones WHERE map=%s",$id));
		$removePolylines = $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."g_polylines WHERE map=%s",$id));
		$removeCircles = $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."g_circles WHERE map=%s",$id));
		if($removeMap)
		{
			?>
			<div class="updated"><p><strong><?php _e('Item Deleted.' ); ?></strong></p></div>
			<?php
		}
	}
	
	function maps_js($id)
	{
		global $wpdb;
		$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."g_maps WHERE id=%s",$id);
		$map = $wpdb->get_results($sql);
		foreach($map as $map)
		{
			?>
				<script>
					var data;
					var marker = [];
					var infowindow =[];
					var polyline = [];
					var circle = [];
					var newcirclemarker = [];
					var geocoder;
					jQuery(document).ready(function(){
						
						
						
						jQuery("#map_name_tab").on("keyup change",function(){
							var name = jQuery(this).val();
							var data = {
								action : 'g_map_options',
								map_id: <?php echo $map->id; ?>,
								name : name,
							}
							jQuery.post("<?php echo admin_url( 'admin-ajax.php' ); ?>", data, function(response){
								if(response.success)
								{
									jQuery("#map_name").val(name);
								}
							},'json')
						})
						
						
						jQuery("#map_name").on("keyup change",function(){
							var name = jQuery(this).val();
							var data = {
								action : 'g_map_options',
								map_id: <?php echo $map->id; ?>,
								name : name,
							}
							jQuery.post("<?php echo admin_url( 'admin-ajax.php' ); ?>", data, function(response){
								if(response.success)
								{
									jQuery("#map_name_tab").val(name);
								}
							},'json')
						})
						
						
						loadMap();
						
						
						function loadMap()
						{
								
							data = {
								action:'g_map_options',
								map_id:<?php echo $map->id; ?>,
							}
							jQuery.post("<?php echo admin_url( 'admin-ajax.php' ); ?>",data,function(response){
								if(response.success)
								{
									var xml = jQuery.parseXML(response.success);
									//console.log(xml);
									var maps = xml.documentElement.getElementsByTagName("map");
									for(var i = 0; i < maps.length; i++)
									{
										var info_type = maps[i].getAttribute("info_type");
										var pan_controller = maps[i].getAttribute("pan_controller");
										var zoom_controller = maps[i].getAttribute("zoom_controller");
										var type_controller = maps[i].getAttribute("type_controller");
										var scale_controller = maps[i].getAttribute("scale_controller");
										var street_view_controller = maps[i].getAttribute("street_view_controller");
										var overview_map_controller = maps[i].getAttribute("overview_map_controller");
										var mapcenter = new google.maps.LatLng(
											parseFloat(maps[i].getAttribute("center_lat")),
											parseFloat(maps[i].getAttribute("center_lng")));
										
										
										geocoder = new google.maps.Geocoder();
										geocoder.geocode({'latLng':mapcenter},function(results, status){
											if (status == google.maps.GeocoderStatus.OK) {
												address = results[0].formatted_address;
												jQuery("#map_center_addr").val(address);
											}
										})	
										
										
										var mapOptions = {
											zoom: <?php echo $map->zoom; ?>,
											center: mapcenter,
											disableDefaultUI: true,
											/*panControl: pan_controller,
											zoomControl: zoom_controller,
											mapTypeControl: type_controller,
											scaleControl: scale_controller,
											streetViewControl: street_view_controller,
											overviewMapControl: overview_map_controller,*/
											mapTypeId: google.maps.MapTypeId.<?php echo $map->type; ?>,
										}
										
										map_admin_view = new google.maps.Map(document.getElementById('g_map_canvas'), mapOptions);
										
										
										var input = document.getElementById("map_center_addr");
										var autocomplete = new google.maps.places.Autocomplete(input);
										google.maps.event.addListener(autocomplete, 'place_changed', function(){
											
											var addr = jQuery("#map_center_addr").val();
											var geocoder = geocoder = new google.maps.Geocoder();
											//alert(addr);
											geocoder.geocode({'address':addr},function(results, status){
												if (status == google.maps.GeocoderStatus.OK) {
													address = results[0].geometry.location;
													map_admin_view.setCenter(address);
													jQuery("#map_center_lat").val(address.lat());
													jQuery("#map_center_lng").val(address.lng());
												}
											 })
										})
										
										
										
										
										if(pan_controller == "true")
										{
											map_admin_view.setOptions({
												panControl: true,
											})
										}
										else
										{
											map_admin_view.setOptions({
												panControl: false,
											})
										}
										if(zoom_controller == "true")
										{
											map_admin_view.setOptions({
												zoomControl: true,
											})
										}
										else
										{
											map_admin_view.setOptions({
												zoomControl: false,
											})
										}
										if(type_controller == "true")
										{
											map_admin_view.setOptions({
												mapTypeControl: true,
											})
										}
										else
										{
											map_admin_view.setOptions({
												mapTypeControl: false,
											})
										}
										if(scale_controller == "true")
										{
											map_admin_view.setOptions({
												scaleControl: true,
											})
										}
										else
										{
											map_admin_view.setOptions({
												scaleControl: false,
											})
										}
										if(street_view_controller == "true")
										{
											map_admin_view.setOptions({
												streetViewControl: true,
											})
										}
										else
										{
											map_admin_view.setOptions({
												streetViewControl: false,
											})
										}
										if(overview_map_controller == "true")
										{
											map_admin_view.setOptions({
												overviewMapControl: true,
											})
										}
										else
										{
											map_admin_view.setOptions({
												overviewMapControl: false,
											})
										}
										
										jQuery(".map_controller_input").on("click",function(){
											if(jQuery('#map_controller_pan').is(':checked'))
											{
												map_admin_view.setOptions({
													panControl: true,
												})
											}
											else
											{
												map_admin_view.setOptions({
													panControl: false,
												})
											}
											if(jQuery('#map_controller_zoom').is(':checked'))
											{
												map_admin_view.setOptions({
													zoomControl: true,
												})
											}
											else
											{
												map_admin_view.setOptions({
													zoomControl: false,
												})
											}
											if(jQuery('#map_controller_type').is(':checked'))
											{
												map_admin_view.setOptions({
													mapTypeControl: true,
												})
											}
											else
											{
												map_admin_view.setOptions({
													mapTypeControl: false,
												})
											}
											if(jQuery('#map_controller_scale').is(':checked'))
											{
												map_admin_view.setOptions({
													scaleControl: true,
												})
											}
											else
											{
												map_admin_view.setOptions({
													scaleControl: false,
												})
											}
											if(jQuery('#map_controller_street_view').is(':checked'))
											{
												map_admin_view.setOptions({
													streetViewControl: true,
												})
											}
											else
											{
												map_admin_view.setOptions({
													streetViewControl: false,
												})
											}
											if(jQuery('#map_controller_overview').is(':checked'))
											{
												map_admin_view.setOptions({
													overviewMapControl: true,
												})
											}
											else
											{
												map_admin_view.setOptions({
													overviewMapControl: false,
												})
											}
										})
										
										
										var markers = xml.documentElement.getElementsByTagName("marker");
										for(j = 0; j < markers.length; j++)
										{
											var name = markers[j].getAttribute("name");
											var address = markers[j].getAttribute("address");
											var anim = markers[j].getAttribute("animation");
											var description = markers[j].getAttribute("description");
											var markimg = markers[j].getAttribute("img");
											var img = new google.maps.MarkerImage(markimg,
											 new google.maps.Size(20, 20));
											var point = new google.maps.LatLng(
												parseFloat(markers[j].getAttribute("lat")),
												parseFloat(markers[j].getAttribute("lng")));
											var html = "<b>" + name + "</b> <br/>" + address;
											if(anim == 'DROP'){
												marker[j] = new google.maps.Marker({
												map: map_admin_view,
												position: point,
												title: name,
												content: description,
												animation: google.maps.Animation.DROP,
												});
											}
											if(anim == 'BOUNCE'){
												marker[j] = new google.maps.Marker({
												map: map_admin_view,
												position: point,
												title: name,
												content: description,
												animation: google.maps.Animation.BOUNCE
												});
											}
											if(anim == 'NONE'){
													marker[j] = new google.maps.Marker({
													map: map_admin_view,
													position: point,
													content: description,
													title: name,
												});
											}
											infowindow[j] = new google.maps.InfoWindow;

											bindInfoWindow(marker[j], map_admin_view, infowindow[j], description, info_type);
											
											jQuery("#map_infowindow_type").on("click",bindInfoWindow(marker[j], map_admin_view, infowindow[j], description, jQuery("#map_infowindow_type").val()));
											jQuery(".edit_list_delete_submit").on("click",function(){
												var parent = jQuery(this).parent();
												var typeelement = parent.find(".edit_list_delete_type");
												var type =typeelement.val();
												var parent = jQuery(this).parent();
												var idelement = parent.find(".edit_list_delete_id");
												var tableelement = parent.find(".edit_list_delete_table");
												var id=idelement.val();
												var table = tableelement.val();
												var li = jQuery(this).parent().parent().parent();
												var x = li.index();
												if(type=="marker")
												{
														marker[x].setMap(null);
														deleteItem(id,table,li,x);

												}
												return false;
											})
										}
									}
									var polygones = xml.documentElement.getElementsByTagName("polygone");
									for(var k = 0; k < polygones.length; k++)
									{
										
										var name = polygones[k].getAttribute("name");
										var new_line_opacity = polygones[k].getAttribute("line_opacity");
										var new_line_color = "#"+polygones[k].getAttribute("line_color");
										var new_fill_opacity = polygones[k].getAttribute("fill_opacity");
										var new_line_width = polygones[k].getAttribute("line_width");
										var new_fill_color = "#"+polygones[k].getAttribute("fill_color");
										var latlngs = polygones[k].getElementsByTagName("latlng");
										var hover_new_line_opacity=polygones[k].getAttribute("hover_line_opacity");
										var hover_new_line_color="#"+polygones[k].getAttribute("hover_line_color");
										var hover_new_fill_opacity=polygones[k].getAttribute("hover_fill_opacity");
										var hover_new_fill_color="#"+polygones[k].getAttribute("hover_fill_color");
										polygoncoords = [];
										for(var g = 0; g < latlngs.length; g++)
										{
											polygonpoints = new google.maps.LatLng(parseFloat(latlngs[g].getAttribute("lat")),
												parseFloat(latlngs[g].getAttribute("lng")))
											polygoncoords.push(polygonpoints)
										}
										//alert(polygoncoords);

										polygone[k] = new google.maps.Polygon({
											paths : polygoncoords,
											map: map_admin_view,
											strokeOpacity: new_line_opacity,
											strokeColor:new_line_color,
											strokeWeight:new_line_width,
											fillOpacity:new_fill_opacity,
											fillColor:new_fill_color,
											draggable:false,
										});

										
										jQuery(".edit_list_delete_submit").on("click",function(){
											var parent = jQuery(this).parent();
											var typeelement = parent.find(".edit_list_delete_type");
											var type =typeelement.val();
											var parent = jQuery(this).parent();
												var idelement = parent.find(".edit_list_delete_id");
												var tableelement = parent.find(".edit_list_delete_table");
												var id=idelement.val();
												var table = tableelement.val();
												var li = jQuery(this).parent().parent().parent();
												var x = li.index();
											if(type=="polygone")
											{
												
												
													polygone[x].setMap(null);
													deleteItem(id,table,li,x);
											}
											return false;
										})
										
										
									}
									var polylines = xml.documentElement.getElementsByTagName("polyline");
									for(var q = 0; q< polylines.length; q++)
									{
										var name = polylines[q].getAttribute("name");
										var line_opacity = polylines[q].getAttribute("line_opacity");
										var line_color = polylines[q].getAttribute("line_color");
										var line_width = polylines[q].getAttribute("line_width");
										var latlngs = polylines[q].getElementsByTagName("latlng");
										var newpolylinecoords =[];
										for(var g = 0; g < latlngs.length; g++)
										{
											polylinepoints = new google.maps.LatLng(parseFloat(latlngs[g].getAttribute("lat")),
												parseFloat(latlngs[g].getAttribute("lng")))
											newpolylinecoords.push(polylinepoints)
										}
										polyline[q] = new google.maps.Polyline({
											path:newpolylinecoords,
											map:map_admin_view,
											strokeColor:"#"+line_color,
											strokeOpacity:line_opacity,
											strokeWeight:line_width,
										})

										jQuery(".edit_list_delete_submit").on("click",function(){
											var parent = jQuery(this).parent();
											var typeelement = parent.find(".edit_list_delete_type");
											var type =typeelement.val();
											var parent = jQuery(this).parent();
											var idelement = parent.find(".edit_list_delete_id");
											var tableelement = parent.find(".edit_list_delete_table");
											var id=idelement.val();
											var table = tableelement.val();
											var li = jQuery(this).parent().parent().parent();
											var x = li.index();
											if(type == "polyline")
											{
													polyline[x].setMap(null);
													deleteItem(id,table,li,x);
												
											}
											return false;
										})
									}
									var circles = xml.documentElement.getElementsByTagName("circle");
									for(var u = 0; u< circles.length; u++)
									{
										var circle_name =circles[u].getAttribute("name");
										var circle_center_lat = circles[u].getAttribute("center_lat");
										var circle_center_lng = circles[u].getAttribute("center_lng");
										var circle_radius = circles[u].getAttribute("radius");
										var circle_line_width = circles[u].getAttribute("line_width");
										var circle_line_color = circles[u].getAttribute("line_color");
										var circle_line_opacity = circles[u].getAttribute("line_opacity");
										var circle_fill_color = circles[u].getAttribute("fill_color");
										var circle_fill_opacity = circles[u].getAttribute("fill_opacity");
										var circle_show_marker = parseInt(circles[u].getAttribute("show_marker"));
										circlepoint = new google.maps.LatLng(parseFloat(circles[u].getAttribute("center_lat")),
										parseFloat(circles[u].getAttribute("center_lng")));
										circle[u] = new google.maps.Circle({
											map:map_admin_view,
											center:circlepoint,
											title:name,
											radius:parseInt(circle_radius),
											strokeColor:"#"+circle_line_color,
											strokeOpacity:circle_line_opacity,
											strokeWeight:circle_line_width,
											fillColor:"#"+circle_fill_color,
											fillOpacity:circle_fill_opacity
										})

										
										jQuery(".edit_list_delete_submit").on("click",function(){
											var parent = jQuery(this).parent();
											var typeelement = parent.find(".edit_list_delete_type");
											var type =typeelement.val();
											var parent = jQuery(this).parent();
											var idelement = parent.find(".edit_list_delete_id");
											var tableelement = parent.find(".edit_list_delete_table");
											var id=idelement.val();
											var table = tableelement.val();
											var li = jQuery(this).parent().parent().parent();
											var x = li.index();
											if(type=="circle")
											{
											
													circle[x].setMap(null);
													deleteItem(id,table,li,x);
											}
											return false;
										})
										if(circle_show_marker == '1')
										{
											newcirclemarker[i] = new google.maps.Marker({
												position:circlepoint,
												map:map_admin_view,
												title:circle_name,
											})
										}
										//bindcircleinfowindow(newcirclemarker[i], map ,circleinfowindow[i], address);
										
									}
								}
							},"json")
						}
					})
					function bindInfoWindow(marker, map, infowindow, description, info_type)
					{
						google.maps.event.addListener(marker, 'click', function() {
							infowindow.setContent(description);
							infowindow.open(map, marker);
						});
					}
					function deleteItem(id,table,li,x)
					{
						var delete_data = {
							action:'g_map_options',
							id:id,
							table:table,
						}
						jQuery.post("<?php echo admin_url( 'admin-ajax.php' ); ?>", delete_data, function(response){
							if(response.success)
							{
								li.remove();
							}
						},"json")
							
					}
				</script>
			<?php
		}
	}
?>