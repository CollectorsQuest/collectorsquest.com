<?php
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'Please do not load this screen directly. Thanks!' );
}
/**
 * WooTransmitter Installer Class
 *
 * Provide a means of installing WooTransmitter.
 *
 * @package WordPress
 * @subpackage WooDojo
 * @category Core
 * @author WooThemes
 * @since 1.2.0
 */

if ( ! class_exists( 'WooTransmitter_Installer' ) ) {
class WooTransmitter_Installer {
    public $token = 'wootransmitter';
    public $api_url = 'http://www.woothemes.com/woo-dojo-api/';
    
    /**
     * Constructor.
     * @since  1.0.0
     * @return  void
     */
    public function __construct() {
        if ( class_exists( 'WooThemes_Transmitter' ) ) {} else {
            if ( is_admin() ) {
                add_action( 'init', array( &$this, 'hide_forever' ), 1 );
                add_action( 'admin_bar_menu', array( &$this, 'add_toolbar_menu' ), 1 );
                add_action( 'admin_print_styles', array( &$this, 'enqueue_styles' ) );
            }
            // Plugin upgrade hooks
            add_filter( 'plugins_api', array( &$this, 'plugins_api' ), 10, 3 );
        }
    } // End __construct()

    /**
     * Add our item to the WordPress Toolbar.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function add_toolbar_menu () {
        if ( class_exists( 'WooThemes_Transmitter' ) || get_user_setting( 'wootransmitterbridgehide' , '0' ) == '1' ) return;
        global $wp_admin_bar, $current_user;
        $is_downloaded = false;

        // If WooTransmitter is installed but not activated, switch from "install" to "activate" functionality.
        $plugins = array_keys( get_plugins() );
        foreach ( $plugins as $plugin ) {
            if ( strpos( $plugin, 'wootransmitter.php' ) !== false ) { $is_downloaded = true; }
        }
        // Stop if the user doesn't have access to install plugins
        if ( ! current_user_can( 'install_plugins' ) ) { return; }
        // Main Menu Item
        $wp_admin_bar->add_menu( array( 'parent' => 'top-secondary', 'id' => $this->token, 'title' => '<span class="message-count read off">' . __( 'Off', 'wootransmitter' ) . '</span>', 'href' => '#' ) );
        // Begin Sub-Menu
        $wp_admin_bar->add_group( array( 'parent' => $this->token, 'id' => $this->token . '-messages' ) );
        $wp_admin_bar->add_menu( array( 'parent' => $this->token . '-messages', 'id' => 'notifications-disabled', 'title' => '<span class="title">' . __( 'Notifications are disabled.', 'wootransmitter' ) . '</span>' ) );
        
        if ( $is_downloaded ) {
            $wp_admin_bar->add_menu( array( 'parent' => $this->token . '-messages', 'id' => 'notification-status', 'title' => '<span class="title"><a href="' . esc_url( $this->get_activate_url() ) . '">' . __( 'Activate WooTransmitter?', 'wootransmitter' ) . '</a> | <a href="' . esc_url( $this->get_hide_url() ) . '">' . __( 'Hide Forever?', 'wootransmitter' ) . '</a></span>' ) );
        } else {
            $wp_admin_bar->add_menu( array( 'parent' => $this->token . '-messages', 'id' => 'notification-status', 'title' => '<span class="title"><a href="' . esc_url( $this->get_install_url() ) . '">' . __( 'Install WooTransmitter?', 'wootransmitter' ) . '</a> | <a href="' . esc_url( $this->get_hide_url() ) . '">' . __( 'Hide Forever?', 'wootransmitter' ) . '</a></span>' ) );
        }
    } // End add_toolbar_menu()
    
    /**
     * Enqueue styles for the notification centre.
     * @since  1.0.0
     * @return void
     */
    public function enqueue_styles () {
        if ( class_exists( 'WooThemes_Transmitter' ) || get_user_setting( 'wootransmitterbridgehide' , '0' ) == '1' ) return;
?>
<style type="text/css">
#wpadminbar #wp-admin-bar-wootransmitter .ab-item { color: #333; text-shadow: none !important; padding: 0 6px !important; height: auto; text-align: right; }
#wpadminbar #wp-admin-bar-wootransmitter .ab-item .title { color: #666666; text-shadow: none !important; }
#wpadminbar #wp-admin-bar-wootransmitter #wp-admin-bar-notifications-disabled .ab-item { background: #FFF; text-align: right; width: 275px; }
#wpadminbar #wp-admin-bar-wootransmitter #wp-admin-bar-notification-status .ab-item a { display: inline; text-shadow: none !important; text-align: right; font-size: 11px !important; }
#wp-admin-bar-wootransmitter .message-count.read { color: #999; background-image: -ms-linear-gradient(bottom, #d3d3d3, #e7e7e7) !important; background-image: -moz-linear-gradient(bottom, #e7e7e7, #d3d3d3) !important; background-image: -webkit-gradient(linear, left bottom, left top, from(#d3d3d3), to(#e7e7e7)) !important; background-image: -webkit-linear-gradient(bottom, #e7e7e7, #d3d3d3) !important;background-image: linear-gradient(bottom, #d3d3d3, #e7e7e7) !important; -moz-box-shadow: inset 0 0 5px rgba(0,0,0,0.2); -webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.2); box-shadow: inset 0 0 5px rgba(0,0,0,0.2); padding: 2px 6px !important; margin: 0; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }
</style>
<?php
    } // End enqueue_styles()

    /**
     * Hide the WooTransmitter notification forever, if desired.
     * @since  1.0.0
     * @return void
     */
    public function hide_forever () {
        if ( is_user_logged_in() && isset( $_GET['wootransmitter-hide'] ) && ( $_GET['wootransmitter-hide'] == 'yes' ) && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'wootransmitter-hide' ) ) {
            set_user_setting( 'wootransmitterbridgehide', '1' );

            $redirect = remove_query_arg( array( 'wootransmitter-hide', '_wpnonce' ), $this->current_url() );

            wp_safe_redirect( $redirect ); exit;
        }
    } // End hide_forever()

    /**
     * Hook into the WordPress Plugins API.
     * @since  1.0.0
     * @param  object $api    The API
     * @param  string $action The action to execute
     * @param  object $args   Arguments to pass to the API
     * @return object         The resulting modified API object
     */
    public function plugins_api ( $api, $action, $args ) {
        if ( class_exists( 'WooThemes_Transmitter' ) || get_user_setting( 'wootransmitterbridgehide' , '0' ) == '1' ) return $api;

        // Stop if the user doesn't have access to install plugins
        if ( ! current_user_can( 'install_plugins' ) ) { return; }
        // Make sure we're in the right place and looking for the correct plugin
        if ( 'plugin_information' != $action || false !== $api || !isset( $args->slug ) || $this->token != $args->slug ) return $api;
        // Get the data for the upgrade
        $upgrade = $this->get_client_upgrade_data();
        if ( ! $upgrade ) return $api;
        
        $api = new stdClass();
        $api->name = __( 'WooTransmitter', 'wootransmitter' );
        $api->version = $upgrade['version'];
        $api->download_link = $upgrade['download_url'];
        return $api;
    } // End plugins_api()

    /**
     * Get the plugin's activation URL.
     * @since  1.0.0
     * @return string The WordPress-formatted activation URL for WooTransmitter.
     */
    private function get_activate_url () {
        return 'plugins.php?action=activate&plugin=' . urlencode( 'wootransmitter/wootransmitter.php' ) . '&plugin_status=all&paged=1&s&_wpnonce=' . urlencode( wp_create_nonce( 'activate-plugin_wootransmitter/wootransmitter.php' ) );
    } // End get_activate_url()

    /**
     * Get the plugin's install URL.
     * @since  1.0.0
     * @return string The WordPress-formatted install URL for WooTransmitter.
     */
    private function get_install_url () {
        return 'update.php?action=install-plugin&plugin=wootransmitter&_wpnonce=' . urlencode( wp_create_nonce( 'install-plugin_wootransmitter' ) );
    } // End get_install_url()

    /**
     * Get the URL for hiding them= menu.
     * @since  1.0.0
     * @return string The formatted URL for hiding the menu.
     */
    private function get_hide_url () {
        return wp_nonce_url( add_query_arg( 'wootransmitter-hide', 'yes', $this->current_url() ), 'wootransmitter-hide' );
    } // End get_hide_url()

    /**
     * Get the current URL.
     * @since  1.0.0
     * @return string The current URL.
     */
    private function current_url () {
        $ssl = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) ? 's' : '';
        $port = ( $_SERVER['SERVER_PORT'] != '80' ) ? ':' . $_SERVER['SERVER_PORT'] : '';
        return sprintf( 'http%s://%s%s%s', $ssl, $_SERVER['SERVER_NAME'], $port, $_SERVER['REQUEST_URI'] );
    } // End current_url()
    
    /**
     * Get the data for the WooTransmitter installation.
     * @since  1.0.0
     * @return array/boolean Either an array of data or false, if an error.
     */
    private function get_client_upgrade_data () {
        if ( class_exists( 'WooThemes_Transmitter' ) ) return;
        $info = get_site_transient( 'wootransmitter_client_upgrade' );
        if ( $info ) return $info;

        $url = $this->api_url;
        $args = array( 'timeout' => 30, 'body' => array( 'action' => 'plugininformation', 'plugin_name' => 'wootransmitter/wootransmitter.php', 'version' => '0.0' ) );
        $data = wp_remote_post( $url, $args );

        if ( ! is_wp_error( $data ) && 200 == $data['response']['code'] ) {
            if ( $info = json_decode( $data['body'], true ) ) {
                if ( isset( $info['payload'] ) ) {
                    $prepped_info = array();
                    if ( isset( $info['payload']['download_link'] ) ) {
                        $prepped_info['download_url'] = esc_url( $info['payload']['download_link'] );
                    }
                    if ( isset( $info['payload']['version'] ) ) {
                        $prepped_info['version'] = floatval( $info['payload']['version'] );
                    }

                    if ( isset( $prepped_info['download_url'] ) && isset( $prepped_info['version'] ) ) {
                        set_site_transient( 'wootransmitter_client_upgrade', $prepped_info, 60*60*24 );
                        return $prepped_info;
                    }
                }
            }
        }
        
        return false;
    } // End get_client_upgrade_data()
} // End Class

if ( class_exists( 'WooThemes_Transmitter' ) ) {} else { new WooTransmitter_Installer(); }
} // End IF Statement