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
		
		for ($i = 0; $i < count($readtype); $i++) {
			$getuserid = mysqli_query($connect, "SELECT name FROM `users` WHERE id={$readuserid[$i]}");
			$getuserrow = mysqli_fetch_array($getuserid);
			echo "<div id='openModal?Card={$i}' class='modal'>
			<div class='modal-dialog2'>
				<div class='modal-content'>
					<div class='modal-header'>
						<h3 class='modal-title'>{$readcategories[$i]}</h3>
						<a href='#close' onclick='changeText2(this)' title='' class='close'>×</a>
					</div>
					<div class='modal-body'>  
						<article class='popup-card'>  
							<a class='a-card' href='#openProfile?Profile={$i}' ><img class='img_card' src='{$readimage_url[$i]}'></a>
							<font style='margin-left: 5%;' class='text_card'>	
								<h4 class='modal-title'>{$readname[$i]}</h4>
								<h4 class='modal-title'>{$getuserrow[0]}</h4>
								<p class='modal-text'>{$readdescription[$i]}</p>
							</font>
						</article>
						<article class='body_card'> 
							<font class='text_card'> <br>
								<p>Категория: <font class='modal-category'>{$readcategories[$i]}</font></p>
								<p>Стоимость: <font class='modal-price'>{$readcost[$i]}</font></p>
							</font>
							<p class='success'>	
								<img class='img-success' style='display: none;' src='imgs/icons-success.png'>
								<button class='btn' onclick='changeText1(this)' data-show='true'>Выбрать</button>
							</p>
						</article>
					</div>
				</div>
			</div>
			</div>";
			
			$getuser = mysqli_query($connect, "SELECT * FROM `users` WHERE id={$readuserid[$i]}");
			$usimage_url = array();
			$usdescription = array();
			$usspecialization = array();
			$usskills = array();
	
			while ($userrow = mysqli_fetch_array($getuser)) {
				array_push($usimage_url, $userrow["image_url"]);
				array_push($usdescription, $userrow["description"]);
				array_push($usspecialization, $userrow["specialization"]);
				array_push($usskills, $userrow["skills"]);
			}
	
			$newcardid = $readuserid[$i] - 1;
			if ($usrate[$newcardid] != 0) {
				$setcardrate = $usrate[$newcardid];
			}
			else {
				$setcardrate = '-';
			}
	
			$sqlreview = mysqli_query($connect, "SELECT * FROM `reviews` WHERE services_userid={$readuserid[$i]}");
			$rerecipient_userid = array();
			$retext = array();
			$redate = array();
			$rerate = array();
			$reagree = array();
	
			while ($reraterow = mysqli_fetch_array($sqlreview)) {
				array_push($rerecipient_userid, $reraterow["recipient_userid"]);
				array_push($retext, $reraterow["text"]);
				array_push($redate, $reraterow["date"]);
				array_push($rerate, $reraterow["rate"]);
				array_push($reagree, $reraterow["agree"]);
			}
	
			$complete = count($rerate);
	
			echo "<div id='openProfile?Profile={$i}' class='modal'>
			<div class='modal-dialog'>
				<div class='modal-content'>
					  <div class='modal-header'>
						<a href='#close' onclick='changeText2(this)' title='' class='close'>×</a>
					  </div>
					  <div class='modal-body'>  
						  <article class='popup-card'>  
							<article class='body_card'> 
								<img class='img_user' src='{$usimage_url[0]}'>
								<font class='text_card'> <br>
									<center>Выполненные заказы:</center> <p class='modal-orders'>{$complete}</p>
									<center>Оценка:</center><p class='modal-star'>{$setcardrate}/5</p>";
			
			if (count($reagree) != 0) {
				$most = max($reagree) - 1;
				$regetuser = mysqli_query($connect, "SELECT name FROM `users` WHERE id={$rerecipient_userid[$most]}");
				$regetuserrow = mysqli_fetch_array($regetuser);
				echo "Популярный отзыв:<p class='modal-review'>{$retext[$most]}</p>
				<p class='modal-review-author'>by {$regetuserrow[0]}</p>";
			}
	
			echo "</font>
							</article>
							<font style='margin-left: 10%;' class='text_card'>	
								<h4 class='modal-title'>{$getuserrow[0]}</h4>
								<p class='modal-text'>Основная специализация: {$usspecialization[0]}</p> <br>
								<p class='modal-skills'>ОПИСАНИЕ: {$usdescription[0]} <br> НАВЫКИ: {$usspecialization[0]}</p>
								<p class='modal-reviews'>Отзывы:</p>";
	
			if (count($reagree) != 0) {
				$most = max($reagree) - 1;
				for ($j = 0; $j < count($reagree); $j++) {
					$reusname = $usname[$rerecipient_userid[$j] - 1];
					echo "<p class='modal-review-user'><b>{$reusname}</b></p>
									<p class='modal-review-data'>($redate[$j])</p>
									<p class='modal-review-text'>{$retext[$j]}</p>
									<p class='modal-review-text'>Оценка: {$rerate[$j]}/5</p>";
	
				}
			}
			else {
				echo "<p class='modal-review-text'>Отзывов пока нет.</p>";
			}
								
			echo "</font>
						</article>
					  </div>
				</div>
			  </div>
			</div>";
		}
	}
	else {

		$category = $_GET['category'];
		$catrequest = "(categories='{$category[0]}'";
		for ($i = 1; $i < count($category); $i++) {
			$catrequest .= " OR categories='{$category[$i]}'";
		}
		$catrequest .= ")";

		if(strlen($_GET["search"]) == 0) {

			$gettype = $_GET['gettype'];
			if ($gettype == 'Все') {
				$sqlrequestadvert = mysqli_query($connect, "SELECT * FROM `adverts` WHERE {$catrequest}");
			}
			else {
			$sqlrequestadvert = mysqli_query($connect, "SELECT * FROM `adverts` WHERE (type='{$gettype}') AND {$catrequest}");
			}

		}
		else {

			$search = $_GET["search"];
			$gettype = $_GET['gettype'];

			if ($gettype == 'Все') {
				$sqlrequestadvert = mysqli_query($connect, "SELECT * FROM `adverts` WHERE {$catrequest} AND (name LIKE '%{$search}%' OR description LIKE '%{$search}%')");
			}
			else {
				$sqlrequestadvert = mysqli_query($connect, "SELECT * FROM `adverts` WHERE (type='{$gettype}') AND {$catrequest} AND (name LIKE '%{$search}%' OR description LIKE '%{$search}%')");
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
		
		for ($i = 0; $i < count($readtype); $i++) {
			$getuserid = mysqli_query($connect, "SELECT name FROM `users` WHERE id={$readuserid[$i]}");
			$getuserrow = mysqli_fetch_array($getuserid);
			echo "<div id='openModal?Card={$i}' class='modal'>
			<div class='modal-dialog2'>
				<div class='modal-content'>
					<div class='modal-header'>
						<h3 class='modal-title'>{$readcategories[$i]}</h3>
						<a href='#close' onclick='changeText2(this)' title='' class='close'>×</a>
					</div>
					<div class='modal-body'>  
						<article class='popup-card'>  
							<a class='a-card' href='#openProfile?Profile={$i}' ><img class='img_card' src='{$readimage_url[$i]}'></a>
							<font style='margin-left: 5%;' class='text_card'>	
								<h4 class='modal-title'>{$readname[$i]}</h4>
								<h4 class='modal-title'>{$getuserrow[0]}</h4>
								<p class='modal-text'>{$readdescription[$i]}</p>
							</font>
						</article>
						<article class='body_card'> 
							<font class='text_card'> <br>
								<p>Категория: <font class='modal-category'>{$readcategories[$i]}</font></p>
								<p>Стоимость: <font class='modal-price'>{$readcost[$i]}</font></p>
							</font>
							<p class='success'>	
								<img class='img-success' style='display: none;' src='imgs/icons-success.png'>
								<button class='btn' onclick='changeText1(this)' data-show='true'>Выбрать</button>
							</p>
						</article>
					</div>
				</div>
			</div>
			</div>";
			
			$getuser = mysqli_query($connect, "SELECT * FROM `users` WHERE id={$readuserid[$i]}");
			$usimage_url = array();
			$usdescription = array();
			$usspecialization = array();
			$usskills = array();
	
			while ($userrow = mysqli_fetch_array($getuser)) {
				array_push($usimage_url, $userrow["image_url"]);
				array_push($usdescription, $userrow["description"]);
				array_push($usspecialization, $userrow["specialization"]);
				array_push($usskills, $userrow["skills"]);
			}
	
			$newcardid = $readuserid[$i] - 1;
			if ($usrate[$newcardid] != 0) {
				$setcardrate = $usrate[$newcardid];
			}
			else {
				$setcardrate = '-';
			}
	
			$sqlreview = mysqli_query($connect, "SELECT * FROM `reviews` WHERE services_userid={$readuserid[$i]}");
			$rerecipient_userid = array();
			$retext = array();
			$redate = array();
			$rerate = array();
			$reagree = array();
	
			while ($reraterow = mysqli_fetch_array($sqlreview)) {
				array_push($rerecipient_userid, $reraterow["recipient_userid"]);
				array_push($retext, $reraterow["text"]);
				array_push($redate, $reraterow["date"]);
				array_push($rerate, $reraterow["rate"]);
				array_push($reagree, $reraterow["agree"]);
			}
	
			$complete = count($rerate);
	
			echo "<div id='openProfile?Profile={$i}' class='modal'>
			<div class='modal-dialog'>
				<div class='modal-content'>
					  <div class='modal-header'>
						<a href='#close' onclick='changeText2(this)' title='' class='close'>×</a>
					  </div>
					  <div class='modal-body'>  
						  <article class='popup-card'>  
							<article class='body_card'> 
								<img class='img_user' src='{$usimage_url[0]}'>
								<font class='text_card'> <br>
									<center>Выполненные заказы:</center> <p class='modal-orders'>{$complete}</p>
									<center>Оценка:</center><p class='modal-star'>{$setcardrate}/5</p>";
			
			if (count($reagree) != 0) {
				$most = max($reagree) - 1;
				$regetuser = mysqli_query($connect, "SELECT name FROM `users` WHERE id={$rerecipient_userid[$most]}");
				$regetuserrow = mysqli_fetch_array($regetuser);
				echo "Популярный отзыв:<p class='modal-review'>{$retext[$most]}</p>
				<p class='modal-review-author'>by {$regetuserrow[0]}</p>";
			}
	
			echo "</font>
							</article>
							<font style='margin-left: 10%;' class='text_card'>	
								<h4 class='modal-title'>{$getuserrow[0]}</h4>
								<p class='modal-text'>Основная специализация: {$usspecialization[0]}</p> <br>
								<p class='modal-skills'>ОПИСАНИЕ: {$usdescription[0]} <br> НАВЫКИ: {$usspecialization[0]}</p>
								<p class='modal-reviews'>Отзывы:</p>";
	
			if (count($reagree) != 0) {
				$most = max($reagree) - 1;
				for ($j = 0; $j < count($reagree); $j++) {
					$reusname = $usname[$rerecipient_userid[$j] - 1];
					echo "<p class='modal-review-user'><b>{$reusname}</b></p>
									<p class='modal-review-data'>($redate[$j])</p>
									<p class='modal-review-text'>{$retext[$j]}</p>
									<p class='modal-review-text'>Оценка: {$rerate[$j]}/5</p>";
	
				}
			}
			else {
				echo "<p class='modal-review-text'>Отзывов пока нет.</p>";
			}
								
			echo "</font>
						</article>
					  </div>
				</div>
			  </div>
			</div>";
		}

	}
	
	?>
</section>

	</div>

	<footer>
		Lorem ipsum dolor sit amet © 2022
	</footer>
</body>
</html>
<script type="text/javascript">
	function changeText1(ev) {
    if(document.getElementsByClassName('btn')[0].getAttribute('data-show') === "true") {
        document.getElementsByClassName('btn')[0].innerText = "Перейти в диалог";
        document.getElementsByClassName('btn')[0].setAttribute('data-show', "false"); 
        document.getElementsByClassName('img-success')[0].style.display = "inline";
    }
}
function changeText2(ev) {
	if(document.getElementsByClassName('btn')[0].getAttribute('data-show') === "false") {
        document.getElementsByClassName('btn')[0].innerText = "Выбрать";

        document.getElementsByClassName('btn')[0].setAttribute('data-show', "true"); 
        document.getElementsByClassName('img-success')[0].style.display = "none";
    }
}

</script>