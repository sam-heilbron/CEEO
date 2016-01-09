<?php
	
	trait Other_trait {

		//public function __construct() {}
		
		protected function sayBye() {
			$this->test();
			return "Say bye";
		}
		
		private function test() {
			echo "test function";
		}
		

	} ## Other_trait
?>