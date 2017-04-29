<?php
require_once("TypeEffectiveness.php");

class Resistance extends TypeEffectiveness {
	public function __construct($type, $bonus) {
		parent::__construct($type, true, $bonus);
	}
}
