<?php
	require_once(dirname(__FILE__) . "/../../Code/Shared/utils.php");
	require_once(dirname(__FILE__) . "/../../Code/config.php");
	require_once(dirname(__FILE__) . "/../../Code/Classes/remote-reader.php");
	require_once(dirname(__FILE__) . "/../../Code/Parsers/parser.cpuworld.php");
    require_once(dirname(__FILE__) . "/../../Code/Classes/Savers/parsed-cpu-saver.php");

    class GetTechSpec 
    {
        function parseAndStore($sn) {
            if ((isset($sn)) && (!empty($sn))) {
                $reader = new RemoteReader();

                $parser = new CpuWorldParser($reader);

                $spec = $parser->SearchFor($sn);
                debug($spec);
                
                $specArray = $spec->get();
                if ( !empty($specArray)) {
                    $saver = new ParsedCPUSaver();
                    try {
                        $model = $saver->store($sn, $specArray);
                        echo '<h2><strong> SAVED TO DB: Model = ', $model, ' Serial = ', $sn, '</strong></h2><br />';
                    }
                    catch (Exception $e) {
                        echo 'Error', $e, '<br />';
                    }
                } 
           } else {
                die("Cannot parse: sn paremeter is empty or not set");
           }
        }
    }
?>