jQuery(document).ready(function () {
  googleplusone_set_form();
  googleplusone_render_plus_one();
  // on location change
    jQuery('#button_location').change(function () {
    var location = jQuery("#button_location option:selected").val();
    if (location == "manual") {
      jQuery('#manual_instructions').show().animate({height:57}, 250);
    } else {
      jQuery('#manual_instructions').animate({height: 0}, 250, function() {
        jQuery('#manual_instructions').hide();
      });
    }
    if (location == 'left') {
      jQuery('#left_float_additional').find('div').show().animate({height:180}, 1000);
    } else {
      jQuery('#left_float_additional').find('div').animate({height: 0}, 1000, function() {
        jQuery('#left_float_additional').find('div').hide();
      });
    }
  });
  jQuery('#button_size').change(function () {
    googleplusone_render_plus_one();
  });
  jQuery('#include_count').change(function () {
    googleplusone_render_plus_one();
  });
});
function googleplusone_render_plus_one () {
  var button_size = jQuery("#button_size option:selected").val();
  if (jQuery("#include_count ").is(':checked')) {
    var include_count = 'true';
  } else {
    var include_count = 'false';	  	
  }
  try {
    gapi.plusone.render("preview_container", {"size": button_size, "count": include_count});
  } catch (Exception) {}
  var height = jQuery("#preview_container").height();
  var width = jQuery("#preview_container").width();
  if (height == 0) {
    height 	= 24;
    width	= 38;
  }
  var left 	= (100 - width)  / 2;
  var top 	= (100 - height) / 2;
  jQuery("#preview_container").css("left", left+'px');
  jQuery("#preview_container").css("top", top+'px');
}
function googleplusone_set_form () {
  jQuery("#button_size").val(GPO.button_size);
  if (GPO.include_count == 'true') {
    jQuery("#include_count ").attr('checked', true);
  }
  if (GPO.script_load == 'true') {
    jQuery("#script_load ").attr('checked', true);
  }
  if (GPO.show_on_home == 'true') {
    jQuery("#show_on_home ").attr('checked', true);
  }
  if (GPO.show_on_single == 'true') {
    jQuery("#show_on_single ").attr('checked', true);
  }
  if (GPO.top_space == '') {
    jQuery("#top_space").val('60%');
  } else {
    jQuery("#top_space").val(GPO.top_space);
  }
  if (GPO.left_space == '') {
    jQuery("#left_space").val('70px');
  } else {
    jQuery("#left_space").val(GPO.left_space);		
  }
  if (GPO.button_location != 'left') {
    jQuery('#left_float_additional').find('div').animate({height: 0}, 5, function() {
      jQuery('#left_float_additional').find('div').hide();
    });
  }
  if (GPO.button_location != 'manual') {
    jQuery('#manual_instructions').animate({height: 0}, 5, function() {
      jQuery('#manual_instructions').hide();
    });
  }
  jQuery("#button_location").val(GPO.button_location);
  jQuery("#position").val(GPO.position);
}
function googleplusone_fade_success () {
  jQuery('#setting-error-settings_updated').animate({opacity: 0, height: 0}, 'slow');
}
