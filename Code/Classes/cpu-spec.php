<?php
	require_once("Abstracts/tech-spec.php");
	
	class CpuSpec extends TechSpec{	
		private static $IGNORED_FIELDS = array();//"Features", "CPUID", "Price_at_introduction", "Type");
		private $data;
				
		private function translateKey($key) {
			$key = strip_tags($key);
		
			// some kind of bullshit which does not want to be removed in conventional way
			$key = str_replace(chr(194).chr(160).chr(63).chr(194).chr(160), '', $key);
			$key = trim($key);
			
			return $key;
		}		
				
		private function translateValue($value) {
			$value = str_replace(chr(194).chr(160).chr(63).chr(194).chr(160), '', $value);
			$value = str_replace('<br></br>', ', ', $value);
			$value = preg_replace("/<\/li>\s*<li>/", ",", $value);
			$value = str_replace("\r\n", '', $value);
			$value = strip_tags($value);
			return $value;
		}		
				
		public function add($key, $value){
			$key = $this->translateKey($key);
			if (!in_array($key, self::$IGNORED_FIELDS)) {
				$this->data[$key] = htmlentities($this->translateValue($value));
			}
		}
		
		public function get(){
			return $this->data;
		}
	}
?>