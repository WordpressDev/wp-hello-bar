<?php
/*
Plugin Name: WP Hello Bar
Plugin URI: http://andrewnorcross.com/plugins/wp-hello-bar
Description: Insert the HelloBar on your WordPress site
Version: 1.0
Author: norcross
Author URI: http://andrewnorcross.com
License: GPL v2

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


  
*/

// Start up the engine 
class WPHelloBar
{
    /**
     * Static property to hold our singleton instance
     * @var WPHelloBar
     */
    static $instance = false;


    /**
     * This is our constructor, which is private to force the use of
     * getInstance() to make this a Singleton
     *
     * @return WPHelloBar
     */
    private function __construct() {
        add_action      ( 'admin_menu',     array( $this, 'menu_settings' ) );
        add_action      ( 'admin_init',     array( $this, 'reg_settings'  ) );
        add_action      ( 'admin_head',     array( $this, 'css_head'      ) );
        add_action      ( 'wp_footer',      array( $this, 'hellobar'      ) );

    }

    /**
     * If an instance exists, this returns it.  If not, it creates one and
     * retuns it.
     *
     * @return WPHelloBar
     */
     
    public static function getInstance() {
        if ( !self::$instance )
            self::$instance = new self;
        return self::$instance;
    }



    /**
     * build out settings page and meta boxes
     *
     * @return WPHelloBar
     */

    public function menu_settings() {
        add_submenu_page('options-general.php', 'HelloBar Settings', 'HelloBar Settings', 'manage_options', 'hb-settings', array( $this, 'hb_settings_display' ));
    }

    /**
     * Register settings
     *
     * @return WPHelloBar
     */


    public function reg_settings() {
        register_setting( 'hb_options', 'hb_options');
    }

    /**
     * CSS in the head for the settings page
     *
     * @return WPHelloBar
     */

    public function css_head() { ?>
        <style type="text/css">

        div#icon-hb {
            background:url(<?php echo plugins_url('/lib/img/hellobar-icon.png', __FILE__); ?>) no-repeat 0 0!important;
        }
        
        </style>

    <?php }

    /**
     * Output the actual code
     *
     * @return WPHelloBar
     */
    public function hellobar() {

        if(is_user_logged_in())
            return;

        $hb_options = get_option('hb_options');

        if( empty($hb_options['digit_one']) || empty($hb_options['digit_two']) )
            return;

        echo '<script type="text/javascript" src="//www.hellobar.com/hellobar.js"></script>';
        echo '<script type="text/javascript">
                new HelloBar('.$hb_options['digit_one'].','.$hb_options['digit_two'].');
              </script>
              ';

    }


    /**
     * Display main options page structure
     *
     * @return WPHelloBar
     */
     
    public function hb_settings_display() { ?>
    
        <div class="wrap">
        <div class="icon32" id="icon-hb"><br></div>
        <h2><?php _e('HelloBar Settings') ?></h2>
        
            <div class="options">
            <p>First, log in to your HelloBar account and get the code provided. In the code, there should be two numbers (see example below)</p>
            <p><img alt="Script Example" title="Script Example" src="<?php echo plugins_url('/lib/img/script-example.jpg', __FILE__); ?>"></p>
            <p>Copy the two highlighted numbers into the fields below.</p>
                 
                <div class="fb_form_options">
                <form method="post" action="options.php">
                <?php
                settings_fields( 'hb_options' );
                $hb_options = get_option('hb_options');
                ?>

                <table class="form-table hb-table">
                <tbody>
                    <tr>
                        <th><label for="hb_options[digit_one]"><?php _e('First Number') ?></label></th>
                        <td>
                            <input type="text" class="small-text" value="<?php if(isset($hb_options['digit_one'] )) echo $hb_options['digit_one']; ?>" id="digit_one" name="hb_options[digit_one]">
                            <span class="description"><?php _e('The first number group from your HelloBar code') ?></span>
                        </td>
                    </tr>

                    <tr>
                        <th><label for="hb_options[digit_two]"><?php _e('Second Number') ?></label></th>
                        <td>
                            <input type="text" class="small-text" value="<?php if(isset($hb_options['digit_two'] )) echo $hb_options['digit_two']; ?>" id="digit_two" name="hb_options[digit_two]">
                            <span class="description"><?php _e('The second number group from your HelloBar code') ?></span>
                        </td>
                    </tr>

                </tbody>
                </table>        
    
                <p><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
                </form>
                </div>
    
            </div>
        
        </div>    
    
    <?php }

/// end class
}


// Instantiate our class
$WPHelloBar = WPHelloBar::getInstance();
