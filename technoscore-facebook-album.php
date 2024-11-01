<?php
/*
Plugin Name: Technoscore Facebook Album
Plugin URI: http://nddw.com/demo3/sws-res-slider/
Description: This plugin adds a 'Add Facebook Album' tag in Post Content Editor of post/page to insert shortcode of facebook album in post content area to show on frontend.
Version:  1.0.0
Author: Technoscore
Author URI: http://www.technoscore.com/
Text Domain: techno_
*/
add_action('admin_menu', 'techno_facebook_album');
function techno_facebook_album() {

	//create new top-level menu
	add_menu_page('Facebook Album', 'Facebook Album', 'administrator', __FILE__, 'techno_facebook_album_page');
	
		//call register settings function
	add_action( 'admin_init', 'techno_facebook_album_register_settings' );
}

function techno_facebook_album_register_settings() {
	//register our settings
	register_setting( 'techno-settings-group', 'techno_facebook_album_id' );
	register_setting( 'techno-settings-group', 'techno_facebook_access_token' );
	
}

function techno_facebook_album_page() {
?>
<div class="wrap">
<h1>Facebook Album Integration</h1>
<form method="post" action="options.php">
    <?php settings_fields( 'techno-settings-group' ); ?>
    <?php do_settings_sections( 'techno-settings-group' ); ?>
    <table class="form-table">

		<tr valign="top">
        <th scope="row">Facebook Album ID</th>
        <td><input type="text" name="techno_facebook_album_id" class="regular-text code" value="<?php echo esc_attr( get_option('techno_facebook_album_id') ); ?>" />&nbsp; ex: example </td>
        </tr>  
	
			
		<tr valign="top">
        <th scope="row">Facebook Access Token</th>
        <td><input type="text" name="techno_facebook_access_token" class="regular-text code" value="<?php echo esc_attr( get_option('techno_facebook_access_token') ); ?>" />&nbsp; ex: xxxxxxxxxxxxxxx|xxxxxxxxxxxxxxxxxxxxxxxxxxx </td>
        </tr>  
		
		<tr valign="top">
        <th scope="row">Facebook Integration Shortcode</th>
        <td><label>[techno_facebook_album_init  fb_class ='add-your-custom-class-name-here']</label></td>
        </tr> 

    </table>
    <?php submit_button(); ?>
</form>
</div>

<?php } 

function techno_facebook_album_list($atts) {
$atts = shortcode_atts(
		array(
			'fb_class' => 'techno',
		), $atts, 'bartag' );
wp_enqueue_style( 'techno_facebook_album_css', plugin_dir_url( __FILE__ ) . 'assets/css/jquery.fb.albumbrowser.css' );
wp_enqueue_style( 'techno_facebook_album_account_css', plugin_dir_url( __FILE__ ) . 'assets/css/fb.css' );
wp_enqueue_script( 'techno_facebook_album_js', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.fb.albumbrowser.js' );
?>
<div class="fbtimelineWrap <? echo $atts['fb_class']; ?>">
	<div class="fb-album-container"></div>
</div>
  <script type="text/javascript">
        jQuery(document).ready(function () { 
          jQuery(".fb-album-container").FacebookAlbumBrowser({
                account: "<?php echo esc_attr( get_option('techno_facebook_album_id') ); ?>",
                accessToken: "<?php echo esc_attr( get_option('techno_facebook_access_token') ); ?>",
                skipAlbums: ["Profile Pictures"],
                lightbox: true,
				pluginImagesPath:'<? echo plugin_dir_url( __FILE__ ) . 'assets/images/' ?>', 
                 photoSelected: function (photo) {
					jQuery(".fb-preview-img-prev").attr("src", "<? echo plugin_dir_url( __FILE__ ) . 'assets/images/prev-icon.png' ?>");	
					jQuery(".fb-preview-img-next").attr("src", "<? echo plugin_dir_url( __FILE__ ) . 'assets/images/next-icon.png' ?>");	
                }			
            });		
        });
  </script>
			<?php
 }
add_shortcode( 'techno_facebook_album_init', 'techno_facebook_album_list' );

function techno_facebook_album_shortcode_button_script() 
{
    if(wp_script_is("quicktags"))
    {
        ?>
            <script type="text/javascript">
                
                //this function is used to retrieve the selected text from the text editor
                function getSel()
                {
                    var txtarea = document.getElementById("content");
                    var start = txtarea.selectionStart;
                    var finish = txtarea.selectionEnd;
                    return txtarea.value.substring(start, finish);
                }

                QTags.addButton( 
                    "code_shortcode", 
                    "Add Facebook Album", 
                    callback
                );

                function callback()
                {
                    /* var selected_text = getSel(); */
                    var selected_text = 'techno_facebook_album_init';
                    QTags.insertContent("[" +  selected_text + " fb_class ='add-your-custom-class-name-here']");
                }
            </script>
        <?php
    }
}

add_action("admin_print_footer_scripts", "techno_facebook_album_shortcode_button_script");