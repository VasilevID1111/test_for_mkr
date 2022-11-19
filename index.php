<?php
// Хостинг, Пользователь, Пароль, Имя базы данных
$connect = mysqli_connect('sql307.byethost6.com', 'b6_33035842', 'brigada123', 'b6_33035842_neighbours');
//$connect = mysqli_connect('localhost', 'root', '', 'neighbours');

if (!$connect) {
    die('Ошибка подключения к базе данных');
}
$connect->set_charset('utf8');
$sqluser = mysqli_query($connect, "SELECT * FROM `users`");

$usname = array();
$usrate = array();

while ($usrow = mysqli_fetch_array($sqluser)) {
    array_push($usname, $usrow["name"]);
}

for ($i = 0; $i < count($usname); $i++) {
	$rating = 0;
	$id = $i + 1;
	$score = array();
	$sqlrate = mysqli_query($connect, "SELECT * FROM `reviews` WHERE services_userid={$id}");
	
	while ($raterow = mysqli_fetch_array($sqlrate)) {
		array_push($score, $raterow["rate"]);
	}

	if (count($score) == 0) {
		array_push($usrate, 0);
	}
	else {
		for ($j = 0; $j < count($score); $j++) {
			$rating = $rating + $score[$j];
		}

		$rate = $rating / count($score);
		array_push($usrate, round($rate, 1));
	}
}
?>
