<?php
    require_once("../Shared/utils.php");
    require_once("../Classes/wpt.php");
    require_once("../Classes/wpt-template.php");
    require_once("../Classes/cpu-spec.php");
	
	class CpuWorldParser{	
		
		private $Template;

		function __construct($DB, $Reader){
			$this->DB = $DB;
			$this->Reader = $Reader;	
			$this->WebsiteBase = "http://www.cpu-world.com";
			
			$this->Template = new WPTTemplate();
			$this->Template->load("//table[contains(@class,\"bh_table\")][2]//tr", "<template><a href='{href}'> </a></template>",  0,  1);
			$this->Template2 = new WPTTemplate();
			$this->Template2->load("//div[contains(@id,\"GET_INFO\")]//tr", "",  0,  1);
		}
				
		private function ReadyCheck(){
			if (empty($this->DB)) {
				die("Error: SimpleShopParser->DB is not defined!");
			}
			if (empty($this->Reader)) {
				die("Error: SimpleShopParser->Reader is not defined!");
			}
			return true;
		}
		
		function Parse(){
			if ($this->ReadyCheck() === true) {
				$this->ExtractData();
			} else {
				die("SimpleShopParser->Parse: Error not ready");
			}
		}
		
		function SearchFor($Serial) {
			$WPT = new WebsiteParseTemplate($this->DB, $this->Reader);
			$results = $WPT->parsePage("http://www.cpu-world.com/cgi-bin/IdentifyPart.pl?PART={$Serial}&PROCESS=Go", $this->Template);
			//debug($results);
			$DOM = new DOMDocument();
			$spec = new CpuSpec();
			foreach($results as $result) {
				$results = $WPT->parsePage("http://www.cpu-world.com/{$result['href']}", $this->Template2);
				foreach($results as &$result) {
					$result['value'] = mb_convert_encoding($result['value'], 'utf-8', mb_detect_encoding($result['value']));
					$result['value'] = mb_convert_encoding($result['value'], 'html-entities', 'utf-8'); 
					
					@$DOM->loadHTML($result['value']);
					$XPath = new DomXPath($DOM);
					$tds = $XPath->query("//td");
					$i = 0;
					$key = "";
					$value = "";
					foreach($tds as $td) {
						$i++;
						$text = innerHTML($td);
						switch($i) {
							case 1:
								$key = $text;
								break;
							case 2:
								$value = $text;
								$spec->add($key, $value);
								break;
						}
					}
				}
			}
			return $spec;
		}
		
		function GetData() {	
			return $this->GetOutput();
		}
	}
?>