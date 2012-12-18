<?php
    require_once(dirname(__FILE__) . "/../Database/db.php");
    require_once(dirname(__FILE__) . "/parsed-cpu-mapper.php");

    class ParsedCPUSaverException extends ParsedCPUMapperException {
        const NO_CATEGORY_AT_DB = 207;
    }
    
    class ParsedCPUSaver
    {	
        private $db;
        private $category;
        private $manufacturer;
        private $productImage;
        private $product;
        private $cpu;
        private $technologies;
        private $sockets;
        
        
        
        public function store($sn, $parsedData)
        {
            list($relatedFields, $productFields, $cpuFields) = ParsedCPUMapper::mapParsedData($sn, $parsedData);
            
            $this->set($relatedFields, $productFields, $cpuFields);
            
            R::store($this->productImage);
            R::store($this->product);
            R::store($this->cpu);
            R::storeAll($this->sockets);
            R::storeAll($this->technologies);
            echo 'issaugotas cpu su serial = ', $this->product->serial, '<br />';
            return $this->product->model;
        }
               
        
        
        /**
         * Prepares data for storage. Creates or find needed beans, adds field data
         * @param associative array $data
         */
        private function set($relatedFields, $productFields, $cpuFields)
        {    
            $this->db = DB::getInstance();
           
            $this->product = R::findOne('product', ' serial = ?', array ( $productFields['serial'])); 
            if ( !empty($this->product)) {
                $this->deleteProductInfo();
            } else {
                $this->product = R::dispense('product');
            }
            
            
            
            foreach($productFields as $key => $value) {
                $this->product->$key = $value;
            }
            
            // add existing category
            $this->category = R::findOne('category', ' name = ?', array( $relatedFields['category']));
            if ( !empty($this->category)) {
                $this->product->category = $this->category;
            }
            else {
                throw new Exception(getClass($this) . 'Error: Category doesnt exist in Database', ParsedCPUMapperException::NO_CATEGORY_AT_DB);
            }
            
            
            
            // add existing or creating new manufacturer
            $manufacturers = R::findOrDispense('manufacturer', ' name = ?', array( $relatedFields['manufacturer']));
            $this->manufacturer = reset($manufacturers);
            $this->manufacturer->name = $relatedFields['manufacturer'];
            $this->product->manufacturer = $this->manufacturer; // creates many to one relation
            
            
            
            
            // add existing or creating new image
            $productImages[] = R::findOrDispense('pimage', ' url = ?', array( $relatedFields['imageUrl']));
            $this->productImage = reset($productImages[0]);
            $this->productImage->url = $relatedFields['imageUrl']; 
            $this->product->sharedPimage[] = $this->productImage; // many to many relation, adding a bean
            
            
            
            // create new cpu
            $this->cpu = R::dispense('cpu');
            foreach($cpuFields as $key => $value) {
                $this->cpu->$key = $value;
            }
            $this->cpu->product = $this->product;
            
            
            
            // add sockets
            for ($i = 0; $i < count( $relatedFields['sockets']); $i++) {
                $this->sockets[$i] = R::findOne('socket', ' name = ?', array( $relatedFields['sockets'][$i] ));
                if ( empty($this->sockets[$i])) {
                    $this->sockets[$i] = R::dispense('socket');
                    $this->sockets[$i]->name = $relatedFields['sockets'][$i];
                }
            }
            $this->cpu->sharedSocket = $this->sockets;
            
            
            
            // add technologies
            for ($i = 0; $i < count( $relatedFields['technologies']); $i++) {
                $this->technologies[$i] = R::findOne('technology', ' name = ?', array( $relatedFields['technologies'][$i] ));
                if ( empty($this->technologies[$i])) {
                    $this->technologies[$i] = R::dispense('technology');
                    $this->technologies[$i]->name = $relatedFields['technologies'][$i];
                }
            }
            $this->cpu->sharedTechnology = $this->technologies;
        }
        
        private function deleteProductInfo() 
        {
            // leave only serial
            $this->product->series = '';
            $this->product->model = '';
            $this->product->description = '';
            $this->product->weight_kg = null;
            $this->product->width_mm = null;
            $this->product->height_mm = null;
            $this->product->depth_mm = null;
                    
            $pimage_products = R::find('pimage_product', ' product_id = ?', array($this->product->id));
            R::trashAll($pimage_products);
            
            $accesory_product = R::find('pimage_product', ' product_id = ?', array($this->product->id));
            R::trashAll($accesory_product);
            
            // delete related cpu, cpu_socket, cpu_technologies
            $this->cpu = R::findOne('cpu', ' product_id = ?', array ($this->product->id));
            if ( !empty($this->cpu)) {

                $sockets = R::find('cpu_socket', ' cpu_id = ?', array ($this->cpu->id));
                $technologies = R::find('cpu_technology', ' cpu_id = ?', array ($this->cpu->id));

                R::trashAll($sockets);
                R::trashAll($technologies);                    
                R::trash($this->cpu);
            }
        }
    }
?>