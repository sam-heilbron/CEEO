<?php

/*
 *	Conn.php
 *	Author: Sam Heilbron
 *	Last Updated: January 2016
 *
 */
 
 	//do i need to include this if i include it in the API_COntroller class
 	require_once './vendor/autoload.php';
	use Zend\Config\Factory;
	
	abstract class Conn {	
		protected $dbhost = null;
		protected $dbuser = null;
		protected $dbpass = null;
		protected $dbname = null;
		
		public function __construct() {
			$config = Factory::fromFile('config/config.php', true);
			$db_info = $config->get($config->get('ACTIVE_DB'));
			
			$this->dbhost = $db_info->get('host');
			$this->dbuser = $db_info->get('user');
			$this->dbpass = $db_info->get('password');
			$this->dbname = $db_info->get('name');
		}
	}
	
?>