<?php

	class Material {
		private $name = null;
		private $uid = null;
		public function __construct($n = "0", $u = 0) {
			$this->name = $n;
			$this->uid = $u;
		}
	}
	
	class MaterialQ {
		private $material = null;
		private $quantity = null;
		public function __construct($m = null, $q = 0) {
			$m = new Material();
			$this->material = $m;
			$this->quantity = $q;
		}
	}
	
	class Transaction {
		private $Mlist = null;
		public function __construct($arr) {
			$this->Mlist[] = new MaterialQ();
		}
	}

?>