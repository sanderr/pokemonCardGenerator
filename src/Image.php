<?php

class Image {

	public function __construct($config_dir, $path_relative_to_config) {
		if (! $path_relative_to_config) {
			$this->path = "";
			return;
		}
		$this->path = $config_dir . "/" . $path_relative_to_config;
		if (! file_exists($this->getPath())) {
			throw new Exception("Image file " . $this->getPath() . " does not exist");
		}
	}

	public function getPath() {
		return $this->path;
	}

	public function getName() {
		return basename($this->path);
	}

}
