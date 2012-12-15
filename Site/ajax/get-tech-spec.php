<?php
       	require_once(dirname(__FILE__) . "/../../Code/Shared/utils.php");
	require_once(dirname(__FILE__) . "/../../Code/config.php");
	require_once(dirname(__FILE__) . "/../../Code/Classes/remote-reader.php");
	require_once(dirname(__FILE__) . "/../../Code/Parsers/parser.cpuworld.php");
	require_once(dirname(__FILE__) . "/../../Code/Classes/Mappers/parsed-cpu-mapper.php");

	if ((isset($_GET['sn'])) && (!empty($_GET['sn']))) {
		$reader = new RemoteReader();

		$parser = new CpuWorldParser($reader);
		
		
		$spec = $parser->SearchFor($_GET['sn']);
		debug($spec);
		$mapper = new ParsedCPUMapper();
		$mapper->store( $spec->get());
	} else {
		die("Cannot parse: sn paremeter is empty or not set");
	}
	
?>