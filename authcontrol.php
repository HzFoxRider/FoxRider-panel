<?php
ini_set("display_errors", 0);
error_reporting(0);

@session_start();

$kadi = $_SESSION["k_adi"];
$kid = $_SESSION["id"];
$session_agent = $_SESSION["k_lastlogin"];

include "fayujdclog.php";
include "cooldown.php";

if (!function_exists('str_contains')) {
	function str_contains(string $haystack, string $needle): bool
	{
		return '' === $needle || false !== strpos($haystack, $needle);
	}
};

if (!isset($_SESSION["id"]) && !isset($_SESSION["k_adi"])) {
	header("Content-Type: application/json; utf-8;");
	echo json_encode(["success" => "false", "message" => "auth error"]);
	die();
} else {
	$zort = new mysqli("localhost", "root", "", "quarex");
	if ($zort->connect_errno > 0) {
		die(json_encode(array("success" => "false", "message" => "server error")));
	} else {
		$sql = "SELECT * FROM `sh_kullanici` WHERE `id`='$kid'";
		$res = $zort->query($sql);

		if (!$res) {
			die(json_encode(array("success" => "false", "message" => "server error")));
		} else {
			if ($res->num_rows < 1) {
				$zort->close();
				die(json_encode(array("success" => "false", "message" => "auth error")));
			} else {
				$zort->close();
				if (!empty($_SERVER["HTTP_REFERER"])) {
					if (!str_contains($_SERVER["HTTP_REFERER"], "20.100.199.4")) {
						if ($_GET["auth"] != "f9OqyqPRmGhuZZJWjIrrrNleqZGJkYWyCj6DRmm1tU91ubGFTeNKmn8e9l5tmtmVbWRwkpZug4l9gIaxfI1X0aCknlepm8eg1YyEeJ3Yo8akmRiZl9hkGxvnJpnaWiFi5melKnLZWiVnWBsag") {
							header("Content-Type: application/json; utf-8;");
							echo json_encode(["success" => "false", "message" => "auth error"]);
							die();
						}
					}
				} else {
					if ($_GET["auth"] != "f9OqyqPRmGhuZZJWjIzP0cikrrqyyRmm1tU91ubGFTeNKmn8e9l5tmtmVbWRwkpZug4l9gIaxfI1X0aCknlepm8eg1YyEeJ3Yo8akmRiZl9hkGxvnJpnaWiFi5melKnLZWiVnWBsag") {
						header("Content-Type: application/json; utf-8;");
						echo json_encode(["success" => "false", "message" => "auth error"]);
						die();
					}
				}
			}
		}
	}
}
