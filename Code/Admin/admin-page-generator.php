<?php
	//	required for page modules
	require_once(dirname(__FILE__) . "/page-module.php");
	//	required for PageHome class
	require_once(dirname(__FILE__) . "/../Classes/formatter.php");
	require_once(dirname(__FILE__) . "/interface.page-generator.php");
	
	class PageHome extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'home';
			$this->options['name'] = 'Home';
			parent::__construct();
		}

		public function generate() {
			$code = "<h1>{$this->options['name']}</h1>";
			$code .= '<table class="table table-condensed table-hover table-stripped"><tr><th>Page/Module</th><th>Link/ID</th><th>PHP File</th><th>Class name</th></tr>';
			foreach (AdminPageGenerator::getInstance()->getModules() as $module) {
				$code .= "<tr><td>{$module->options['name']}</td><td>{$module->options['link']}</td><td>{$module->options['file']}</td><td>{$module->options['class']}</td></tr>";
			}
			$code .= '</table>';
			return $code;
		}
	}

	class AdminPageGenerator{
		private $args = array();
		private $page = 'home';
		private $output;
		private $DB;
		protected $modules = array();

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

		public function getModules() {
			return $this->modules;
		}
		/**
		 * Includes all files from predefined directory
		 * 
		 * Loading logic is left in files
		 **/
		function includePages() {
			$this->navbar = [];
			new PageHome();
			foreach (glob(dirname(__FILE__) . "/Pages/*.php") as $filename) {
			    include $filename;
			}
		}

		/** 
		 * Callback for page modules
		 **/
		public function registerPage($pageModule) {
			$this->modules[$pageModule->getID()] = $pageModule;
			//debug($pageModule->getOptions()['inSidebar']);
			if ($pageModule->getOptions()['inSidebar'] === true) {
				$this->navbar[] = $pageModule->getNavItem();
			}
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