jQuery(document).ready(function(){
	
		var updated_div = jQuery(".updated");
		if(updated_div != undefined){
			setInterval(function(){
				jQuery(".updated").hide(500);
			},10000)
		}
	
		jQuery(".editing_heading").on('click',function(){
			//jQuery(this).find(".heading_arrow").html() == "after" ? jQuery(this).find(".heading_arrow").html('▼') : jQuery(this).find(".heading_arrow").html('▲');
			
			var parent = jQuery(this).parent();
			var content = parent.find(".edit_content");
			content.slideToggle(200);
		})
		
		jQuery(".editing_heading").toggle(function(){
				jQuery(this).find(".heading_arrow").html("▲")
			},function(){
				jQuery(this).find(".heading_arrow").html("▼")
			})
		
		jQuery("#marker_add_button").on("click",function(e){
			jQuery(this).hide("fast");
			jQuery("#g_maps > div").not("#g_map_marker").addClass("hide");
			jQuery("#g_map_marker").removeClass("hide");
			jQuery("#markers_edit_exist_section").hide(200);
			jQuery(".update_marker_list_item").hide(200);
			jQuery("#g_map_marker_options .hidden_edit_content").show(200);
			var center_lat = jQuery("#map_center_lat").val();
			var center_lng = jQuery("#map_center_lng").val();
			google.maps.event.trigger(map, 'resize');
			var map_center = new google.maps.LatLng(center_lat,center_lng);
			map.setCenter(map_center);
			if(newmarker)
			{
				newmarker.setMap(null);
			}
			return false;
		})
		
		jQuery("#cancel_marker, #back_marker").on("click", function(e){
			jQuery("#marker_add_button").show(200);
			jQuery("#g_maps > div").not("#g_map_canvas").addClass("hide");
			jQuery("#g_map_canvas").removeClass("hide");
			jQuery("#markers_edit_exist_section").show(200);
			jQuery(".update_marker_list_item").show(200);
			jQuery("#g_map_marker_options .hidden_edit_content").hide(200);
			return false;
		})
		
		jQuery("#cancel_edit_marker, #back_edit_marker").on("click",function(){
			jQuery("#marker_add_button").show(200);
			jQuery("#g_maps > div").addClass("hide");
			jQuery("#g_map_canvas").removeClass("hide");
			jQuery("#markers_edit_exist_section").show(200);
			jQuery(this).parentsUntil(".editing_section").find(".update_list_item").hide(200);
			jQuery("#marker_add_button").show(200);
			return false;
		})
		
		jQuery("#cancel_polygone, #back_polygone").on("click",function(e){
			jQuery("#polygon_add_button").show(200);
			jQuery("#g_maps > div").addClass("hide");
			jQuery("#g_map_canvas").removeClass("hide");
			jQuery("#polygone_edit_exist_section").show(200);
			jQuery("#g_map_polygone_options .hidden_edit_content").hide(200);
			return false;
		})
		
		jQuery("#cancel_edit_polygone, #back_edit_polygone").on("click",function(e){
			jQuery(".edit_polygone_list_delete a").show(200);
			jQuery("#g_maps > div").addClass("hide");
			jQuery("#g_map_canvas").removeClass("hide");
			jQuery("#polygone_edit_exist_section").show(200);
			jQuery(this).parent().parent().parent().parent().parent().find(".update_list_item").hide(200);
			jQuery("#polygon_add_button").show(200);
			return false;
		})
		jQuery("#polygon_add_button").on('click',function(e){
			jQuery(this).hide(100);
			jQuery("#g_maps > div").not("#g_map_polygon").addClass("hide");
			jQuery("#g_map_polygon").removeClass("hide");
			jQuery("#polygone_edit_exist_section").hide(200);
			jQuery("#g_map_polygone_options .hidden_edit_content").show(200);
			var center_lat = jQuery("#map_center_lat").val();
			var center_lng = jQuery("#map_center_lng").val();
			google.maps.event.trigger(mappolygone, 'resize');
			var map_center = new google.maps.LatLng(center_lat,center_lng);
			mappolygone.setCenter(map_center);
			jQuery("#polygone_coords").val("");
			if(newpolygon)
			{
				newpolygon.setMap(null);
				
				newpolygoncoords = [];
				for(var i = 0; i < polygonmarker.length ; i++)
				{
					polygonmarker[i].setMap(null);
				}
				polygonmarker = [];
			}
			return false;
		})
		
		jQuery("#cancel_polyline, #back_polyline").on("click",function(e){
			jQuery("#polyline_add_button").show(200);
			jQuery("#g_maps > div").addClass("hide");
			jQuery("#g_map_canvas").removeClass("hide");
			jQuery("#polyline_edit_exist_section").show(200);
			jQuery("#g_map_polyline_options .hidden_edit_content").hide(200);
			return false;
		})
		
		jQuery("#cancel_edit_polyline, #back_edit_polyline").on("click",function(e){
			jQuery(".edit_polyline_list_delete a").show(200);
			jQuery("#g_maps > div").addClass("hide");
			jQuery("#g_map_canvas").removeClass("hide");
			jQuery("#polyline_edit_exist_section").show(200);
			jQuery(this).parent().parent().parent().parent().parent().find(".update_list_item").hide(200);
			jQuery("#polyline_add_button").show(200);
			return false;
		})
		
		jQuery("#polyline_add_button").on('click',function(e){
			e.preventDefault;
			jQuery(this).hide("fast");
			jQuery("#g_maps > div").not("#g_map_polygon").addClass("hide");
			jQuery("#g_map_polyline").removeClass("hide");
			jQuery("#polyline_edit_exist_section").hide(200);
			jQuery("#g_map_polyline_options .hidden_edit_content").show(200);
			var center_lat = jQuery("#map_center_lat").val();
			var center_lng = jQuery("#map_center_lng").val();
			google.maps.event.trigger(mappolyline, 'resize');
			var map_center = new google.maps.LatLng(center_lat,center_lng);
			mappolyline.setCenter(map_center);
			jQuery("#polyline_coords").val("");
			if(newpolyline)
			{
				newpolyline.setMap(null);
				
				newpolylinecoords = [];
				for(var i = 0; i < polylinemarker.length ; i++)
				{
					polylinemarker[i].setMap(null);
				}
				polylinemarker = [];
			}
			return false;
		})
		
		jQuery("#cancel_circle, #back_circle").on("click",function(e){
			jQuery("#circle_add_button").show("fast");
			jQuery("#g_maps > div").addClass("hide");
			jQuery("#g_map_canvas").removeClass("hide");
			jQuery("#circle_edit_exist_section").show(200);
			jQuery("#g_map_circle_options .hidden_edit_content").hide(200);
			return false;
		})
		
		jQuery("#cancel_edit_circle, #back_edit_circle").on("click",function(e){
			jQuery("#g_maps > div").not("#g_map_polygon").addClass("hide");
			jQuery("#g_map_canvas").removeClass("hide");
			jQuery("#circle_edit_exist_section").show(200);
			jQuery(this).parent().parent().parent().parent().parent().find(".update_list_item").hide(200);
			jQuery("#circle_add_button").show(200);
		})
		
		jQuery("#circle_add_button").on("click",function(e){
			jQuery(this).hide("fast");
			jQuery("#g_maps > div").addClass("hide");
			jQuery("#g_map_circle").removeClass("hide");
			jQuery("#circle_edit_exist_section").hide(200);
			jQuery("#g_map_circle_options .hidden_edit_content").show(200);
			var center_lat = jQuery("#map_center_lat").val();
			var center_lng = jQuery("#map_center_lng").val();
			google.maps.event.trigger(mapcircle, 'resize');
			var map_center = new google.maps.LatLng(center_lat,center_lng);
			mapcircle.setCenter(map_center);
			if(newcircle)
			{
				newcircle.setMap(null);
				circlemarker.setMap(null);
				circlemarker ="";
				newcircle = "";
			}
			return false;
		})
		
		
		jQuery('#upload_marker_pic').click(function(e) {
	 
			alert("Custom Icons are disabled in free version. If you need those functionalityes, you need to buy the commercial version.");
			return false
		});	
		
		
		jQuery('#upload_edit_marker_pic').click(function(e) {
	 
			alert("Custom Icons are disabled in free version. If you need those functionalityes, you need to buy the commercial version.");
			return false
		});	
		jQuery(".marker_image_choose_button").on("click",function(){
			jQuery(this).parent().parent().find(".active").removeClass("active");
			jQuery(this).parent().addClass("active");
		})
		
		jQuery(".front_end_input_options").on("keyup change",function(){
			var width = parseInt(jQuery("#map_width").val())/2;
			var height = jQuery("#map_height").val();
			var border_radius = jQuery("#map_border_radius").val();
			jQuery(".g_map").css({width:width+"%", height:height+"px", borderRadius:border_radius+"px"})
		})
		
})
