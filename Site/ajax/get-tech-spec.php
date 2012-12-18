<?php
	require_once(dirname(__FILE__) . "/../../Code/Shared/utils.php");
	require_once(dirname(__FILE__) . "/../../Code/config.php");
	require_once(dirname(__FILE__) . "/../../Code/Classes/remote-reader.php");
	require_once(dirname(__FILE__) . "/../../Code/Parsers/parser.cpuworld.php");
    require_once(dirname(__FILE__) . "/../../Code/Classes/Mappers/parsed-cpu-saver.php");


	if ((isset($_GET['sn'])) && (!empty($_GET['sn']))) {
		$reader = new RemoteReader();

		$parser = new CpuWorldParser($reader);
		
        $spec = $parser->SearchFor($_GET['sn']);
		debug($spec);
        
        $mapper = new ParsedCPUSaver();
        $model = $mapper->store($_GET['sn'], $spec->get());
        echo '<h2><strong> SAVED TO DB: Model = ', $model, ' Serial = ', $sn, '</strong></h2><br />';
	} else {
		die("Cannot parse: sn paremeter is empty or not set");
	}
	
?>