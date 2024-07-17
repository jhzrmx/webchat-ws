<?php
class HTML {
	public function __construct($title) {
		echo "<!DOCTYPE html>\n<html>\n<head>\n\t<meta charset=\"utf-8\">\n\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n\t<title>$title</title>\n";
	}

	public function addLink($rel, $href) {
		echo "\t<link rel=\"$rel\" href=\"$href\">\n";
	}

	public function addScript($src) {
		echo "\t<script src=\"$src\"></script>\n";
	}

	public function startBody() {
		echo "</head>\n<body>\n";
	}

	public function endBody() {
		echo "</body>\n</html>\n";
	}
}
?>