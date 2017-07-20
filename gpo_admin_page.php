<?php
/*
The main admin page for this plugin. The logic for different user input and form submittion is written here. 
*/
function google_plus_one_admin_menu () {
  $plugin_page = add_options_page(__('Google +1 settings', 'google_plus_one_button_by_kms'), __('Google +1', 'google_plus_one_button_by_kms'), 'administrator',
    'google-plus-one-share-button', 'google_plus_one_admin_page');
  add_action( "admin_print_scripts-$plugin_page", 'google_plus_one_admin_head' );
}
function google_plus_one_admin_head () {
  add_action('wp_print_scripts', 'enqueue_rp_gpo_scripts');
  $plugin_dir = trailingslashit(plugins_url(dirname(plugin_basename(__FILE__))));
  wp_enqueue_script('loadjs', $plugin_dir . 'gpo_admin.js');
  echo "<link rel='stylesheet' href='" . $plugin_dir . "gpo_admin.css' type='text/css' />\n";
}
function google_plus_one_admin_page () {
  if (! current_user_can('manage_options'))  {
  wp_die(__('You do not have sufficient permissions to access this page.'));
  }
  $update = false;
  if (isset($_POST['rp_gpo_submit'])
  && $_POST['rp_gpo_submit'] === "true") {
    if ($_POST['include_count'] === 'true') {
      $include_count = 'true';
    } else {
      $include_count = 'false';
    }
    if ($_POST['script_load'] === 'true') {
      $script_load = 'true';
    } else {
      $script_load = 'false';
    }
    if ($_POST['show_on_home'] === 'true') {
      $show_on_home = 'true';
    } else {
      $show_on_home = 'false';
    }
    if ($_POST['show_on_single'] === 'true') {
      $show_on_single = 'true';
    } else {
      $show_on_single = 'false';
    }
    update_option('rp_gpo_button_size', $_POST['button_size']);
    update_option('rp_gpo_include_count', $include_count);
    update_option('rp_gpo_button_location', $_POST['button_location']);
    update_option('rp_gpo_script_load', $script_load);
    update_option('rp_gpo_top_space', $_POST['top_space']);
    update_option('rp_gpo_left_space', $_POST['left_space']);
    update_option('rp_gpo_position', $_POST['position']);
    update_option('rp_gpo_show_on_home', $show_on_home);
    update_option('rp_gpo_show_on_single', $show_on_single);
    update_option('rp_gpo_filter_tag', $_POST['filter_tag']);
    $update = true;
  }
  wp_enqueue_script('jquery'); 
?>
<div class="wrap">
  <div class="icon32" id="icon-options-general"><br></div>
  <h2><?php _e('Google +1 (Plus One) settings', 'google_plus_one_button_by_kms'); ?></h2>
  <?php if ($update) { ?>
  <div id="setting-error-settings_updated" class="updated settings-error"><p><strong><?php _e('Settings saved.'); ?></strong></p></div>
  <?php } ?>
  <div id="preview_wrapper">
    <div id="preview_area">
      <div id="preview_container"></div>
    </div>
  </div>
  <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    <input type="hidden" name="rp_gpo_submit" value="true"> 
    <table class="form-table">
      <tr valign="top">
        <th scope="row">
          <label for="posts_per_page"><?php _e("The button's", 'google_plus_one_button_by_kms'); ?></label>
        </th>
        <td>
          <p>
            <label>
              ... <?php _e('size', 'google_plus_one_button_by_kms'); ?>:
              <select name="button_size" id="button_size">
                <option value="standard"><?php _e('normal', 'google_plus_one_button_by_kms'); ?>&nbsp;</option>
                <option value="small"><?php _e('small', 'google_plus_one_button_by_kms'); ?>&nbsp;</option>
                <option value="medium"><?php _e('middle', 'google_plus_one_button_by_kms'); ?>&nbsp;</option>
                <option value="tall"><?php _e('tall', 'google_plus_one_button_by_kms'); ?>&nbsp;</option>
              </select>
            </label>
          </p>
          <p>
            <label>
              ... <?php _e('location', 'google_plus_one_button_by_kms'); ?>:
              <select name="button_location" id="button_location">
                <option value="top"><?php _e('above the post', 'google_plus_one_button_by_kms'); ?>&nbsp;</option>
                <option value="bottom"><?php _e('below the post', 'google_plus_one_button_by_kms'); ?>&nbsp;</option>
                <option value="both"><?php _e('above AND below', 'google_plus_one_button_by_kms'); ?>&nbsp;</option>
                <option value="left"><?php _e('floating on left', 'google_plus_one_button_by_kms'); ?>&nbsp;</option>
                <option value="manual"><?php _e('placed manually', 'google_plus_one_button_by_kms'); ?>&nbsp;</option>
              </select>
            </label>
          </p>
          <p>
            <ul>
              <li><em><?php _e('If you select left floating location, please revise additional information displayed below.', 'google_plus_one_button_by_kms'); ?></em></li>
              <li id="manual_instructions"><em><?php echo sprintf(__("Insert the %s code into your theme where you'd like to see the button", 'google_plus_one_button_by_kms'), highlight_string('<?php rp_gpo_share([text_to_display_beside_button][, url_to_link_to]); ?>', true)); ?></em></li>
            </ul>
          </p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="posts_per_page"><?php _e('Display', 'google_plus_one_button_by_kms'); ?></label>
        </th>
        <td>
          <p>
            <label>
              <input type="checkbox" name="include_count" id="include_count" value="true" />
              <?php _e('with counter', 'google_plus_one_button_by_kms'); ?>
            </label>
          </p>
          <p>
            <label>
              <input type="checkbox" name="show_on_home" id="show_on_home" value="true" />
              <?php _e('on first page', 'google_plus_one_button_by_kms'); ?>
          </label>
          </p>
          <p>
            <label>
              <input type="checkbox" name="show_on_single" id="show_on_single" value="true" />
              <?php _e('besides posts', 'google_plus_one_button_by_kms'); ?>
            </label>
          </p>
          <p>
            <label>
              ... <?php _e('filtered on', 'google_plus_one_button_by_kms'); ?>:
              <input type="text" name="filter_tag" id="filter_tag" value="<?php echo get_option('rp_gpo_filter_tag'); ?>" />
              <?php _e('List the name or slug of the tag you want to filter on. Reverse filter can be prefixed with \'!\'', 'google_plus_one_button_by_kms'); ?>
            </label>
          </p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="posts_per_page"><?php _e('JavaScript SEO', 'google_plus_one_button_by_kms'); ?></label>
        </th>
        <td>
          <p>
            <label>
              <input type="checkbox" name="script_load" id="script_load" value="true" />
              <?php _e('at the bottom of the page in the footer section', 'google_plus_one_button_by_kms'); ?>
            </label>
          </p>
        </td>
      </tr>
      <tr valign="top" id="left_float_additional">
        <th scope="row">
          <label for="posts_per_page"><?php _e("Floating button's", 'google_plus_one_button_by_kms'); ?></label>
        </th>
        <td>
          <p>
            <label>
              ... <?php _e('spacing on top', 'google_plus_one_button_by_kms'); ?>:
              <input type="text" name="top_space" id="top_space" size="10" />
            </label>
          </p>
          <p>
            <ul>
              <li><em><?php echo sprintf(__("The Button's spacing from top. For example: %s", 'google_plus_one_button_by_kms'), highlight_string('60%', true)); ?></em></li>
            </ul>
          </p>
          <p>
            <label>
              ... <?php _e('spacing left', 'google_plus_one_button_by_kms'); ?>:
              <input type="text" name="left_space" id="left_space" size="10" />
            </label>
          </p>
          <p>
            <ul>
              <li><em><?php echo sprintf(__("The Button's spacing from the left side. For example: %s", 'google_plus_one_button_by_kms'), highlight_string('70px', true)); ?></em></li>
            </ul>
          </p>
          <p>
            <label>
              ... <?php _e('position', 'google_plus_one_button_by_kms'); ?>:
              <select name="position" id="position">
                <option value="fixed"><?php _e('Fixed', 'google_plus_one_button_by_kms'); ?>&nbsp;</option>
                <option value="absolute"><?php _e('Absolut', 'google_plus_one_button_by_kms'); ?>&nbsp;</option>
              </select>
            </label>
          </p>
        </td>
      </tr>
    </table>
    <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
  </form>		
</div>

<script>
jQuery(document).ready(function () {
<?php if ($update) { ?>	setTimeout(googleplusone_fade_success, 5000);<?php } ?>
});
var GPO = {};
GPO.button_size     = '<?php echo get_option('rp_gpo_button_size'); ?>';
GPO.include_count   = '<?php echo get_option('rp_gpo_include_count'); ?>';
GPO.button_location = '<?php echo get_option('rp_gpo_button_location'); ?>';
GPO.script_load     = '<?php echo get_option('rp_gpo_script_load'); ?>';
GPO.top_space       = '<?php echo get_option('rp_gpo_top_space'); ?>';
GPO.left_space      = '<?php echo get_option('rp_gpo_left_space'); ?>';
GPO.position        = '<?php echo get_option('rp_gpo_position'); ?>';
GPO.show_on_home    = '<?php echo get_option('rp_gpo_show_on_home'); ?>';
GPO.show_on_single  = '<?php echo get_option('rp_gpo_show_on_single'); ?>';
GPO.filter_tag      = '<?php echo get_option('rp_gpo_filter_tag'); ?>';
</script>
<?php }
