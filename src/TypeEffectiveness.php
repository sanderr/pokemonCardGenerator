<?php

class TypeEffectiveness {

	public function __construct($type, $resistant, $bonus) {
		$this->type = $type;
		$this->resistant = $resistant;
		$this->bonus = $bonus;
	}

	public function getType() {
		return $this->type;
	}

	public function getBonus() {
		return $this->bonus;
	}

}
