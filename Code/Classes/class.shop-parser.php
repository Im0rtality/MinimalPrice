<?php
	require_once("../Shared/utils.php");
	require_once("class.module.php");
	require_once("class.template-manager.php");

	class ShopParser extends Module{
		protected $Hostname;	
		
		protected $Reader;
		protected $DB;
		protected $Templates;

		protected $contents;
		
		public  $LastError;				
		private $Buffer;
		private $DOM;
		private $Nodes;
		private $XPath;
		
		
		function __construct($DB, $Reader){
			$this->DB = $DB;
			$this->Reader = $Reader;	
			$this->Templates = new TemplateManager($this->DB);
			$this->DOM = new DOMDocument();
			//$this->XPath = new DomXPath($this->DOM);
		}
		
		function GetContent($PageUrl) {
			debug("ShopParser->GetContent($PageUrl)");
			$this->Buffer = $this->Reader->Get($PageUrl);
			return $this->Buffer;
		}
		
		function Load() {
			debug("ShopParser->Load()");
			if (!empty($this->Buffer)) {
				@$this->DOM->loadHTML($this->Buffer);
				$this->DOM->preserveWhiteSpace = false;
				$this->XPath = new DomXPath($this->DOM);
				return true;
			} else {
				$this->LastError = "Error: Buffer is empty";
				return false;
			}
		}
		
		protected function ExtractData() {
			$this->contents = array();
			//$classname = "productListing";
			$tbody = $this->XPath->query($this->Templates->GetItem("product-list"));


			foreach ($tbody as $tr) {
				$item = array();
				$td = $this->XPath->Query('td', $tr);
				
				if ($td->length == 7) {
					$attr = $this->XPath->Query('a', $td->item(2))->item(0);
					if ($attr != null) {
						echo innerHTML($tr) . "<hr/>\n";
				
						foreach ($attr->attributes as $attrName => $attrNode) {
							if ($attrNode->name == "href") {
								$item["href"] = $attrNode->value; 
							}
						}
						
						$item['model'] = $td->item(0)->textContent;
						$item['href'] = $attrNode->value; 
						$item['price'] = $td->item(5)->textContent;
							
						$this->contents[] = $item;
					}
				}
			}
		}
		
		protected function GetOutput() {			
			return $this->contents;
		}
	}
?>