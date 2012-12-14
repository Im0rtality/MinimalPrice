<?php
	require_once(dirname(__FILE__) . "/../../Code/Shared/utils.php");
	require_once(dirname(__FILE__) . "/../../Code/config.php");
	require_once(dirname(__FILE__) . "/../../Code/Classes/remote-reader.php");
	require_once(dirname(__FILE__) . "/../../Code/Parsers/parser.cpuworld.php");


	if ((isset($_GET['sn'])) && (!empty($_GET['sn']))) {
		$reader = new RemoteReader();

		$parser = new CpuWorldParser($reader);
		
		
		$data = $parser->SearchFor($_GET['sn']);
		$data->store();
		debug($data);
	} else {
		die("Cannot parse: sn paremeter is empty or not set");
	}
	
?>