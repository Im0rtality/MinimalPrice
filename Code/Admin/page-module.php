<?php
	class PageModule {
		protected $options;

		function __construct(){
			AdminPageGenerator::getInstance()->registerPage($this);
		}

		function getOptions() {
			return $this->options;
		}

		function getID() {
			return $this->options['link'];
		}

		function getNavItem() {
			return array('link' => $this->options['link'], 'name' => $this->options['name']);
		}
	}
?>
