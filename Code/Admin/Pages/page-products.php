<?php
	require_once(dirname(__FILE__) . "\..\..\Classes\mysql.php");
	require_once(dirname(__FILE__) . '\..\..\Classes\formatter.php');
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageProducts extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'products';
			$this->options['name'] = 'Products';
			parent::__construct();

		}

        public function set_products(){
            //uzklausa i sql ir duomenu paemimas array formatu bei array ilgiu paemimas (stuff)
            $shop = array( array("rose", 1.25 , 15),
                array("daisy", 0.75 , 25),
                array("orchid", 1.15 , 7),
                array("stuff", 1.15 , 7),
                array("more_stuff", 1.15 , 7),
                array("additional_stuff", 1.15 , 7),
                array("even_more_stuff", 1.15 , 7),
                array("no_more_stuff", 1.15 , 7)
            );
            $size1 = 8;
            $size2 = 3;
            for ($i = 0; $i < $size1; $i++ )
            {
                echo '<form>';
                for ($i2 = 0; $i2 < $size2; $i2++ )
                    echo '<input type="text" id="nameField" value='.$shop[$i][$i2].' length=50 onkeypress="updateName(this.value)"/>';
                echo '<button type="Submit">Update</button>';
                echo '<button type="Submit">Delete</button>';
                echo '</form> ';
            }
        }

		public function generate() {
            $this->set_products();
			$code = "<h1>{$this->options['name']}</h1>";

			return $code;
		}


	}

	new PageProducts();
?>