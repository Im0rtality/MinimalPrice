<?php
	require_once(dirname(__FILE__) . "/../Database/db.php");
	require_once(dirname(__FILE__) . "/../../Shared/utils.php");
	
	class ParsedCPUMapper
	{	
		private $db;
		private $product;
		private $productImage;
		private $manufacturer;
		private $category;
		
		
		
		private function map($parsedData)
		{
			// ----- add product data -----
			$this->product->series = $parsedData['Family'];
			$this->product->model = $parsedData['Model number'];
			$this->product->code = $parsedData['CPU part numbers'];
			$this->product->description = "a SUPER unique fcuk yea preprocessor";
			
			$this->product->category_id = 1;
			//$this->product->manufacturer_id = 1;
			// -----/add product data -----
			
			
			// add existing manufacturer
			$this->manufacturer = R::findOne('manufacturer', ' name = ?', array( $parsedData['Family']));
							 										
			
			
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
		}
		
		public function store($parsedData)
		{
			$this->db = DB::getInstance();
			$this->product = R::dispense('product');
			
			$this->map($parsedData);
			$this->manufacturer->ownProduct[] = $this->product; // create one to many relation, adding a bean
			
			R::store($this->product); 
			
		}
	}
?>