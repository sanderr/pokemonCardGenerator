<?php

class Attack {

	public function __construct($name, $description, $damage, $required_energies) {
		$this->name = $name;
		$this->description = $description;
		$this->damage = $damage;
		$this->required_energies = $required_energies;
		if (sizeof($this->required_energies) > self::MAX_NB_ENERGIES) {
			throw new Exception("Maximum amount of energies for an attack is " . self::MAX_NB_ENERGIES);
		}
	}

	private const MAX_NB_ENERGIES = 4;

	public function getName() {
		return $this->name;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getDamage() {
		return $this->damage;
	}

	public function getRequiredEnergies() {
		return $this->required_energies;
	}

}
