<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Install_Admin extends CI_Migration {

	public function up()
	{
		$attributes = array('ENGINE' => 'MyISAM', 'DEFAULT CHARSET' => 'utf8');

		$fields = array(
				"`id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY",
				"`name` varchar(150) NOT NULL DEFAULT ''"	,
				"`user` varchar(50) NOT NULL",
				"`pass` varchar(100) NOT NULL",
				"`mail` varchar(50) NOT NULL",
				"`active` int(1) NOT NULL DEFAULT '0'",
				"`mail_token` varchar(100) NULL",
				"`mail_expires` datetime NULL",
				"`created` datetime NOT NULL",
				"`updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'",
				"`access` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'"
				);

		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('administrators', TRUE, $attributes);
		$this->db->simple_query("INSERT INTO `administrators` (`name`, `user`, `pass`, `mail`, `active`, `created`) VALUES ('Administrator', 'admin', '09ae22c2c195d71cca64d461a1603332efb073d9', 'admin@localhost.com', 1, CURRENT_TIMESTAMP)");

		$fields = array(
				"`ip` varchar(20) NOT NULL PRIMARY KEY",
				"`attempts` tinyint(1) NOT NULL",
				"`blocked` datetime NOT NULL",
				);

		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('auth_attempts', TRUE, $attributes);

		$fields = array(
				"`id` varchar(128) NOT NULL",
				"`ip_address` varchar(45) NOT NULL",
				"`timestamp` int(10) unsigned DEFAULT 0 NOT NULL",
				"`data` blob NOT NULL",
				"PRIMARY KEY (id)",
				"KEY `ci_sessions_timestamp` (`timestamp`)"
				);

		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('ci_sessions', TRUE, $attributes);
	}

	public function down()
	{
		$this->dbforge->drop_table('administrators');
		$this->dbforge->drop_table('auth_attempts');
		$this->dbforge->drop_table('ci_sessions');
	}

}

/* End of file 001_install_admin.php */
/* Location: ./application/migrations/001_install_admin.php */
