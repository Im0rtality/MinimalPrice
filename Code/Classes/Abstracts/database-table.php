<?php
	abstract class DatabaseTable{	
		abstract public function __constructor($DB);		
		
		abstract public function get();
		abstract public function set();
		abstract public function load($id);
		abstract public function store();
	}
?>