<?php

/**
  Скрипт для дешифровки паролей пользователей в Detrix. Пароли  хранятся
  в БД, шифрованные с помощью симметричного алгоритма BlowFish. Ключ для
  дешифровки находится в файле  %detrix_dir%/system/utils/MSF_string.php

  Konstantin Burov, L2W
**/

//Так же называется константа из файла  MSF_string.php ;-)
$sSuperDuperSecretKey =
	"!-eeflslskdjfla;456864~}{fjkdlswkfkll@#$%#$9f0sf8a723#@";

// Запрос пароля
$enc_pass = base64_decode(readline("Шифрованный пароль: "));

//Расшифровка и вывод
echo "Дешифрованый пароль: " . trim(openssl_decrypt($enc_pass, "BF-ECB",
	$sSuperDuperSecretKey, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING))
	. "\n";

?>
