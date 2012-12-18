<?php
	require_once(dirname(__FILE__) . "/parsed-merchandise-saver.php");
    require_once(dirname(__FILE__) . "/../../../Site/ajax/get-tech-spec2.php");
    
    
    
    class ParsedMPS { // parsed merchandise product saver
        
        /**
         * stores all parsed $merchandise, links given shopId. 
         * If there is no product with given serial, tries to get specification for it and store in database.
         * If parsed data is not correct echos Exceptions.
         * @param type $shopId
         * @param type $merchandise
         */
        function storeAll($shopId, $merchandise) {
            $merchandiseSaver = new ParsedMerchandiseSaver();
            $specGetter = new GetTechSpec();
            $i = 0;
            $hasTriedCreate = false; // has tried to create product

            while ($i < count($merchandise)) {
                try {
                    echo 'preke nr = ', $i, ' bandoma saugoti<br />';
                    $merchandiseSaver->store($shopId, $merchandise[$i]);
                    echo 'preke nr = ', $i, ' sekmingai issaugota<br />';
                    $i++;
                    $hasTriedCreate = false;
                }
                catch (Exception $e){
                    if ( !$hasTriedCreate && $e->getCode() == ParsedMerchandiseSaverException::PRODUCT_UNKNOWN) {
                        echo 'prekes nr = ', $i, ' atitinkancio produkto nera, ieskom tokio produkto<br />';
                        $sn = preg_replace("/[^a-zA-Z0-9]+/", "", $merchandise[$i]['serial']);
                        $specGetter->parseAndStore($sn);
                        $hasTriedCreate = true;
                    }
                    else {
                        echo 'Error: ', $e->getMessage(), '<br />';
                        $i++;
                        $hasTriedCreate = false;
                    }
                }
            }
        }
    }
?>