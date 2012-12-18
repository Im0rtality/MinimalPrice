<?php
    require_once(dirname(__FILE__) . "/../Database/db.php");
    require_once(dirname(__FILE__) . "/parsed-merchandise-mapper.php");

    class ParsedMerchandiseSaverException extends ParsedMerchandiseMapperException
    {
        const CURRENCY_UNKNOWN = 103;
        const PRODUCT_UNKNOWN = 104;
        const SHOP_UNKNOWN = 105;
    }

    class ParsedMerchandiseSaver
    {
        private $db;
        private $merchandise;
        private $shop;
        private $currency;
        private $product;



        public function store($shopId, $parsedData)
        {
            $this->db = DB::getInstance();

            $fields = ParsedMerchandiseMapper::map($parsedData);
            // search for given product
            $this->product = R::findOne('product', ' serial = ?', array( $fields['serial']));
            if ( !empty($this->product)) {
                $this->merchandise = R::dispense('merchandise');
                $this->shop = R::load('shop', $shopId); // get given shop
                if ( !empty($this->shop)) {
                    $this->currency = R::findOne('currency', " code LIKE ? ", array( $fields['currency']."%"));
                    if ( !empty($this->currency)) {
                        
                        // fields
                        $this->merchandise->cost = $fields['cost'];
                        $this->merchandise->amount = $fields['amount'];
                        //$this->merchandise->warranty_mm = $fields['warranty_mm'];
                        $this->merchandise->url = $fields['url'];
                        
                        // relations
                        $this->merchandise->shop = $this->shop;
                        $this->merchandise->currency = $this->currency;
                        $this->merchandise->product = $this->product;

                        R::store($this->merchandise);
                    }
                    else {
                        throw new Exception(__CLASS__ . ' Currency not found in Database', ParsedMerchandiseSaverException::CURRENCY_UNKNOWN);
                    }
                }
                else {
                    throw new Exception(__CLASS__ . ' Given shop not found in Database', ParsedMerchandiseSaverException::SHOP_UNKNOWN);
                }
            }
            else {
                throw new Exception(__CLASS__ . ' Product with such serial not exists', ParsedMerchandiseSaverException::PRODUCT_UNKNOWN);
            }
        }
    }
?>