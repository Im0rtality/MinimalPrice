<?php
	require_once(dirname(__FILE__) . "/../../Classes/mysql.php");
	require_once(dirname(__FILE__) . '/../interface.page-generator.php');

	class PageParserPanel extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'parser-panel';
			$this->options['name'] = 'Parsers Control Panel';
			parent::__construct();
		}

		public function generate() {
			$code = "";
			$code .= <<<EOT
<html>
	<head>
	</head>
<body>
	<form method="GET" action="../ajax/parser.php" class="form-inline">
		<legend>Skytech.lt parser</legend>
		<div class="input-append">
			<input type="text" name="url" id="url" class="input-xxlarge" placeholder="URL of page with product list">
			<select id="shop" name="shop" class="input-medium">
			  <option value="1">SKYTECH.LT</option>
			</select>
			<button type="submit" class="btn">PARSE</button> 
		</div>
		<p class="muted">Sample data: http://www.skytech.lt/procesoriai-stac-komp-procesoriai-c-86_85_182_584.html?pagesize=500</p>
	</form>

	<form method="GET" action="../ajax/get-tech-spec.php">
		<legend>cpu-world.com parser</legend>	
		<div class="input-append">
			<input type="text" name="sn" id="url" class="input-xlarge" placeholder="Serial Number of CPU">
			<select id="category" class="input-medium">
			  <option value="3">CPU</option>
			</select>
			<button type="submit" class="btn">PARSE</button> 
		</div>
		<p class="muted">Sample data: SDX140HBK13GQ</p>
</form>
</body>
</html>
EOT;

			return $code;
		}
	}

	new PageParserPanel();
?>