<?php

class Evolution {

	public function __construct($evolution_stage, $evolves_from, $evolution_image_file) {
		$this->setStage($evolution_stage);
		if ($this->getStage() === 0) {
			$this->evolves_from = "";
			$this->evolution_image_file = "";
		} else {
			$this->evolves_from = $evolves_from;
			$this->evolution_image_file = $evolution_image_file;
			if ($evolution_image_file && ! file_exists($evolution_image_file)) {
				throw new Exception("Evolution image file " . $evolution_image_file . " does not exist");
			}
		}
	}

	private function setStage($stageString) {
		switch ($stageString) {
			case "Base":
			case "base":
			case "0":
			case "":
				$stage = 0;
				break;
			case "First":
			case "first":
			case "1":
				$stage = 1;
				break;
			case "Second":
			case "second":
			case "2":
				$stage = 2;
				break;
			default:
				throw new Exception("invalid evolution stage: " . $stageString);
		}
		$this->stage = $stage;
	}

	public function getStage() {
		return $this->stage;
	}

	public function getPrevious() {
		return $this->evolves_from;
	}

	public function getPreviousImage() {
		return $this->evolution_image_file;
	}

	public function getStageString() {
		switch($this->stage) {
			case 0:
				return "BASE";
			case 1:
				return "STAGE1";
			case 2:
				return "STAGE2";
			default:
				throw new Exception("invalid evolution stage" . $this->stage);
		}
	}

}
