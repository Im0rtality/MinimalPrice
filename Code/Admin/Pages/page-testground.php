<?php
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageTestGround extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'test';
			$this->options['name'] = 'Test Ground';
			parent::__construct();
		}

		public function generate() {
			$code = "<h1>{$this->options['name']}</h1>";

			$Template['product-list'] = '//table[contains(@class,\"productListing\")][1]//tr';
			$Template['product-list-skip'] = 1;
			$Template['product-list-field-count'] = 3;
			$Template['product-list-item'] = "<template><td>{serial}</td><td></td><td><a href='{href}'></a> </td><td></td><td>{price}</td></template>";

			$json = json_encode($Template);

			debug(htmlentities($json));

			return $code;
		}
	}

	new PageTestGround();
?>