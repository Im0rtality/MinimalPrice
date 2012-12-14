<?php
	require_once("Abstracts/tech-spec.php");
	require_once(dirname(__FILE__) . "\Database\db.php");
	
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
		
		/**
		 * adds specifications one key, one value at time
		 */
		public function add($key, $value) {
			$key = $this->translateKey($key);
			if (!in_array($key, self::$IGNORED_FIELDS)) {
				$this->data[$key] = htmlentities($this->translateValue($value));
			}
		}
		
		public function get() {
			return $this->data;
		}
		
		/**
		 * stores data to DB
		 */
		public function store() {
			$db = DB::getInstance();
			
			$product = R::dispense('product');
			// ----- add product data -----
			$product->series = $this->data['Family'];
			$product->model = $this->data['Model number'];
			$product->code = $this->data['CPU part numbers'];
			$product->description = "a SUPER unique fcuk yea preprocessor";
			
			$product->category_id = 1;
			// -----/add product data -----
			
			
			
			// add existing manufacturer
			$manuf = $this->data['Family'];
			$manufacturer = R::findOne('manufacturer', ' name = ?', array($manuf));
							 										
			$manufacturer->ownProduct[] = $product; // create one to many relation, adding a bean
			
			/*
			// add existing category
			$category = R::findOne('category', 'category = Procesoriai');
			$category->ownProduct[] = $product;	// one to many relation, adding a bean
		
			// add new product image
			$productImage = R::dispense('pimage');
			$productImage->url = 'dummyImageURL' . rand() . '.jpg'; // create random image address
			R::store($productImage);
			
			$product->sharedPimage[] = $productImage; // many to many relation, adding a bean
			*/

			R::store($product); 
		}
	}
?>