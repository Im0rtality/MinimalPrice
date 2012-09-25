<?php
	class Module{
		protected $Options = array();

		function GetName() {
			return $this->Options['name'];
		}
				
		function GetOptions() {
			return $this->Options;
		}
	}
?>