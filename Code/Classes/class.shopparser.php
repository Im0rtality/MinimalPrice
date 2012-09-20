<?php
	function debug($data) {
		echo "<pre>" . print_r($data, true) . "</pre>";
	}
	class ShopParser{
		protected $Hostname;	
		
		public  $LastError;				
		private $DBLink;
		private $Reader;
		private $Buffer;
		private $DOM;
		private $Nodes;
		private $XPath;
		
		
		function ShopParser($DB, $R){
			$this->DBLink = $DB;
			$this->Reader = $R;
			$this->DOM = new DOMDocument();
		}
		
		function GetContent($PageUrl) {
			$this->Buffer = $this->Reader->Get($PageUrl);
			return $this->Buffer;
		}
		
		function Load() {
			if (!empty($this->Buffer)) {
				@$this->DOM->loadHTML($this->Buffer);
				return true;
			} else {
				$this->LastError = "Error: Buffer is empty";
				return false;
			}
		}
		
		function ExtractData() {
			$this->Nodes = $this->DOM->getElementsByTagName('table');
			$this->XPath = new DomXPath($this->DOM);
		}
		
		function GetOutput() {
			$contents = array();
			$classname = "productListing";
			$tbody = $this->XPath->query("//table[contains(@class,\"$classname\")][1]//tr");

			foreach ($tbody as $node) {
				$item = array();
				$td = $this->XPath->Query('td', $node);
				if ($td->length == 7) {
					$attr = $this->XPath->Query('a', $td->item(2))->item(0);
					if ($attr != null) {
					
						foreach ($attr->attributes as $attrName => $attrNode) {
							if ($attrNode->name == "href") {
								$item["href"] = $attrNode->value; 
							}
						}
						
						$item['model'] = $td->item(0)->textContent;
						$item['href'] = $attrNode->value; 
						$item['price'] = $td->item(5)->textContent;
						
						$contents[] = $item;
					}
				}
			}
			return $contents;
		}
	}
?>