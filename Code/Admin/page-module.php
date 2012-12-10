<?php
	class PageModule {
		protected $options;
		protected $action = 'none';

		function __construct(){
			$this->options['file'] = debug_backtrace(FALSE)[0]['file'];
			$this->options['class'] = debug_backtrace(FALSE)[1]['class'];
			if (!isset($this->options['inSidebar'])) { $this->options['inSidebar'] = true; }
			
			if ((isset($_GET['action']) && !empty($_GET['action']))) {
				$this->action = $_GET['action'];
			}

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

		function getHeader() {
			$action = "";
			if ($this->action != 'none') {
				$action = " <small> [{$this->action}]</small>";
			}
			$code = "<h1>{$this->options['name']}{$action}</h1>";
			return $code;
		}

	}
?>
