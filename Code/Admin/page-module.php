<?php
	class PageModule {
		protected $options;

		function __construct(){
			$this->options['file'] = debug_backtrace(FALSE)[0]['file'];
			$this->options['class'] = debug_backtrace(FALSE)[1]['class'];
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
