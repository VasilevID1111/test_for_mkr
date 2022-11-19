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

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Соседи</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<header>
		<nav class="nav">
			<div class="list">
			<img class="logo" src="imgs/logo-bg2.png"></img>
			<ul class="nav-list">
				<li class="nav-item"><a href="" class="nav-link"><img src="imgs/icon-notifications-active.png"></a></li>
				<li class="nav-item"><a href="" class="nav-link"><img src="imgs/icons-messages.png"></a></li>
				<li class="nav-item"><a href="" class="nav-link"><img src="imgs/icons-plus.png"></a></li>
				<li class="nav-item"><a href="" class="nav-link"><img src="imgs/icons-user.png"></a></li>
			</ul>
			</div>
		</nav>
	</header>

	<form method="get">
	<div class="div-search">
		<div class="form-search">
			<select name="gettype" id="gettype" class="sel-search">
				<option value="Все">Все</option>
				<option value="Заявки">Заявки</option>
				<option value="Объявления">Объявления</option>
			</select>
		  <input class="inp-search" name="search" placeholder="Искать..." type="search">
		  <button class="but-search" name="submit" id="submit" type="submit"><img src="imgs/icons-search.png"></button>
		</div>
	</div>

	<div class="main-page">	
		<section class="categories">
			<!-- <form method="get"> -->
				<p><input type="checkbox" name="category[]" value="Компьютерная помощь"> Компьютерная помощь</p>
				<p><input type="checkbox" name="category[]" value="Автомобильная помощь"> Автомобильная помощь</p>
				<p><input type="checkbox" name="category[]" value="Швейные услуги"> Швейные услуги</p>
				<p><input type="checkbox" name="category[]" value="Услуги сантехника"> Услуги сантехника</p>
			<!-- </form> -->
		</section>
	</form>
	
<section class="cards">

	<?php

	if(!isset($_GET["category"])) {

		if(strlen($_GET["search"]) == 0) {

			$gettype = $_GET['gettype'];
			if ($gettype == 'Все') {
				$sqlrequestadvert = mysqli_query($connect, "SELECT * FROM `adverts`");
			}
			else {
				$sqlrequestadvert = mysqli_query($connect,  "SELECT * FROM `adverts` WHERE type='{$gettype}'");
			}

		}
		else {

			$search = $_GET["search"];
			$gettype = $_GET['gettype'];

			if ($gettype == 'Все') {
				$sqlrequestadvert = mysqli_query($connect,  "SELECT * FROM `adverts` WHERE name LIKE '%{$search}%' OR description LIKE '%{$search}%'");
			}
			else {
				$sqlrequestadvert = mysqli_query($connect, "SELECT * FROM `adverts` WHERE (type='{$gettype}') AND (name LIKE '%{$search}%' OR description LIKE '%{$search}%')");
			}

		}
	
		$readuserid = array();
		$readtype = array();
		$readcategories = array();
		$readname = array();
		$readdescription = array();
		$readimage_url = array();
		$readcost = array();
	
		while ($readrow = mysqli_fetch_array($sqlrequestadvert)) {
			array_push($readuserid, $readrow["userid"]);
			array_push($readname, $readrow["name"]);
			array_push($readdescription, $readrow["description"]);
			array_push($readcost, $readrow["cost"]);
			array_push($readimage_url, $readrow["image_url"]);
			array_push($readcategories, $readrow["categories"]);
	
			if ($readrow["type"] == 'Заявки') {
				array_push($readtype, 'З');
			}
			else {
				array_push($readtype, 'О');
			}
		}
		
		echo "<section class='three-elems'>";
	
		for ($i = 0; $i < count($readtype); $i++) {
			$newid = $readuserid[$i] - 1;
	
			if ($usrate[$newid] != 0) {
				$setrate = $usrate[$newid];
			}
			else {
				$setrate = '-';
			}
	
			if (($i + 1) % 3 == 0) {
				echo "<article class='ad_card'>
					<a class='a-card' href='#openModal?Card={$i}'>
						<img class='img_card' src='{$readimage_url[$i]}'>
						<font class='text_card'>
							<h4>{$readname[$i]}</h4>
							<p>{$readdescription[$i]}</p>
						</font>
					</a>
					<font>
						<h4 class='star'>{$setrate}</h4>
						<p class='type'>{$readtype[$i]}</p>
					</font>	
				</article>
				</section>
				<section class='three-elems'>";
			} else {
				echo "<article class='ad_card'>
					<a class='a-card' href='#openModal?Card={$i}'>
						<img class='img_card' src='{$readimage_url[$i]}'>
						<font class='text_card'>
							<h4>{$readname[$i]}</h4>
							<p>{$readdescription[$i]}</p>
						</font>
					</a>
					<font>
						<h4 class='star'>{$setrate}</h4>
						<p class='type'>{$readtype[$i]}</p>
					</font>	
				</article>";
			}
		}
	
		if (count($readtype) % 3 != 0) {
			echo "</section>";
		}
	