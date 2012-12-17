<?php
    require_once(dirname(__FILE__) . "/../Database/db.php");
    require_once(dirname(__FILE__) . "/../../Shared/utils.php");

    class ParsedMerchandiseMapperException
    {
        // exceptions
        const CANT_MAP_CURRENCY = 101;
        const CANT_MAP_COST = 102;
        const CURRENCY_UNKNOWN = 103;
        const PRODUCT_UNKNOWN = 104;
        
    }
    
    class ParsedMerchandiseMapper
    {	
        private $db;
        private $merchandise;
        private $shop;
        private $currency;
        private $product;
        
        
        
        private function splitCostCurrency($costCurrency)
        {
            if (preg_match("#[0-9]*[.,][0-9]*#", $costCurrency, $matches) > 0) {
                $cost = $matches[0];
                if (preg_match("/([A-Z][a-z]*)/", $costCurrency, $costMatches) > 0) {
                    $currency = substr($costMatches[0], 0, 3); // gets first 3 letters
                }
                else {
                    throw new Exception('Merchandise-mapper: Cannot map parsed currency', ParsedMerchandiseMapperException::CURRENCY_UNKNOWN);
                }
            }
            else {
                throw new Exception('Merchandise-mapper: Cannot map parsed cost', ParsedMerchandiseMapperException::COST_UNKNOWN);
            }   

            return array($cost, $currency);
        }
        
        
        
        /**
         * Maps parser keys and data to database tables' fields
         * @param associative array $parsedData data from parser
         * return associative array keys - tables' fields, values - parsed data
         */
        private function map($parsedData)
        {
            list($cost, $currency) = $this->splitCostCurrency($parsedData['price']);
            
            // merchandise fields
            $fields = [
                "serial" => $parsedData['serial'],
                "currency" => $currency,
                "cost" => $cost,
                "amount" => 1, 
                //"warranty_mm" => 
                "url" => $parsedData['href'],
            ];
            
            return $fields;
        }
        
        public function store($shopId, $parsedData)
        {
            $this->db = DB::getInstance();
            
            $fields = $this->map($parsedData);
            
            // search for given product
            $this->product = R::findOne('product', ' code = ?', array( $fields['serial']));
            if ( !empty($this->product)) {
                $this->merchandise = R::dispense('merchandise');
                $this->shop = R::load('shop', $shopId); // get given shop
                $this->currency = R::findOne('currency', ' name LIKE ?', array( $fields['currency']));
                if ( !empty($this->currency)) {

                    // relations
                    $this->merchandise->shop = $this->shop;
                    $this->merchandise->currency = $this->currency;
                    $this->merchandise->product = $this->product;

                    R::store($this->merchandise);
                }
                else {
                    throw new Exception('Merchandise-mapper: Currency not found in Database', ParsedMerchandiseMapperException::CURRENCY_UNKNOWN);
                }
            } 
            else {
                throw new Exception('Merchandise-mapper: Product with such serial not exists', ParsedMerchandiseMapperException::PRODUCT_UNKNOWN);
            }
        }
    }
?>