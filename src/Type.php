<?php

class Type {

	public function __construct($name) {
		$this->setShortName($name);
	}

	private function setShortName($name) {
		$short_name = $name;
		switch ($name) {
			case "":
				$short_name = "";
				break;
			case "Fire":
			case "fire":
				$short_name = "feu";
				break;
			case "Grass":
			case "grass":
				$short_name = "plante";
				break;
			case "Water":
			case "water":
				$short_name = "eau";
				break;
			case "Lightning":
			case "lightning":
			case "Lighting":
			case "lighting":
			case "Electric":
			case "electric":
				$short_name = "elektrik";
				break;
			case "Psychic":
			case "psychic":
				$short_name = "psy";
				break;
			case "Fighting":
			case "fighting":
				$short_name = "combat";
				break;
			case "Colorless":
			case "colorless":
				$short_name = "incolore";
				break;
			case "Darkness":
			case "darkness":
			case "Dark":
			case "dark":
				$short_name = "obscure";
				break;
			case "Metal":
			case "metal":
			case "Steel":
			case "steel":
				$short_name = "metal";
				break;
			case "Dragon":
			case "dragon":
				$short_name = "dragon";
				break;
			default:
				throw new Exception("invalid type: " . $name);
		} //switch
		$this->short_name = $short_name;
	}

	public function getShortName() {
		return $this->short_name;
	}
}
