<?php
	require_once(dirname(__FILE__) . "\..\..\Classes\mysql.php");
	require_once(dirname(__FILE__) . '\..\..\Classes\formatter.php');
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageProducts extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'products';
			$this->options['name'] = 'Products';
			parent::__construct();
		}

		public function generate() {
			$code = "<h1>{$this->options['name']}</h1>";

			return $code;
		}
	}

	new PageProducts();
?>