<?php
/**
 * Plugin Name: Alterly
 * Plugin URI: https://www.alterly.com/
 * Version: 1.0.3
 * Author: Alterly
 * Author URI: http://www.alterly.com/
 * Description: Let's you easily insert Altery snippet code in the header of your WordPress blog
 * License: GPL2
 */

/*  Copyright 2016 Alterly

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

/**
 * Alterly Class
 */
class Alterly 
{
	/**
	 * Constructor
	 */
	public function __construct() 
	{

		// Plugin Details
        $this->plugin               = new stdClass;
        $this->plugin->name         = 'alterly'; // Plugin Folder
        $this->plugin->displayName  = 'Alterly'; // Plugin Name
        $this->plugin->version      = '1.0.3';
        $this->plugin->folder       = plugin_dir_path(__FILE__);
        $this->plugin->url          = plugin_dir_url(__FILE__);
        $this->plugin->basename     = plugin_basename(__FILE__);
		
		// Hooks
		add_action('admin_init', array(&$this, 'registerSettings'));
        add_action('admin_menu', array(&$this, 'adminPanelsAndMetaBoxes'));
		add_filter('plugin_action_links_' . $this->plugin->basename, array(&$this, 'addActionLinks'));
        
        // Frontend Hooks
        add_action('wp_head', array(&$this, 'frontendHeader'));
	}
	
	/**
	 * Register Settings
	 */
	function registerSettings() 
	{
		register_setting($this->plugin->name, 'alterly_account_id', 'trim');
	}

	/**
	 * Plugin action links
	 * https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
	 */
	function addActionLinks($links) 
	{
		$alterly = array(
			'<a href="' . admin_url('options-general.php?page=alterly') . '">Settings</a>',
		);
		return array_merge($links, $alterly);
	}

	/**
     * Register the plugin settings panel
     */
    function adminPanelsAndMetaBoxes() 
    {
    	add_submenu_page('options-general.php', $this->plugin->displayName, $this->plugin->displayName, 'manage_options', $this->plugin->name, array(&$this, 'adminPanel'));
	}
    
    /**
     * Output the Administration Panel
     * Save POSTed data from the Administration Panel into a WordPress option
     */
    function adminPanel() 
    {
    	// Save Settings
        if (isset($_POST['submit'])) {

        	// trim whitespace
        	$id = trim($_POST['alterly_account_id']);

        	// Check nonce and validate account ID
        	if (!isset($_POST[$this->plugin->name.'_nonce'])) {
	        	// Missing nonce	
	        	$this->errorMessage = __('nonce field is missing. Settings NOT saved.', $this->plugin->name);
        	} elseif (!wp_verify_nonce($_POST[$this->plugin->name.'_nonce'], $this->plugin->name)) {
	        	// Invalid nonce
	        	$this->errorMessage = __('Invalid nonce specified. Settings NOT saved.', $this->plugin->name);
        	} elseif (empty($id)) {
        		// Invalid account ID
	        	$this->errorMessage = __('No account ID specified. See instructions below for getting your account ID from your account profile page on the Alterly website. Settings NOT saved.', $this->plugin->name);
	        // http://stackoverflow.com/questions/6772603/php-check-if-number-is-decimal
        	} elseif (!is_numeric($id)) {
        		// Invalid account ID
	        	$this->errorMessage = __('Invalid account ID specified. The account ID should be a number. Settings NOT saved.', $this->plugin->name);
        	} elseif (floor($id) != $id) {
        		// Invalid account ID
	        	$this->errorMessage = __('Invalid account ID specified. The account ID should be a number. Settings NOT saved.', $this->plugin->name);
        	} else {        	
	        	// Save
	    		update_option('alterly_account_id', $id);
				$this->message = __('Settings saved.', $this->plugin->name);
			}
        }
        
        // Get latest settings
        $this->settings = array(
        	'alterly_account_id' => stripslashes(get_option('alterly_account_id')),
        );
        
    	// Load Settings Form
        include_once(WP_PLUGIN_DIR.'/'.$this->plugin->name.'/views/settings.php');  
    }
    
    /**
	 * Loads plugin textdomain
	 */
	function loadLanguageFiles() 
	{
		load_plugin_textdomain($this->plugin->name, false, $this->plugin->name.'/languages/');
	}
	
	/**
	 * Outputs script / CSS to the frontend header
	 */
	function frontendHeader() 
	{
		$this->output('alterly_account_id');
	}
	
	/**
	 * Outputs the given setting, if conditions are met
	 *
	 * @param string $setting Setting Name
	 * @return output
	 */
	function output($setting) 
	{
		// Ignore admin, feed, robots or trackbacks
		if (is_admin() OR is_feed() OR is_robots() OR is_trackback()) {
			return;
		}
		
		// Get meta
		$meta = get_option($setting);
		if (empty($meta)) {
			return;
		}	
		if (trim($meta) == '') {
			return;
		}

		// http://stackoverflow.com/questions/6772603/php-check-if-number-is-decimal
		if (!is_numeric($meta)) {
			return;
		}
		if (floor($meta) != $meta) {
			return;
		}
		
		// Output
		echo sprintf('<script src="//code.alterly.com/%d.js"></script>', stripslashes($meta));
	}
}
		
$alterly = new Alterly();
