<?php 
/*
Core logic to display social share icons at the required positions. 
*/
function google_plus_one_share_init () {
  // DISABLED IN THE ADMIN PAGES
  if (is_admin()) {
    return;
  }
  $script_load = get_option('rp_gpo_script_load');
  wp_enqueue_script('twitter_facebook_share_google_plus_script', 'http://apis.google.com/js/plusone.js', false, false, ($script_load === "true"));
}
function google_plus_one_contents ($content) {
  global $post;
  // determine filter tag presence
  $filter_tag = get_option('rp_gpo_filter_tag');
  if (strlen($filter_tag) > 0) {
    // extract reverse
    $reverse = false;
    if (strpos($filter_tag, '!') === 0) {
      $filter_tag = substr($filter_tag, 1);
      $reverse = true;
    }
    $post_tags = get_the_tags();
    $has_tag = false;
    foreach ($post_tags as $pt) {
      if ($filter_tag == $pt->name
          || $filter_tag == $pt->slug) {
        $has_tag = true;
        break;
      }
    }
    if (($has_tag && $reverse)
        || (!$has_tag && !$reverse)) {
      return $content;
    }
  }
  if (get_post_status($post->ID) == 'publish') {
    $output = rp_social_share();
  }
  if ((is_single() && (get_option('rp_gpo_show_on_single') == 'true'))
      || (is_home() && (get_option('rp_gpo_show_on_home') == 'true'))) {
    $position = get_option('rp_gpo_button_location');
    if ($position == 'top') {
      return  $output . $content;
    }
    if ($position == 'bottom') {
      return  $content . $output;
    }
    if ($position == 'left') {
      // only show with blog-post on single pages, else buttons get stacked over one-another
      if (is_home()) {
        add_action('wp_footer', 'rp_gpo_home_share');
        return  $content;
      } else {
        return  $output . $content;
      }
    }
    if ($position == 'both') {
      return  $output . $content . $output;
    }
  } else {
    return $content;
  }
}
// Function to display just one button on homepage
function rp_gpo_home_share () {
  $output = rp_social_share(network_site_url());
  echo $output;
}
// Function to manually display related posts.
function rp_gpo_share ($text = null, $url = null) {
  $output = rp_social_share($url, $text);
  echo $output;
}
function rp_social_share($url = null, $text = null) {
  if ($url == null) $url = get_permalink();

  //GET ARRAY OF STORED VALUES
  $button_size     =  get_option('rp_gpo_button_size');
  $include_count   =  get_option('rp_gpo_include_count');
  $button_location = get_option('rp_gpo_button_location');
  $button_style    =  get_option('rp_gpo_button_style');
  $script_load     =  get_option('rp_gpo_script_load');
  $top_space       =  get_option('rp_gpo_top_space');
  $left_space      =  get_option('rp_gpo_left_space');
  $position        =  get_option('rp_gpo_position');
  $show_on_home    =  get_option('rp_gpo_show_on_home');
  $show_on_single  =  get_option('rp_gpo_show_on_single');

  if ($left_space == '') {
    $left_space = '70px';
  }
  if ($top_space == '') {
    $top_space = '60%';
  }
  if ($button_size == 'tall') {
    $height = '60px';
  } else {
    $height = '18px';
  }

  if ($button_location == 'left'){
?>
<style type="text/css">
#leftcontainerBox {
  position: <?php echo $position; ?>;
  top: <?php echo $top_space; ?>;
  left: <?php echo $left_space; ?>;
}
</style>
<?php
    $output = '<div id="leftcontainerBox">';
    $output .= '
    <div class="buttons">
    <g:plusone href="' . $url . '" size="tall" count="'.$include_count.'"></g:plusone>
    </div>';
    $output .= '</div><div style="clear:both"></div>';
    return $output;
  }
  if (($button_location == 'top')
      || ($button_location == 'bottom')
      || ($button_location == 'manual')
      || ($button_location == 'both')) {
    $output = '<div id="bottomcontainerBox" class="' . $button_location . '">';
    $output .= '
<div class="buttons">
<g:plusone href="' . $url . '" size="'.$button_size.'" count="'.$include_count.'"></g:plusone>
</div>';
    $output .= '</div>' . $text . '<div style="clear:both"></div><div style="padding-bottom:2px;"></div>';
    return $output;
  }
}
