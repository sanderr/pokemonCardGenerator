#!/usr/bin/env php
<?php
require_once("Type.php");
require_once("Weakness.php");
require_once("Resistance.php");
require_once("Evolution.php");
require_once("Attack.php");
require_once("Pokemon.php");
require_once("Image.php");

function createType($name) {
	return new Type($name);
}

$global_config = parse_ini_file("config.ini");
$card_config_directory = $global_config["card_config_directory"];
$card_output_directory = $global_config["card_output_directory"];
mkdir($card_output_directory);

$card_config_files = glob($card_config_directory . "/*.ini");
foreach($card_config_files as $config_file) {
	try {
		$config = parse_ini_file($config_file, true);
		$section_general = $config["general"];
		$section_evolution = $config["evolution"];
		$section_attacks = $config["attacks"];

		$name = $section_general["name"];
		$type = new Type($section_general["type"]);
		$hitpoints = (int) $section_general["hitpoints"];
		$retreat_cost = (int) $section_general["retreat_cost"];
		$image_file = new Image($card_config_directory, $section_general["image_file"]);
		$weakness = new Weakness(new Type($section_general["weakness_type"]), $section_general["weakness_impact"]);
		$resistance = new Resistance(new Type($section_general["resistance_type"]), $section_general["resistance_impact"]);
		$evolution = new Evolution($section_evolution["evolution_stage"], $section_evolution["evolves_from"], new Image($card_config_directory, $section_evolution["evolution_image_file"]));
		if ($section_attacks["attack_one_energy"]) {
			$section_one_energies = array_map("createType", explode(",", $section_attacks["attack_one_energy"]));
		} else {
			$section_one_energies = array();
		}
		if ($section_attacks["attack_two_energy"]) {
			$section_two_energies = array_map("createType", explode(",", $section_attacks["attack_two_energy"]));
		} else {
			$section_two_energies = array();
		}
		$attack_one = new Attack($section_attacks["attack_one"], $section_attacks["attack_one_description"], $section_attacks["attack_one_damage"], $section_one_energies);
		$attack_two = new Attack($section_attacks["attack_two"], $section_attacks["attack_two_description"], $section_attacks["attack_two_damage"], $section_two_energies);

		$pokemon = new Pokemon($name, $type, $hitpoints, $weakness, $resistance, $evolution, $attack_one, $attack_two, $retreat_cost, $image_file);
		$pokemon->downloadImage($card_output_directory);
	} catch (Exception $e) {
		echo "Something went wrong with " . $config_file . ":\n\t" . $e->getMessage() . "\n";
	}
} //foreach
