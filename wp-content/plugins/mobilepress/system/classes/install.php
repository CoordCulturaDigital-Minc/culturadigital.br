<?php
if ( ! class_exists('MobilePress_install'))
{
	/**
	 * Class that deals with installing the MobilePress plugin
	 *
	 * @package MobilePress
	 * @since 1.0
	 */
	class MobilePress_install {
		
		/**
		 * Start the installation process
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		function MobilePress_install()
		{	
			// If MobilePress table exists then try upgrade, otherwise create it and add defaults (cant use CheckTable() in functions.php in this code)
			if (mopr_check_table_exists())
			{
				$this->upgrade();
			}
			else
			{
				$this->setup_table();
				$this->add_defaults();
			}
		}
		
		/**
		 * Inserts default values into the MobilePress database
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		function add_defaults()
		{
			global $wpdb;
			
			// Add default options
			$sql = "INSERT INTO " . MOPR_TABLE . " (
						option_name,
						option_value,
						option_value_2
					)
					VALUES
						('version', '" . MOPR_VERSION . "', ''),
						('title', '', ''),
						('description', '', ''),
						('themes_directory', 'mobile-themes', ''),
						('default_theme', 'default', 'Default'),
						('iphone_theme', 'iphone', 'iPhone'),
						('force_mobile', '0', ''),
						('aduity_account_public_key', '', ''),
						('aduity_site_public_key', '', ''),
						('aduity_ads_enabled', '0', ''),
						('aduity_debug_mode', '0', ''),
						('aduity_ads_location', '0', '')
					";
			
			$results = $wpdb->query($sql);
		}
		
		/**
		 * Creates the MobilePress Table
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		function setup_table()
		{
			$sql	= "
						CREATE TABLE " . MOPR_TABLE . " (
							id mediumint(9) NOT NULL AUTO_INCREMENT,
							option_name VARCHAR(100) NOT NULL,
							option_value VARCHAR(100) NOT NULL,
							option_value_2 VARCHAR(100) NOT NULL,
						UNIQUE KEY id (id))
						ENGINE = MYISAM 
						CHARACTER SET utf8 
						COLLATE utf8_unicode_ci;
					";
					
			// Require upgrade.php from the CORE
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
		
		/**
		 * Upgrades the plugin database if it is outdated
		 *
		 * @package MobilePress
		 * @since 1.0.2
		 */
		function upgrade()
		{	
			global $wpdb;
			
			if (MOPR_DBVERSION < '1.0.2')
				$this->upgrade_102();
			
			if (MOPR_DBVERSION < '1.1')
				$this->upgrade_110();
			
			if (MOPR_DBVERSION < '1.1.1')
				$this->upgrade_111();
				
			if (MOPR_DBVERSION < '1.1.5')
				$this->upgrade_115();
			
			// Change the version in the database to the latest version after upgrade
			$wpdb->query(
				$wpdb->prepare("
					UPDATE
						" . MOPR_TABLE . "
					SET
						option_value = '%s'
					WHERE
						option_name = 'version'
					", MOPR_VERSION)
				);
		}
		
		/**
		 * Critical upgrades for plugin version 1.0.2 and lower
		 *
		 * @package MobilePress
		 * @since 1.0.2
		 */
		function upgrade_102()
		{
			global $wpdb;
			
			// First delete any duplicate DB entries that may have been caused by a bug prior to version 1.0.2
			$sql	= "DELETE FROM " . MOPR_TABLE . " WHERE id > 11";
			$delete	= $wpdb->query($sql);
			
			// Reset auto increment
			$sql	= "ALTER TABLE " . MOPR_TABLE . " AUTO_INCREMENT = 1";
			$reset	= $wpdb->query($sql);
		}
		
		/**
		 * Upgrades for plugin version 1.1 and lower
		 *
		 * @package MobilePress
		 * @since 1.1
		 */
		function upgrade_110()
		{
			global $wpdb;
			
			// First we needa alter the option_value2 column name
			$sql	= "ALTER TABLE " . MOPR_TABLE . " CHANGE option_value2 option_value_2 VARCHAR(100) NOT NULL";
			$reset	= $wpdb->query($sql);
			
			// Delete options we no longer need
			$sql	= "DELETE FROM " . MOPR_TABLE . " WHERE option_name = 'windowstheme'";
			$delete	= $wpdb->query($sql);
			
			$sql	= "DELETE FROM " . MOPR_TABLE . " WHERE option_name = 'operamtheme'";
			$delete	= $wpdb->query($sql);
			
			$sql	= "DELETE FROM " . MOPR_TABLE . " WHERE option_name = 'iswebbrowser_iphone'";
			$delete	= $wpdb->query($sql);
			
			$sql	= "DELETE FROM " . MOPR_TABLE . " WHERE option_name = 'iswebbrowser_windows'";
			$delete	= $wpdb->query($sql);
			
			$sql	= "DELETE FROM " . MOPR_TABLE . " WHERE option_name = 'iswebbrowser_operamini'";
			$delete	= $wpdb->query($sql);
			
			// Update some changed options
			$wpdb->query(
				$wpdb->prepare("
					UPDATE
						" . MOPR_TABLE . "
					SET
						option_name = '%s'
					WHERE
						option_name = 'defaulttheme'
					", "default_theme")
				);
			
			$wpdb->query(
				$wpdb->prepare("
					UPDATE
						" . MOPR_TABLE . "
					SET
						option_name = '%s'
					WHERE
						option_name = 'iphonetheme'
					", "iphone_theme")
				);
			
			$wpdb->query(
				$wpdb->prepare("
					UPDATE
						" . MOPR_TABLE . "
					SET
						option_name = '%s'
					WHERE
						option_name = 'forcemobile'
					", "force_mobile")
				);
			
			// Change option values from "true" to "1" and "false" to "0"
			$wpdb->query(
				$wpdb->prepare("
					UPDATE
						" . MOPR_TABLE . "
					SET
						option_value = '%s'
					WHERE
						option_value = 'true'
					", "1")
				);
			
			$wpdb->query(
				$wpdb->prepare("
					UPDATE
						" . MOPR_TABLE . "
					SET
						option_value = '%s'
					WHERE
						option_value = 'false'
					", "0")
				);
			
			// Insert new options
			$sql = "INSERT INTO " . MOPR_TABLE . " (
						option_name,
						option_value,
						option_value_2
					)
					VALUES
						('aduity_account_public_key', '', ''),
						('aduity_account_secret_key', '', ''),
						('aduity_site_public_key', '', ''),
						('aduity_ads_enabled', '0', ''),
						('aduity_analytics_enabled', '0', ''),
						('aduity_ads_type', '0', ''),
						('aduity_ads_campaign', '0', ''),
						('aduity_ads_ad', '0', ''),
						('aduity_ads_location', '0', '')
					";
	
			$results = $wpdb->query($sql);
		}
		
		function upgrade_111()
		{
			global $wpdb;
			
			// Insert new options
			$sql = "INSERT INTO " . MOPR_TABLE . " (
						option_name,
						option_value,
						option_value_2
					)
					VALUES
						('themes_directory', 'mobile-themes', ''),
						('aduity_debug_mode', '0', '')
					";
	
			$results = $wpdb->query($sql);
		}
		
		function upgrade_115()
		{
			global $wpdb;
			
			// Delete options we no longer need
			$sql	= "DELETE FROM " . MOPR_TABLE . " WHERE option_name = 'aduity_account_secret_key'";
			$delete	= $wpdb->query($sql);
			
			// Delete options we no longer need
			$sql	= "DELETE FROM " . MOPR_TABLE . " WHERE option_name = 'analytics_enabled'";
			$delete	= $wpdb->query($sql);
			
			// Delete options we no longer need
			$sql	= "DELETE FROM " . MOPR_TABLE . " WHERE option_name = 'aduity_ads_type'";
			$delete	= $wpdb->query($sql);
			
			// Delete options we no longer need
			$sql	= "DELETE FROM " . MOPR_TABLE . " WHERE option_name = 'aduity_ads_campaign'";
			$delete	= $wpdb->query($sql);
			
			// Delete options we no longer need
			$sql	= "DELETE FROM " . MOPR_TABLE . " WHERE option_name = 'aduity_ads_ad'";
			$delete	= $wpdb->query($sql);
		}
	}
}
?>