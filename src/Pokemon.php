<?php

class Pokemon {

	public function __construct($name, $type, $hitpoints, $weakness, $resistance, $evolution, $attack_one, $attack_two, $retreat_cost, $image) {
		$this->name = $name;
		$this->type = $type;
		$this->hitpoints = $hitpoints;
		$this->weakness = $weakness;
		$this->resistance = $resistance;
		$this->evolution = $evolution;
		$this->attack_one = $attack_one;
		$this->attack_two = $attack_two;
		$this->retreat_cost = $retreat_cost;
		$this->image = $image;
		if ($this->image && ! file_exists($this->image)) {
			throw new Exception("Image file " . $this->image . " does not exist");
		}
		$this->initializeSession();
	}

	private const MAX_RETREAT_COST = 4;
	private const URL = "http://www.mypokecard.com/en/";
	private const SESSION_COOKIE_NAME = "PHPSESSID";

	private function initializeSession() {
		$session = curl_init(self::URL);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($session, CURLOPT_HEADER, 1);
		$result = curl_exec($session);
		curl_close($session);
		preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
		$cookies = array();
		foreach($matches[1] as $item) {
		    parse_str($item, $cookie);
		    $cookies = array_merge($cookies, $cookie);
		}
		if (! isset($cookies[self::SESSION_COOKIE_NAME])) {
			die("Error: something went wrong trying to get the session cookie\n");
		}
		$this->session_id = $cookies[self::SESSION_COOKIE_NAME];
	}

	public function getName() {
		return $this->name;
	}

	public function getType() {
		return $this->type;
	}

	public function getEvolution() {
		return $this->evolution;
	}

	public function getAttackOne() {
		return $this->attack_one;
	}

	public function getAttackTwo() {
		return $this->attack_two;
	}

	public function getImage() {
		return $this->image;
	}

	public function getHitpoints() {
		return $this->hitpoints;
	}

	public function getWeakness() {
		return $this->weakness;
	}

	public function getResistance() {
		return $this->resistance;
	}

	public function getRetreatCost() {
		return $this->retreat_cost;
	}

	private const SERVER_IMAGE_SRC = "xQjk35aAob.jpg";
	private const FIRST_GEN_CARD_TYPE = "ex";

	public function downloadImage($save_directory) {
		$post_data = array();
		$post_data["img_src"] = self::SERVER_IMAGE_SRC;
		$post_data["card_id"] = "";
		$post_data["card_type"] = self::FIRST_GEN_CARD_TYPE;
		$post_data["background"] = $this->getType()->getShortName();
		$post_data["niveau"] = $this->getEvolution()->getStageString();
		$post_data["evolution"] = $this->getEvolution()->getPrevious();
		if ($this->getEvolution()->getPreviousImage()) {
			// upload file
			$filename = $this->getEvolution()->getPreviousImage();
			$img_post_data = array();
			$img_post_data["fileToUpload"] = new CURLFile($filename, "image/jpeg", $filename);
			$session = curl_init(self::URL . "my/doajaxfileupload.php?id=fileToUpload");
			curl_setopt($session, CURLOPT_COOKIE, self::SESSION_COOKIE_NAME . "=" . $this->session_id);
			curl_setopt($session, CURLOPT_POST, true);
			curl_setopt($session, CURLOPT_POSTFIELDS, $img_post_data);
			curl_exec($session);
			curl_close($session);
			$post_data["image_file"] = $filename;
			$post_data["evol_image_file"] = $filename;
		}
		$post_data["name"] = $this->getName();
		$post_data["attaque1"] = $this->getAttackOne()->getName();
		$post_data["attaque1_desc"] = $this->getAttackOne()->getDescription();
		$post_data["degats1"] = $this->getAttackOne()->getDamage();
		$post_data["attaque2"] = $this->getAttackTwo()->getName();
		$post_data["attaque2_desc"] = $this->getAttackTwo()->getDescription();
		$post_data["degats2"] = $this->getAttackTwo()->getDamage();
		if ($this->getImage()) {
			// upload file
			$filename = $this->getImage();
			$img_post_data = array();
			$img_post_data["fileToUpload"] = new CURLFile($filename, "image/jpeg", $filename);
			$session = curl_init(self::URL . "my/doajaxfileupload.php?id=fileToUpload");
			curl_setopt($session, CURLOPT_COOKIE, self::SESSION_COOKIE_NAME . "=" . $this->session_id);
			curl_setopt($session, CURLOPT_POST, true);
			curl_setopt($session, CURLOPT_POSTFIELDS, $img_post_data);
			curl_exec($session);
			curl_close($session);
			$post_data["image_file"] = $filename;
		}
		$post_data["points_vie"] = $this->getHitpoints();
		$energiesOne = $this->getAttackOne()->getRequiredEnergies();
		$post_data["nrj1_pos"] = 1 + sizeof($energiesOne);
		for ($i = 0; $i < sizeof($energiesOne); $i++) {
			$j = $i + 1;
			$post_data["nrj1_$j"] = "my/fonds/nrj/" . $energiesOne[$i]->getShortName() . ".png";
		}
		$energiesTwo = $this->getAttackTwo()->getRequiredEnergies();
		$post_data["nrj2_pos"] = 1 + sizeof($energiesTwo);
		for ($i = 0; $i < sizeof($energiesTwo); $i++) {
			$j = $i + 1;
			$post_data["nrj2_$j"] = "my/fonds/nrj/" . $energiesTwo[$i]->getShortName() . ".png";
		}
		$post_data["faiblesse"] = $this->getWeakness()->getType()->getShortName();
		$post_data["faiblesse_opt"] = $this->getWeakness()->getBonus();
		$post_data["resistance"] = $this->getResistance()->getType()->getShortName();
		$post_data["resistance_opt"] = $this->getResistance()->getBonus();
		$post_data["retraite"] = $this->getRetreatCost();

		// send update POST request
		$session = curl_init(self::URL . "update.php");
		curl_setopt($session, CURLOPT_COOKIE, self::SESSION_COOKIE_NAME . "=" . $this->session_id);
		curl_setopt($session, CURLOPT_POST, true);
		curl_setopt($session, CURLOPT_POSTFIELDS, $post_data);
		curl_exec($session);
		curl_close($session);

		// send image GET request
		$session = curl_init(self::URL . "my/tmp/" . self::SERVER_IMAGE_SRC);
		curl_setopt($session, CURLOPT_COOKIE, self::SESSION_COOKIE_NAME . "=" . $this->session_id);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
		$image_data = curl_exec($session);

		// save image
		$destination = $save_directory . "/" . $this->getName() . ".jpg";
		$file = fopen($destination, "w");
		fputs($file, $image_data);
		fclose($file);
	} //downloadImage()

} //Pokemon
