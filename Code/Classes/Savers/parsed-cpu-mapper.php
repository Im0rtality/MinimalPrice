<?php

    class ParsedCPUMapperException {
        const NO_TYPE_PARSED = 200;
        const NO_CATEGORY_PARSED = 201;
        const NO_MANUFACTURER_PARSED = 202;
        const NO_IMAGES_PARSED = 203;
        const NO_SOCKETS_PARSED = 204;
        const NO_MODEL_PARSED = 205;
        const NO_SERIAL_PARSED = 206;
        const NO_FREQUENCY_PARSED = 207;
    }
    
    class ParsedCPUMapper
    {	
        /**
         * Maps parser keys and data to database tables' fields
         * @param $sn - product serial number
         * @param associative array $parsedData data from parser
         * return associative array keys - tables' fields, values - parsed data
         */
        public static function mapParsedData($sn, $parsedData)
        {
            $relatedFields;
            $productFields;
            $cpuFields;
            if ( array_key_exists('Type', $parsedData)) {
                $relatedFields['category'] = 'Procesoriai'; // TODO category
            }
            else {
                throw new Exception(__CLASS__ . 'Error: No category', ParsedCPUMapperException::NO_CATEGORY_PARSED);
            }
            if ( array_key_exists('Family', $parsedData)) {
                list($manufacturer, $series) = self::splitFamily( $parsedData['Family']);
                $relatedFields['manufacturer'] = $manufacturer;
                $productFields['series'] = $series;
            }
            else {
                throw new Exception(__CLASS__ . 'Error: No manufacturer', ParsedCPUMapperException::NO_MANUFACTURER_PARSED);
            }
            if ( array_key_exists('CPU part numbers', $parsedData)) {
                $serial = self::splitSerial( $sn, $parsedData['CPU part numbers']);
                $productFields['serial'] = $serial;
            }
            else {
                throw new Exception(__CLASS__ . 'Error: No serial parsed', ParsedCPUMapperException::NO_SERIAL_PARSED);
            }
            if ( array_key_exists('Model number', $parsedData)) {
                $productFields['model'] = $parsedData['Model number'];
            }
            else {
                throw new Exception(__CLASS__ . 'Error: No model', ParsedCPUMapperException::NO_MODEL_PARSED);
            }
            if ( array_key_exists('The number of cores', $parsedData)) {
                $cpuFields['cores'] = $parsedData['The number of cores'];
            }
            else {
                $cpuFields['cores'] = 1;
            }
            
            if ( array_key_exists('Frequency', $parsedData)) {
                $cpuFields['frequency_mhz'] = $parsedData['Frequency'];
            }
            else {
                throw new Exception(__CLASS__ . 'Error: No frequency', ParsedCPUMapperException::NO_FREQUENCY_PARSED);
            }
            if ( array_key_exists('Data width', $parsedData)) {
                $cpuFields['instruction_set_bits'] = 32;
            }
            else {
                $cpuFields['instruction_set_bits'] = $parsedData['Data width'];
            }
            // FUTURE add support (needs parsing 4 x 30 kb instruction + 4 x 2 MB data) 
            //"cacheL1Kb" => $parsedData['Level 1 cache size'],
            //"cacheL2Kb" => $parsedData['Level 2 cache size'],
            //"cacheL3Kb" => $parsedData['Level 3 cache size'],
            if ( array_key_exists('The number of threads', $parsedData)) {
                $cpuFields['threads'] = $parsedData['The number of threads'];
            }
            else {
                $cpuFields['threads'] = 1;
            }
            
            if ( array_key_exists('Introduction date', $parsedData)) {
                $cpuFields['launch_date'] = $parsedData['Introduction date'];
            }
            
            if ( array_key_exists('Clock multiplier', $parsedData)) {
                $cpuFields['bus_core_ratio'] = $parsedData['Clock multiplier'];
            }
            //"busSpeedMhz" => $parsedData['Bus speed'], // FUTURE needs parsing
            if ( array_key_exists('Thermal Design Power', $parsedData)) {
                $cpuFields['max_tdp'] = $parsedData['Thermal Design Power'];
            }
            //"maxCpusConfiguration" => $parsedData['Multiprocessing'], // FUTURE needs parsing to number
            //"technologyNm" => $parsedData['Manufacturing process'], // FUTURE needs parsing to number
            $cpuFields['is_fan_included'] = 0; // FUTURE add box (with fan) processors support   
            
            if ( array_key_exists('Sockets', $parsedData) || array_key_exists('Socket', $parsedData)) {
                $sockets = self::splitSockets($parsedData);
                $relatedFields['sockets'] = $sockets;
            }
            else {
                throw new Exception(__CLASS__ . 'Error: No sockets', ParsedCPUMapperException::NO_SOCKETS_PARSED);
            }
            if ( array_key_exists('Features', $parsedData)) {
                $relatedFields['technologies'] = explode(', ', $parsedData['Features']);
            }
            
            // product images // TODO
            $relatedFields['imageUrl'] = 'dummyImageURL' . rand() . '.jpg'; // create random image address    
            
            return array($relatedFields, $productFields, $cpuFields);
        }

        
        
        // ----- methods for spliting parsed data -----
        private static function splitFamily($family)
        {
            $family = trim($family); // remove leading and trailing whitespaces
            $pos = strpos($family, ' ');
            $manufacturer = substr($family, 0, $pos);
            $series = substr($family, $pos + 1);
            return array($manufacturer, $series);
        }
        
        private static function splitSerial($sn, $partNumbersString)
        {
            $serial = '';
            if (strpos($partNumbersString, $sn) > 0) { // if serial is in partNumbersString
                $serial = $sn;
            }
            return $serial;
        }
        
        private static function splitSockets($parsedData)
        {
            $sockets = [];
            if ( array_key_exists('Sockets', $parsedData)) {
                $sockets = explode(', ', $parsedData['Sockets']);    
            }
            else if ( array_key_exists('Socket', $parsedData)) { // named Socket, not Sockets
                $sockets = explode(', ', $parsedData['Socket']);    
            }
            return $sockets;
        }
        // -----/methods for spliting parsed data -----
    }
?>