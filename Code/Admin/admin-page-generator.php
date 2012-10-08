<?php
//	required for page modules
	require_once(dirname(__FILE__) . "/page-module.php");

	class AdminPageGenerator{
		private $args = [];
		private $page = 'home';
		private $output;
		private $DB;
		private $modules = [];

		// Singleton pattern. Keep code tight for easy copy pasting to other places
    	protected static $instance = null;
	    protected function __construct(){}
	    protected function __clone(){}
    	public static function getInstance() {
	        if (!isset(static::$instance)) {
	            static::$instance = new static;
	        }
	        return static::$instance;
	    }

		public static function init(){
			$obj = AdminPageGenerator::getInstance();
			$obj->DB = MySql::getInstance();
			return $obj;
		}

		function passQuery($args){
			$this->args = $args;

			// if no page selected, go to homepage
			if (empty($this->args['page'])) {
				$this->page = 'home';
			} else {
				$this->page = $this->args['page'];
			}
		}

		/**
		 * Includes all files from predefined directory
		 * 
		 * Loading logic is left in files
		 **/
		function includePages() {
			$this->navbar = [];
			foreach (glob(dirname(__FILE__) . "/Pages/*.php") as $filename) {
			    include $filename;
			}
		}

		/** 
		 * Callback for page modules
		 **/
		public function registerPage($pageModule) {
			$this->modules[$pageModule->getID()] = $pageModule;
			$this->navbar[] = $pageModule->getNavItem();
		}

		/** 
		 * Generates page
		 * 
		 * Generates page according to settings. Picks appropriate module for loading using <i>page</i> passed in passQuery() 
		 **/
		function generate() {
			$template = [];
			$template['nav'] = $this->navbar;
			if (isset($this->modules[$this->page])) {
				$module = $this->modules[$this->page];
				$template['title'] = $module->getOptions()['name'];
				$template['body'] = $module->generate();
			} else {// show admin homepage/overview
				$template['title'] = 'Error';
				$template['body'] = "<h1>Invalid page parameter: '{$this->page}'</h1>";
			}

			require("../../HTML Templates/Admin/page.php");
		}

	}
?>