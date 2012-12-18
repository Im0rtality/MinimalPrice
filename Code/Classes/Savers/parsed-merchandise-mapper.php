<?php
    class ParsedMerchandiseMapperException
    {
        // exceptions
        const CANT_MAP_CURRENCY = 101;
        const CANT_MAP_COST = 102;
    }

    class ParsedMerchandiseMapper
    {
        /**
         * Maps parser keys and data to database tables' fields
         * @param associative array $parsedData data from parser
         * return associative array keys - tables' fields, values - parsed data
         */
        public static function map($parsedData)
        {
            $fields['serial'] = preg_replace("/[^a-zA-Z0-9]+/", "", $parsedData['serial']);
            
            list($cost, $currency) = self::splitCostCurrency($parsedData['price']);
            if ( !empty($cost)) {
                $fields['cost'] = $cost;
            }
            else {
                throw new Exception(__CLASS__ . ' Cannot map parsed cost', ParsedMerchandiseSaverException::CANT_MAP_COST);
            }
            if ( !empty($currency)) {
                $fields['currency'] = $currency;
            }
            else {
                throw new Exception(__CLASS__ . ' Cannot map parsed currency', ParsedMerchandiseSaverException::CANT_MAP_CURRENCY);
            }
            $fields['amount'] = 1; // TODO
            //$fields['warranty_mm'] =  // TODO
            $fields['url'] = $parsedData['href'];
            
            return $fields;
        }
        
        
        private static function splitCostCurrency($costCurrency)
        {
            if (preg_match("#[0-9]*[.,][0-9]*#", $costCurrency, $matches) > 0) {
                $cost = $matches[0];
                if (preg_match("/([A-Z][a-z]*)/", $costCurrency, $costMatches) > 0) {
                    $currency = strtoupper( substr($costMatches[0], 0, 3)); // gets first 3 letters
                }
            }
            return array($cost, $currency);
        }
    }
?>