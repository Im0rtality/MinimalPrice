<?php
	require_once(dirname(__FILE__) . "/../../Code/Shared/utils.php");
	require_once(dirname(__FILE__) . "/../../Code/config.php");
	require_once(dirname(__FILE__) . "/../../Code/Classes/remote-reader.php");
	require_once(dirname(__FILE__) . "/../../Code/Parsers/parser.simple-shop-parser.php");
    require_once(dirname(__FILE__) . "/../../Code/Classes/Mappers/parsed-merchandise-mapper.php");

	function checkParameter($key) {
		if ((!isset($_GET[$key])) || (empty($_GET[$key]))) {
			die("Cannot parse: `{$key}` paremeter is empty or not set");
		}
	}
	
	// check if any parameter is missing
	checkParameter('url');
	checkParameter('shop');

	$parser = new SimpleShopParser($_GET['shop']);
	
	$parser->parsePage($_GET['url']);
    $merchandise = $parser->GetData();
	debug($merchandise);
    
    $shopId = 1; // TODO get merchandise parsers' given shop id. 
    storeAll($shopId, $merchandise);
    
    /**
     * stores all parsed $merchandise, links given shopId. 
     * If there is no product with given serial, tries to get specification for it and store in database.
     * If parsed data is not correct gives echos Exceptions.
     * @param type $shopId
     * @param type $merchandise
     */
    function storeAll($shopId, $merchandise) {
        $merchandiseMapper = new ParsedMerchandiseMapper();
        $merchandiseNo = 0;
        $nTriesToCreateNew = 0; // number of tries to create new product if it is not found, 
        
        while ($merchandiseNo < count($merchandise)) {
            try {
                $merchandiseMapper->store($shopId, $merchandise[$merchandiseNo]);
                $nTriesToCreateNew = 0;
            }
            catch (Exception $e){
                if ($e->getCode() == ParsedMerchandiseMapperException::PRODUCT_UNKNOWN) {
                    echo 'TODO add new product <br>';           // TODO get specs and create a new product
                    $nTriesToCreateNew++;
                }
                else {
                    echo $e, '\n'; // print error
                }
            }
            
            if ($nTriesToCreateNew > 1) {
                $merchandiseNo++;
            } // else work again with same shop item;
        }
    }
?>