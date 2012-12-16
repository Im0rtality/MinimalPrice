<?php
    require_once(dirname(__FILE__) . "/../Database/db.php");
    require_once(dirname(__FILE__) . "/../../Shared/utils.php");

    class ParsedCPUMapper
    {	
        private $db;
        private $category;
        private $manufacturer;
        private $productImage;
        private $product;
        private $cpu;
        private $technologies;
        private $sockets;
        
        
        
        // ----- methods for spliting parsed data -----
        private function splitFamily($family)
        {
            $pos = strpos($family, ' ');
            $manufacturer = substr($family, 0, $pos);
            $series = substr($family, $pos + 1);
            
            return array($manufacturer, $series);
        }
        
        private function splitCode($partNumbersString)
        {
            $pos = strpos($partNumbersString, ' ');
            $code = substr($partNumbersString, 0, $pos);
            
            return $code;
        }
        
        private function splitSockets($parsedData)
        {
            $sockets = [];
            if ( array_key_exists('Sockets', $parsedData)) {
                $sockets = explode(', ', $parsedData['Sockets']);    
            }
            else { // named Socket, not Sockets
                $sockets = explode(', ', $parsedData['Socket']);    
            }
            return $sockets;
        }
        // -----/methods for spliting parsed data -----
        
        
        
        /**
         * Maps parser keys and data to database tables' fields
         * @param associative array $parsedData data from parser
         * return associative array keys - tables' fields, values - parsed data
         */
        private function map($parsedData)
        {
            list($manufacturer, $series) = $this->splitFamily( $parsedData['Family']);
            $code = $this->splitCode( $parsedData['CPU part numbers']);
            
            $sockets = $this->splitSockets($parsedData);
	    $technologies = explode(', ', $parsedData['Features']);
            
            // misc related tables' fields
            $relatedFields = [
                "category" => "Procesoriai",
                "manufacturer" => $manufacturer,
                
                // product images
                "imageUrl" => 'dummyImageURL' . rand() . '.jpg', // create random image address
                
                "sockets" => $sockets,
		"technologies" => $technologies,
            ];
            
            // TRICKY ----------------- folowing fields' keys MUST be the same as table fields ----------------------------------- 
            // product table fields
            $productFields = [
                "series" => $series,
                "model" => $parsedData['Model number'],
                "code" => $code,
                "description" => "dummy decription",
            ];
            
            // cpu table fields
            $cpuFields = [
		"cores" => $parsedData['The number of cores'],
		"frequency_mhz" => $parsedData['Frequency'],
		"instruction_set_bits" => $parsedData['Data width'],
                // FUTURE add support (needs parsing 4 x 30 kb instruction + 4 x 2 MB data) 
		//"cacheL1Kb" => $parsedData['Level 1 cache size'],
		//"cacheL2Kb" => $parsedData['Level 2 cache size'],
		//"cacheL3Kb" => $parsedData['Level 3 cache size'],
		"threads" => $parsedData['The number of threads'],
		"launch_date" => $parsedData['Introduction date'], // FIXME convert to data - time
		"bus_core_ratio" => $parsedData['Clock multiplier'],
		//"busSpeedMhz" => $parsedData['Bus speed'], // FUTURE needs parsing
		"max_tdp" => $parsedData['Thermal Design Power'],
		//"maxCpusConfiguration" => $parsedData['Multiprocessing'], // FUTURE needs parsing to number
		//"technologyNm" => $parsedData['Manufacturing process'], // FUTURE needs parsing to number
		"is_fan_included" => 0, // FUTURE add box (with fan) processors support   
            ];
            
            return array($relatedFields, $productFields, $cpuFields);
        }
        
        /**
         * Prepares data for storage. Creates or find needed beans, adds field data
         * @param associative array $data
         */
        private function set($relatedFields, $productFields, $cpuFields)
        {    
            $this->db = DB::getInstance();
            
            // add existing category
            $this->category = R::findOne('category', ' name = ?', array( $relatedFields['category']));
            
            // add existing manufacturer
            $this->manufacturer = R::findOne('manufacturer', ' name = ?', array( $relatedFields['manufacturer']));
            if ( empty($this->manufacturer)) {
                $this->manufacturer = R::dispense('manufacturer'); // create new
                $this->manufacturer->name = $relatedFields['manufacturer'];
            }
            
            // add existing image
            $this->productImage = R::findOne('pimage', ' url = ?', array( $relatedFields['imageUrl']));
            if ( empty($this->productImage)) {
                $this->productImage = R::dispense('pimage'); // create new image
                $this->productImage->url = $relatedFields['imageUrl']; // create random image address
            }

            // create product data
            $this->product = R::dispense('product'); // create new
            foreach($productFields as $key => $value) {
                $this->product->$key = $value;
            }
            
            // create cpu table fields
            $this->cpu = R::dispense('cpu'); // create new
            foreach($cpuFields as $key => $value) {
                $this->cpu->$key = $value;
            }
            
            // add sockets
            for ($i = 0; $i < count( $relatedFields['sockets']); $i++) {
                $this->sockets[$i] = R::findOne('socket', ' name = ?', array( $relatedFields['sockets'][$i] ));
                if ( empty($this->sockets[$i])) {
                    $this->sockets[$i] = R::dispense('socket');
                    $this->sockets[$i]->name = $relatedFields['sockets'][$i];
                }
            }
            
            // add technologies
            for ($i = 0; $i < count( $relatedFields['technologies']); $i++) {
                $this->technologies[$i] = R::findOne('technology', ' name = ?', array( $relatedFields['technologies'][$i] ));
                if ( empty($this->technologies[$i])) {
                    $this->technologies[$i] = R::dispense('technology');
                    $this->technologies[$i]->name = $relatedFields['technologies'][$i];
                }
            }
        }

        public function store($parsedData)
        {
            list($relatedFields, $productFields, $cpuFields) = $this->map($parsedData);
            $this->set($relatedFields, $productFields, $cpuFields);
            
            // relations
            $this->product->manufacturer = $this->manufacturer; // creates many to one relation, adds a bean
            $this->product->category = $this->category;
            $this->product->sharedPimage[] = $this->productImage; // many to many relation, adding a bean
            
            $this->cpu->product = $this->product;
            $this->cpu->sharedSocket = $this->sockets;
            $this->cpu->sharedTechnology = $this->technologies;
            
            R::store($this->productImage);
            R::store($this->product);
            R::store($this->cpu);
            R::storeAll($this->sockets);
            R::storeAll($this->technologies);
        }
    }
?>