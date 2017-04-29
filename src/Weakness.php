<?php
require_once("TypeEffectiveness.php");

class Weakness extends TypeEffectiveness {
	public function __construct($type, $bonus) {
		parent::__construct($type, false, $bonus);
	}
}
