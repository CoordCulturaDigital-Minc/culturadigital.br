<?php
if ( ! class_exists('MobilePress_uninstall'))
{
	/**
	 * Class which completely removes the MobilePress plugin and database
	 *
	 * @package MobilePress
	 * @since 1.0.2
	 */
	class MobilePress_uninstall {
	
		/**
		 * Constructor which initiates the uninstallation
		 *
		 * @package MobilePress
		 * @since 1.0.2
		 */
		function MobilePress_uninstall()
		{
			$this->delete_table();
		}
		
		/**
		 * Deletes the MobilePress table
		 *
		 * @package MobilePress
		 * @since 1.0.2
		 */
		function delete_table()
		{
			global $wpdb;
			$sql = "DROP TABLE IF EXISTS " . MOPR_TABLE;
			$wpdb->query($sql);
		}
		
	}
}
?>