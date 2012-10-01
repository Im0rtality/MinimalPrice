<?php
    require_once("../Shared/utils.php");
	require_once("../config.php");
	require_once("../Classes/class.mysql.php");
	require_once("../Classes/class.remote-reader.php");
	require_once("../Parsers/parser.cpuworld.php");
	
	$db = new MySql();

	$reader = new RemoteReader();

	$parser = new CpuWorldParser($db, $reader);
	
	debug($parser->SearchFor("SDX140HBK13GQ"));
	
?>