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
	<div class="page">	
	<header>
		<nav class="nav">
			<div class="list">
			<div class="logo-div">
					<img class="logo" src="imgs/logo-bg2.svg"></img>
					<p class="logo-text">Соседи</p>
			</div>
			<ul class="nav-list">
				<li class="nav-item">
					<a href="" class="nav-link"><img class='img-nav' src="imgs/icon-notifications.svg">
						
					</a>
				</li>
				<li class="nav-item"><a href="" class="nav-link"><img class='img-nav' src="imgs/icons-messages.svg"></a></li>
				<li class="nav-item"><a href="#openAdd" class="nav-link"><img class='img-nav' src="imgs/icons-plus.svg"></a></li>
				<li class="nav-item"><a href="" class="nav-link"><img class='img-nav' src="imgs/icons-user.svg"></a></li>
			</ul>
			</div>
		</nav>
	</header>

	<form method="get">
	<div class="div-search">
		<div class="form-search">
			<select name="gettype" id="gettype" class="select-search">
				<option value="Все">Все</option>
				<option value="Заявки">Заявки</option>
				<option value="Объявления">Объявления</option>
			</select>
			
		</div>

		<div class="search">
		  <input class="inp-search" name="search" placeholder="" type="search" autocomplete="off">
		  <button class="but-search" name="getinfo" id="submit" type="submit"><img class='img-search' src="imgs/icons-search.png"></button>
		</div>

		<div class="radio-wrapper">
		    <p class="correct"><i class="ion-checkmark-round"></i>
		    </p>
		    <p class="neutral-icon"><i class="ion-record"></i>
		    </p>
		    <p class="wrong"><i class="ion-close-round"></i></p>

		    <input checked type="radio" name="event" class="yes" id="radio-yes" />
		    <label for="radio-yes"></label>

		    <input type="radio" name="event" class="no" id="radio-no" />
		    <label for="radio-no"></label>
		  </div>
	</div>
	
	
		
	
	<div class="main-page">	
		<section class="categories">
			<!-- <form method="get"> -->
				<p class="category-text"> 
					<a class="category-a" type="comp" tag="false" onclick="changeColorText(this)">
						Компьютерная помощь <input class="category-inp" type="checkbox" name="category[]" value="Компьютерная помощь">
					</a>
				</p>
				<p class="category-text"> <a class="category-a"  type="auto" tag="false" onclick="changeColorText(this)">Автомобильная помощь <input class="category-inp" type="checkbox" name="category[]" value="Автомобильная помощь"></a></p>
				<p class="category-text"> <a class="category-a" type="sewing" tag="false" onclick="changeColorText(this)">Швейные услуги <input class="category-inp" type="checkbox" name="category[]" value="Швейные услуги"></a></p>
				<p class="category-text"> <a class="category-a" type="plumber" tag="false" onclick="changeColorText(this)">Услуги сантехника <input class="category-inp" type="checkbox" name="category[]" value="Услуги сантехника"></a></p>
			<!-- </form> -->
		</section>
	</form>
	<section class="cards">
	<?php

	if(isset($_GET['getinfo'])) {       // при нажатии на кнопку

		if(!isset($_GET["category"])) {

			if(strlen($_GET["search"]) == 0) {

				$gettype = $_GET['gettype'];
				if ($gettype == 'Все') {
					$sqlrequestadvert = mysqli_query($connect, "SELECT * FROM `adverts`");
				}
				else {
					$sqlrequestadvert = mysqli_query($connect, "SELECT * FROM `adverts` WHERE type='{$gettype}'");
				}

			}
			else {

				$search = $_GET["search"];
				$gettype = $_GET['gettype'];

				if ($gettype == 'Все') {
					$sqlrequestadvert = mysqli_query($connect, "SELECT * FROM `adverts` WHERE name LIKE '%{$search}%' OR description LIKE '%{$search}%'");
				}
				else {
					$sqlrequestadvert = mysqli_query($connect, "SELECT * FROM `adverts` WHERE (type='{$gettype}') AND (name LIKE '%{$search}%' OR description LIKE '%{$search}%')");
				}

			}
		
			$readid = array();
			$readuserid = array();
			$readtype = array();
			$readcategories = array();
			$readname = array();
			$readdescription = array();
			$readimage_url = array();
			$readcost = array();
		
			while ($readrow = mysqli_fetch_array($sqlrequestadvert)) {
				array_push($readid, $readrow["id"]);
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

			echo "<section class='two-elems'>";
		
			for ($i = 0; $i < count($readtype); $i++) {
				$newid = $readuserid[$i] - 1;
		
				if ($usrate[$newid] != 0) {
					$setrate = $usrate[$newid];
				}
				else {
					$setrate = '-';
				}
				if (($i + 1) % 2 == 0) {
					$type = "";
					if($readtype[$i] == "З")
						$type = "Помочь";
					else
						$type = "Написать";
					echo "<article class='ad_card'>
						<div class='div_ad_card'>
							<div class='profile-star'>
								<img class='profile_img_card' src='{$readimage_url[$i]}'>
								<div class='div-card-img'><img class='img-star' src='imgs/icons-star.svg'><p class='star'>{$setrate}</p></div>
								
							</div>
							<font class='text_card'>
								<div class='card-title'>
									<p class='text'>{$readname[$i]}</p>
									<div class='card-imgs'> 
										<form method='post'>
											<a tag='false' onclick='changeImg(this)'><img class='img_like' src='imgs/icon-like.png'></a>
											<a href='#openEdit?Card={$readid[$i]}'><img class='img_edit' src='imgs/icon-edit.svg'></a>
											<button name='deleteinfo' value='{$readid[$i]}' type='submit' onclick='return  confirm(`Вы уверены удалить карточку №{$readid[$i]} \"$readname[$i]\"?`)'><img class='img_delete' src='imgs/icon-delete.svg'></button>
										</form>
									</div>
								</div>
							<p class='p-card-desc'>{$readdescription[$i]}</p>
							<div class='price-type'><p class='p-card'>Тип Какой-то тип</p> <p class='p-card-type'>Цена руб.</p></div>

								
							
							</font>
							</div>
						<div class='date-and-type'>
								<p class='text_date'>вчера</p>
								<a class='a-card' href='#openModal?Card={$readid[$i]}'><p class='type'>{$type}</p></a>
						</div>
					</article>
					</section>
					<section class='two-elems'>";
				} else {
					$type = "";
					if($readtype[$i] == "З")
						$type = "Помочь";
					else
						$type = "Написать";
					echo "<article class='ad_card'>
							<div class='div_ad_card'>
							<div class='profile-star'>
								<img class='profile_img_card' src='{$readimage_url[$i]}'>
								<div class='div-card-img'><img class='img-star' src='imgs/icons-star.svg'><p class='star'>{$setrate}</p></div>
							</div>
							<font class='text_card'>
								<div class='card-title'>
									<p class='text'>{$readname[$i]}</p>
									<div class='card-imgs'>
										<form method='post'>
											<a tag='false' onclick='changeImg(this)'><img class='img_like' src='imgs/icon-like.png'></a>
											<a href='#openEdit?Card={$readid[$i]}'><img class='img_edit' src='imgs/icon-edit.svg'></a>
											<button name='deleteinfo' value='{$readid[$i]}' type='submit' onclick='return  confirm(`Вы уверены удалить карточку №{$readid[$i]} \"$readname[$i]\"?`)'><img class='img_delete' src='imgs/icon-delete.svg'></button>
										</form>
									</div>
								</div>
							<p class='p-card-desc'>{$readdescription[$i]}</p>
							<div class='price-type'><p class='p-card'>Тип Какой-то тип</p> <p class='p-card-type'>Цена руб.</p></div>
							

							</font>
							</div>
						<div class='date-and-type'>
								<p class='text_date'>вчера</p>
								<a class='a-card' href='#openModal?Card={$readid[$i]}'><p class='type'>{$type}</p></a>
							</div>
						
					</article>";
				}
			}
		
			if (count($readtype) % 2 != 0) {
				echo "</section>";
			}
			
			for ($i = 0; $i < count($readtype); $i++) {
				$getuserid = mysqli_query($connect, "SELECT name FROM `users` WHERE id={$readuserid[$i]}");
				$getuserrow = mysqli_fetch_array($getuserid);
				echo "<div id='openModal?Card={$readid[$i]}' class='modal'>
				<div class='modal-dialog2'>
					<div class='modal-content'>
						<div class='modal-header'>
							<h3 class='modal-title'>{$readcategories[$i]}</h3>
							<a href='#close' onclick='changeText2(this)' title='' class='close'>×</a>
						</div>
						<div class='modal-body'>  
							<article class='popup-card'>  
								<a class='a-card' href='#openProfile?Profile={$readid[$i]}' ><img class='img_card' src='{$readimage_url[$i]}'></a>
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
		
									<button class='btn' data-show='true'>Выбрать</button><img class='img-success' style='display: none;' src='imgs/icons-success.png'>
								</p>
							</article>
						</div>
					</div>
				</div>
				</div>";

				if ($readtype[$i] == 'З') {
					$typeoption1 = "Прошу помощи";
					$typeoption2 = "Помочь";
				}
				else {
					$typeoption1 = "Помочь";
					$typeoption2 = "Прошу помощи";
				}

				$catlist = array("Автомобильная помощь", "Компьютерная помощь", "Швейные услуги", "Услуги сантехника");
				$typecat1 = $readcategories[$i];
				$outlist = array();
				for ($j = 0; $j < count($catlist); $j++) {
					if($catlist[$j] != $readcategories[$i]) {
						array_push($outlist, $catlist[$j]);
					}
				}
				$typecat2 = $outlist[0];
				$typecat3 = $outlist[1];
				$typecat4 = $outlist[2];

				echo "<div id='openEdit?Card={$readid[$i]}' class='modal'>
				<div class='modal-dialog2'>
					<div class='modal-content'>
						<div class='modal-header'>
							<h3 class='modal-title'>Редактирование</h3>
							<a href='?gettype=%D0%92%D1%81%D0%B5&amp;search=&amp;submit=#close' onclick='changeText2(this)' title='' class='close'>×</a>
						</div>
						<div class='modal-body'>  
							<form method='post' enctype='multipart/form-data'>
								<article class='popup-card'>  
									<div class='img_card' style='background-size: cover; background-image: url({$readimage_url[$i]});'><input class='inp_card' type='file' name='gettypeimg'>
									<p class='uploadphoto' id='uploadphoto'>Новое фото не загружено</p></div>

									<font class='text_card'> 
										Вид объявления: 
										<select name='gettypeopt' id='gettypeopt' >
											<option value='{$typeoption1}'>{$typeoption1}</option>
											<option value='{$typeoption2}'>{$typeoption2}</option>
										</select>
										<p>Название: <font class='modal-category'> <input type='text' name='gettypename' value='{$readname[$i]}' class='inp-text' required/> </font></p>
										Описание:<p style='margin-top: 2%;'> <font class='modal-price'> <textarea type='text' name='gettypedesc' class='inp-text' required> {$readdescription[$i]}</textarea> </font></p>
									</font>
								</article>
								<article class='body_card'> 
									<font class='text_card'> <br>
										<p>Категория: <font class='modal-category'> 
										<select name='gettypecat' id='gettypecat'>
											<option value='{$typecat1}'>{$typecat1}</option>
											<option value='{$typecat2}'>{$typecat2}</option>
											<option value='{$typecat3}'>{$typecat3}</option>
											<option value='{$typecat4}'>{$typecat4}</option>
										</select>
										<input type='hidden' name='gettypeid' value='{$readid[$i]}'>

										</font></p>
										<p style='margin-top: 2%;'>Стоимость: <font class='modal-price'> <input type='number' name='gettypecost' id='setoldcost_{$readid[$i]}' value='{$readcost[$i]}' class='inp-number'> </font></p>
									</font>
									<p class='success'>	
										<input class='btn' name='seteditinfo' type='submit' onclick='return editOldCard({$readid[$i]});' value='Сохранить' />
									</p>
								</article>
							</form>
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
		
				echo "<div id='openProfile?Profile={$readid[$i]}' class='modal'>
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
		
			$readid = array();
			$readuserid = array();
			$readtype = array();
			$readcategories = array();
			$readname = array();
			$readdescription = array();
			$readimage_url = array();
			$readcost = array();
		
			while ($readrow = mysqli_fetch_array($sqlrequestadvert)) {
				array_push($readid, $readrow["id"]);
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
			
			echo "<section class='two-elems'>";
		
			for ($i = 0; $i < count($readtype); $i++) {
				$newid = $readuserid[$i] - 1;
		
				if ($usrate[$newid] != 0) {
					$setrate = $usrate[$newid];
				}
				else {
					$setrate = '-';
				}
		
				if (($i + 1) % 2 == 0) {
					$type = "";
					if($readtype[$i] == "З")
						$type = "Помочь";
					else
						$type = "Написать";
					echo "<article class='ad_card'>
						<div class='div_ad_card'>
							<div class='profile-star'>
								<img class='profile_img_card' src='{$readimage_url[$i]}'>
								<div class='div-card-img'><img class='img-star' src='imgs/icons-star.svg'><p class='star'>{$setrate}</p></div>
							</div>
							<font class='text_card'>
								<div class='card-title'>
									<p class='text'>{$readname[$i]}</p>
									<div class='card-imgs'>
										<form method='post'>
											<a tag='false' onclick='changeImg(this)'><img class='img_like' src='imgs/icon-like.png'></a>
											<a href='#openEdit?Card={$readid[$i]}'><img class='img_edit' src='imgs/icon-edit.svg'></a>
											<button name='deleteinfo' value='{$readid[$i]}' type='submit' onclick='return  confirm(`Вы уверены удалить карточку №{$readid[$i]} \"$readname[$i]\"?`)'><img class='img_delete' src='imgs/icon-delete.svg'></button>
										</form>
									</div>
								</div>
							<p class='p-card-desc'>{$readdescription[$i]}</p>
							<div class='price-type'><p class='p-card'>Тип Какой-то тип</p> <p class='p-card-type'>Цена руб.</p></div>
							
							</font>
							</div>
							<div class='date-and-type'>
								<p class='text_date'>вчера</p>
								<a class='a-card' href='#openModal?Card={$readid[$i]}'><p class='type'>{$type}</p></a>
							</div>
					</article>
					</section>
					<section class='two-elems'>";
				} else {
					$type = "";
					if($readtype[$i] == "З")
						$type = "Помочь";
					else
						$type = "Написать";
					echo "<article class='ad_card'>
						<div class='div_ad_card'>
							<div class='profile-star'>
								<img class='profile_img_card' src='{$readimage_url[$i]}'>
								<div class='div-card-img'><img class='img-star' src='imgs/icons-star.svg'><p class='star'>{$setrate}</p></div>
							</div>
							<font class='text_card'>
								<div class='card-title'>
									<p class='text'>{$readname[$i]}</p>
									<div class='card-imgs'>
										<form method='post'>
											<a tag='false' onclick='changeImg(this)'><img class='img_like' src='imgs/icon-like.png'></a>
											<a href='#openEdit?Card={$readid[$i]}'><img class='img_edit' src='imgs/icon-edit.svg'></a>
											<button name='deleteinfo' value='{$readid[$i]}' type='submit' onclick='return  confirm(`Вы уверены удалить карточку №{$readid[$i]} \"$readname[$i]\"?`)'><img class='img_delete' src='imgs/icon-delete.svg'></button>
										</form>
									</div>
								</div>
							<p class='p-card-desc'>{$readdescription[$i]}</p>
							<div class='price-type'><p class='p-card'>Тип Какой-то тип</p> <p class='p-card-type'>Цена руб.</p></div>
							
							</font>
							</div>
							<div class='date-and-type'>
								<p class='text_date'>вчера</p>
								<a class='a-card' href='#openModal?Card={$readid[$i]}'><p class='type'>{$type}</p></a>
							</div>
					</article>";
				}
			}
		
			if (count($readtype) % 2 != 0) {
				echo "</section>";
			}
			
			for ($i = 0; $i < count($readtype); $i++) {
				$getuserid = mysqli_query($connect, "SELECT name FROM `users` WHERE id={$readuserid[$i]}");
				$getuserrow = mysqli_fetch_array($getuserid);
				echo "<div id='openModal?Card={$readid[$i]}' class='modal'>
				<div class='modal-dialog2'>
					<div class='modal-content'>
						<div class='modal-header'>
							<h3 class='modal-title'>{$readcategories[$i]}</h3>
							<a href='#close' onclick='changeText2(this)' title='' class='close'>×</a>
						</div>
						<div class='modal-body'>  
							<article class='popup-card'>  
								<a class='a-card' href='#openProfile?Profile={$readid[$i]}' ><img class='img_card' src='{$readimage_url[$i]}'></a>
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
									
									<button class='btn' data-show='true'>Выбрать</button><img class='img-success' style='display: none;' src='imgs/icons-success.png'>

								</p>
							</article>
						</div>
					</div>
				</div>
				</div>";
				
				$getuserid = mysqli_query($connect, "SELECT name FROM `users` WHERE id={$readuserid[$i]}");
				$getuserrow = mysqli_fetch_array($getuserid);

				if ($readtype[$i] == 'З') {
					$typeoption1 = "Прошу помощи";
					$typeoption2 = "Помочь";
				}
				else {
					$typeoption1 = "Помочь";
					$typeoption2 = "Прошу помощи";
				}

				$catlist = array("Автомобильная помощь", "Компьютерная помощь", "Швейные услуги", "Услуги сантехника");
				$typecat1 = $readcategories[$i];
				$outlist = array();
				for ($j = 0; $j < count($catlist); $j++) {
					if($catlist[$j] != $readcategories[$i]) {
						array_push($outlist, $catlist[$j]);
					}
				}
				$typecat2 = $outlist[0];
				$typecat3 = $outlist[1];
				$typecat4 = $outlist[2];

				echo "<div id='openEdit?Card={$readid[$i]}' class='modal'>
				<div class='modal-dialog2'>
					<div class='modal-content'>
						<div class='modal-header'>
							<h3 class='modal-title'>Редактирование</h3>
							<a href='?gettype=%D0%92%D1%81%D0%B5&amp;search=&amp;submit=#close' onclick='changeText2(this)' title='' class='close'>×</a>
						</div>
						<div class='modal-body'>  
							<form id='newFormAdv' method='post' enctype='multipart/form-data'>
								<article class='popup-card'>  
									<div class='img_card' style='background-size: cover; background-image: url({$readimage_url[$i]});'><input class='inp_card' type='file' name='gettypeimg'>
									<p class='uploadphoto' id='uploadphoto'>Новое фото не загружено</p></div>

									<font class='text_card'> 
										Вид объявления: 
										<select name='gettypeopt' id='gettypeopt' >
											<option value='{$typeoption1}'>{$typeoption1}</option>
											<option value='{$typeoption2}'>{$typeoption2}</option>
										</select>
										<p>Название: <font class='modal-category'> <input type='text' name='gettypename' value='{$readname[$i]}' class='inp-text' required/> </font></p>
										Описание:<p style='margin-top: 2%;'> <font class='modal-price'> <textarea type='text' name='gettypedesc' class='inp-text' required> {$readdescription[$i]}</textarea> </font></p>
									</font>
								</article>
								<article class='body_card'> 
									<font class='text_card'> <br>
										<p>Категория: <font class='modal-category'> 
										<select name='gettypecat' id='gettypecat'>
											<option value='{$typecat1}'>{$typecat1}</option>
											<option value='{$typecat2}'>{$typecat2}</option>
											<option value='{$typecat3}'>{$typecat3}</option>
											<option value='{$typecat4}'>{$typecat4}</option>
										</select>
										<input type='hidden' name='gettypeid' value='{$readid[$i]}'>

										</font></p>
										<p style='margin-top: 2%;'>Стоимость: <font class='modal-price'> <input type='number' name='gettypecost' id='setoldcost_{$readid[$i]}' value='{$readcost[$i]}' class='inp-number'> </font></p>
									</font>
									<p class='success'>	
									<input class='btn' name='seteditinfo' type='submit' onclick='return editOldCard({$readid[$i]});' value='Сохранить' />
									</p>
								</article>
							</form>
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
		
				echo "<div id='openProfile?Profile={$readid[$i]}' class='modal'>
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
	}
	else {       // при загрузке страницы

		$sqlrequestadvert = mysqli_query($connect, "SELECT * FROM `adverts`");

		$readid = array();
		$readuserid = array();
		$readtype = array();
		$readcategories = array();
		$readname = array();
		$readdescription = array();
		$readimage_url = array();
		$readcost = array();
	
		while ($readrow = mysqli_fetch_array($sqlrequestadvert)) {
			array_push($readid, $readrow["id"]);
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

		echo "<section class='two-elems'>";
	
		for ($i = 0; $i < count($readtype); $i++) {
			$newid = $readuserid[$i] - 1;
	
			if ($usrate[$newid] != 0) {
				$setrate = $usrate[$newid];
			}
			else {
				$setrate = '-';
			}
			if (($i + 1) % 2 == 0) {
				$type = "";
				if($readtype[$i] == "З")
					$type = "Помочь";
				else
					$type = "Написать";
				echo "<article class='ad_card'>
					<div class='div_ad_card'>
						<div class='profile-star'>
							<img class='profile_img_card' src='{$readimage_url[$i]}'>
							<div class='div-card-img'><img class='img-star' src='imgs/icons-star.svg'><p class='star'>{$setrate}</p></div>
							
						</div>
						<font class='text_card'>
							<div class='card-title'>
								<p class='text'>{$readname[$i]}</p>
								<div class='card-imgs'>
									<form method='post'>
										<a tag='false' onclick='changeImg(this)'><img class='img_like' src='imgs/icon-like.png'></a>
										<a href='#openEdit?Card={$readid[$i]}'><img class='img_edit' src='imgs/icon-edit.svg'></a>
										<button name='deleteinfo' value='{$readid[$i]}' type='submit' onclick='return  confirm(`Вы уверены удалить карточку №{$readid[$i]} \"$readname[$i]\"?`)'><img class='img_delete' src='imgs/icon-delete.svg'></button>
									</form>
								</div>
							</div>
						<p class='p-card-desc'>{$readdescription[$i]}</p>
						<div class='price-type'><p class='p-card'>Тип Какой-то тип</p> <p class='p-card-type'>Цена руб.</p></div>

							
						
						</font>
						</div>
					<div class='date-and-type'>
							<p class='text_date'>вчера</p>
							<a class='a-card' href='#openModal?Card={$readid[$i]}'><p class='type'>{$type}</p></a>
					</div>
				</article>
				</section>
				<section class='two-elems'>";
			} else {
				$type = "";
				if($readtype[$i] == "З")
					$type = "Помочь";
				else
					$type = "Написать";
				echo "<article class='ad_card'>
						<div class='div_ad_card'>
						<div class='profile-star'>
							<img class='profile_img_card' src='{$readimage_url[$i]}'>
							<div class='div-card-img'><img class='img-star' src='imgs/icons-star.svg'><p class='star'>{$setrate}</p></div>
						</div>
						<font class='text_card'>
							<div class='card-title'>
								<p class='text'>{$readname[$i]}</p>
								<div class='card-imgs'>
									<form method='post'>
										<a tag='false' onclick='changeImg(this)'><img class='img_like' src='imgs/icon-like.png'></a>
										<a href='#openEdit?Card={$readid[$i]}'><img class='img_edit' src='imgs/icon-edit.svg'></a>
										<button name='deleteinfo' value='{$readid[$i]}' type='submit' onclick='return  confirm(`Вы уверены удалить карточку №{$readid[$i]} \"$readname[$i]\"?`)'><img class='img_delete' src='imgs/icon-delete.svg'></button>
									</form>
								</div>
							</div>
						<p class='p-card-desc'>{$readdescription[$i]}</p>
						<div class='price-type'><p class='p-card'>Тип Какой-то тип</p> <p class='p-card-type'>Цена руб.</p></div>
						

						</font>
						</div>
					<div class='date-and-type'>
							<p class='text_date'>вчера</p>
							<a class='a-card' href='#openModal?Card={$readid[$i]}'><p class='type'>{$type}</p></a>
						</div>
					
				</article>";
			}
		}
	
		if (count($readtype) % 2 != 0) {
			echo "</section>";
		}
		
		for ($i = 0; $i < count($readtype); $i++) {
			$getuserid = mysqli_query($connect, "SELECT name FROM `users` WHERE id={$readuserid[$i]}");
			$getuserrow = mysqli_fetch_array($getuserid);
			echo "<div id='openModal?Card={$readid[$i]}' class='modal'>
			<div class='modal-dialog2'>
				<div class='modal-content'>
					<div class='modal-header'>
						<h3 class='modal-title'>{$readcategories[$i]}</h3>
						<a href='#close' onclick='changeText2(this)' title='' class='close'>×</a>
					</div>
					<div class='modal-body'>  
						<article class='popup-card'>  
							<a class='a-card' href='#openProfile?Profile={$readid[$i]}' ><img class='img_card' src='{$readimage_url[$i]}'></a>
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
	
								<button class='btn' data-show='true'>Выбрать</button><img class='img-success' style='display: none;' src='imgs/icons-success.png'>
							</p>
						</article>
					</div>
				</div>
			</div>
			</div>";

			if ($readtype[$i] == 'З') {
				$typeoption1 = "Прошу помощи";
				$typeoption2 = "Помочь";
			}
			else {
				$typeoption1 = "Помочь";
				$typeoption2 = "Прошу помощи";
			}

			$catlist = array("Автомобильная помощь", "Компьютерная помощь", "Швейные услуги", "Услуги сантехника");
			$typecat1 = $readcategories[$i];
			$outlist = array();
			for ($j = 0; $j < count($catlist); $j++) {
				if($catlist[$j] != $readcategories[$i]) {
					array_push($outlist, $catlist[$j]);
				}
			}
			$typecat2 = $outlist[0];
			$typecat3 = $outlist[1];
			$typecat4 = $outlist[2];

			echo "<div id='openEdit?Card={$readid[$i]}' class='modal'>
			<div class='modal-dialog2'>
				<div class='modal-content'>
					<div class='modal-header'>
						<h3 class='modal-title'>Редактирование</h3>
						<a href='?gettype=%D0%92%D1%81%D0%B5&amp;search=&amp;submit=#close' onclick='changeText2(this)' title='' class='close'>×</a>
					</div>
					<div class='modal-body'>  
						<form method='post' enctype='multipart/form-data'>
							<article class='popup-card'>  
								<div class='img_card' style='background-size: cover; background-image: url({$readimage_url[$i]});'><input class='inp_card' type='file' name='gettypeimg'>
								<p class='uploadphoto' id='uploadphoto'>Новое фото не загружено</p></div>

								<font class='text_card'> 
									Вид объявления: 
									<select name='gettypeopt' id='gettypeopt' >
										<option value='{$typeoption1}'>{$typeoption1}</option>
										<option value='{$typeoption2}'>{$typeoption2}</option>
									</select>
									<p>Название: <font class='modal-category'> <input type='text' name='gettypename' value='{$readname[$i]}' class='inp-text' required/> </font></p>
									Описание:<p style='margin-top: 2%;'> <font class='modal-price'> <textarea type='text' name='gettypedesc' class='inp-text' required> {$readdescription[$i]}</textarea> </font></p>
								</font>
							</article>
							<article class='body_card'> 
								<font class='text_card'> <br>
									<p>Категория: <font class='modal-category'> 
									<select name='gettypecat' id='gettypecat'>
										<option value='{$typecat1}'>{$typecat1}</option>
										<option value='{$typecat2}'>{$typecat2}</option>
										<option value='{$typecat3}'>{$typecat3}</option>
										<option value='{$typecat4}'>{$typecat4}</option>
									</select>
									<input type='hidden' name='gettypeid' value='{$readid[$i]}'>

									</font></p>
									<p style='margin-top: 2%;'>Стоимость: <font class='modal-price'> <input type='number' name='gettypecost' id='setoldcost_{$readid[$i]}' value='{$readcost[$i]}' class='inp-number'> </font></p>
								</font>
								<p class='success'>	
								<input class='btn' name='seteditinfo' type='submit' onclick='return editOldCard({$readid[$i]});' value='Сохранить' />
								</p>
							</article>
						</form>
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
	
			echo "<div id='openProfile?Profile={$readid[$i]}' class='modal'>
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

	if(isset($_POST['setaddedinfo'])) {       // отправка новой карточки
		$addopt = $_POST['settypeopt'];
		$addcat = $_POST['settypecat'];
		$addname = $_POST['settypename'];
		$adddesc = $_POST['settypedesc'];
		$addcost = $_POST['settypecost'];

		if ($addopt == 'Прошу помощи') {
			$addopt = "Заявки";
		}
		else {
			$addopt = "Объявления";
		}

		if (strlen($addcost) == 0) {
			$addcost = 0;
		}
		
		$sqlgetidrequest = mysqli_query($connect, "SELECT `id` FROM `adverts`");
		$readfreeid = array();
	
		while ($readrow = mysqli_fetch_array($sqlgetidrequest)) {
			array_push($readfreeid, $readrow["id"]);
		}
	
		$addedid = $readfreeid[count($readfreeid) - 1];
		$addedid++;

		/*echo "
		Тип: {$addopt} <br>
		Категория: {$addcat} <br>
		Название: {$addname} <br>
		Описание: {$adddesc} <br>
		Цена: {$addcost} <br>
		";*/

		if (is_uploaded_file($_FILES['settypeimg']['tmp_name'])) {

			//echo "Фото добавлено! <br>";

			$addimgname = $_FILES['settypeimg']['name'];
			$addimgpath = $_FILES['settypeimg']['tmp_name'];

			$addimg = '/pictures/'.$addimgname;
			move_uploaded_file($addimgpath, $_SERVER['DOCUMENT_ROOT'].$addimg);

			//echo "Имя фото:  {$addimgname}     Название блин полное:  {$addimg} <br>";

			$sqladdedrequest = mysqli_query($connect, "INSERT INTO `adverts`(`id`, `userid`, `type`, `categories`, `name`, `description`, `image_url`, `cost`) VALUES ('{$addedid}','1','{$addopt}','{$addcat}','{$addname}','{$adddesc}','{$addimg}','{$addcost}')");

		}
		else {

			//echo "Фото не выбрано. <br>";
			$sqladdedrequest = mysqli_query($connect, "INSERT INTO `adverts`(`id`, `userid`, `type`, `categories`, `name`, `description`, `image_url`, `cost`) VALUES ('{$addedid}','1','{$addopt}','{$addcat}','{$addname}','{$adddesc}','pictures/AddEdit.jpg','{$addcost}')");
		
		}

		//echo "<script>alert('Карточка №".$addedid." \"".$addname."\" успешно создана!');</script>";
		echo "<script>alert('Карточка \"".$addname."\" успешно создана!');</script>";
		refresh();
	
	}
	else if (isset($_POST['deleteinfo'])) {       // удаление карточки

		$deleteid = $_POST['deleteinfo'];
		$deletename = $readname[$deleteid - 1];
		$sqldeleterequest = mysqli_query($connect, "DELETE FROM `adverts` WHERE `id`='{$deleteid}'");

		//echo "<script>alert('Карточка №".$deleteid." \"".$deletename."\" успешно удалена!');</script>";
		echo "<script>alert('Карточка \"".$deletename."\" успешно удалена!');</script>";
		refresh();

	}
	else if(isset($_POST['seteditinfo'])) {       // отправка редактированных данных
		$editid = $_POST['gettypeid'];
		$editopt = $_POST['gettypeopt'];
		$editcat = $_POST['gettypecat'];
		$editname = $_POST['gettypename'];
		$editdesc = $_POST['gettypedesc'];
		$editcost = $_POST['gettypecost'];

		if ($editopt == 'Прошу помощи') {
			$editopt = "Заявки";
		}
		else {
			$editopt = "Объявления";
		}

		if (strlen($editcost) == 0) {
			$editcost = 0;
		}

		/*echo "
		ID: {$editid} <br>
		Тип: {$editopt} <br>
		Категория: {$editcat} <br>
		Название: {$editname} <br>
		Описание: {$editdesc} <br>
		Цена: {$editcost} <br>
		";*/

		if (is_uploaded_file($_FILES['gettypeimg']['tmp_name'])) {

			$editimgname = $_FILES['gettypeimg']['name'];
			$editimgpath = $_FILES['gettypeimg']['tmp_name'];
	
			$editimg = '/pictures/'.$editimgname;
			move_uploaded_file($editimgpath, $_SERVER['DOCUMENT_ROOT'].$editimg);

			//echo "Имя фото:  {$editimgname}     Название блин полное:  {$editimg}";

			$sqleditrequest = mysqli_query($connect, "UPDATE `adverts` SET `type`='{$editopt}',`categories`='{$editcat}',`name`='{$editname}',`description`='{$editdesc}',`image_url`='{$editimg}',`cost`='{$editcost}' WHERE `id`='{$editid}'");

		}
		else {
			$sqleditrequest = mysqli_query($connect, "UPDATE `adverts` SET `type`='{$editopt}',`categories`='{$editcat}',`name`='{$editname}',`description`='{$editdesc}',`cost`='{$editcost}' WHERE `id`='{$editid}'");
		}

		//echo "<script>alert('Карточка №".$editid." \"".$editname."\" успешно отредактирована!');</script>";
		echo "<script>alert('Карточка \"".$editname."\" успешно отредактирована!');</script>";
		refresh();
		
	}

	function refresh() {
		//header('Location: '.'http://mysite.local/');
    	//die();
		echo "<script>window.location.href='index.php'</script>";
	}

	?>



	<div id='openAdd' class='modal'>
		<div class='modal-dialog2'>
			<div class='modal-content'>
				<div class='modal-header'>
					<h3 class='modal-title'>Добавить карточку</h3>
					<a href='?gettype=%D0%92%D1%81%D0%B5&amp;search=&amp;submit=#close' onclick='changeText2(this)' title='' class='close'>×</a>
				</div>
				<div class='modal-body'>  
					<form method='post' enctype='multipart/form-data'>
						<article class='popup-card'>  
							<div class='img_card'><input onchange='changeNewFile(this);' class='inp_card' type='file' name='settypeimg'></div>
							<p class='uploadnewphoto'>Фото не загружено</p></div>
							<font class='text_card'> 
								Вид объявления: 
								<select name='settypeopt' id='settypeopt' >
									<option value='Помочь'>Помочь</option>
									<option value='Прошу помощи'>Прошу помощи</option>
								</select>
								<p>Название: <font class='modal-category'> <input type='text' name='settypename' class='inp-text' required/> </font></p>
								Описание:<p style='margin-top: 2%;'> <font class='modal-price'>  <textarea type='text' name='settypedesc' class='inp-text' required> </textarea> </font></p>
							</font>
						</article>
						<article class='body_card'> 
							<font class='text_card'> <br>
								<p>Категория: <font class='modal-category'> 
								<select name='settypecat' id='settypecat' >
									<option value='Автомобильная помощь'>Автомобильная помощь</option>
									<option value='Компьютерная помощь'>Компьютерная помощь</option>
									<option value='Швейные услуги'>Швейные услуги</option>
									<option value='Услуги сантехника'>Ремонт и отделка</option>
								</select>
		 					</font></p>
								<p style='margin-top: 2%;'>Стоимость: <font class='modal-price'> <input type='number' name='settypecost' id='addnewcost' class='inp-number'> </font></p>
							</font>
							<p class='success'>	
							<input class='btn' name='setaddedinfo' type='submit' value='Сохранить' onclick='return addNewCard();' />
							</p>
						</article>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

	</div>

	<footer>
		Lorem ipsum dolor sit amet © 2022
	</footer>
</div>	
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
    }}
    function changeColorText(ev) {
    	switch(ev.getAttribute('type')){
    		case 'comp':
    			console.log(ev);
    			if(ev.getAttribute('tag') == 'false'){
		    		ev.style.color = '#6C63FF';
		    		ev.setAttribute('tag', "true"); 
		    		ev.innerHTML = 'Компьютерная помощь <input class="category-inp" checked type="checkbox" name="category[]" value="Компьютерная помощь">';
		    	}
		    	else{
		    		ev.style.color = '#0A0A0A';
		    		ev.setAttribute('tag', "false"); 
		    		ev.innerHTML = 'Компьютерная помощь <input class="category-inp" type="checkbox" name="category[]" value="Компьютерная помощь">';    	
		    	}
		    	break;
		    case 'auto':
		    	console.log(ev);
		    	if(ev.getAttribute('tag') == 'false'){
		    		ev.style.color = '#6C63FF';
		    		ev.setAttribute('tag', "true"); 
		    		ev.innerHTML = 'Автомобильная помощь <input class="category-inp" checked type="checkbox" name="category[]" value="Автомобильная помощь">';
		    	}
		    	else{
		    		ev.style.color = '#0A0A0A';
		    		ev.setAttribute('tag', "false"); 
		    		ev.innerHTML = 'Автомобильная помощь <input class="category-inp" type="checkbox" name="category[]" value="Автомобильная помощь">';    	
		    	}
		    	break;
		    case 'sewing':
		    	if(ev.getAttribute('tag') == 'false'){
		    		ev.style.color = '#6C63FF';
		    		ev.setAttribute('tag', "true"); 
		    		ev.innerHTML = 'Швейные услуги <input class="category-inp" checked type="checkbox" name="category[]" value="Швейные услуги">';
		    	}
		    	else{
		    		ev.style.color = '#0A0A0A';
		    		ev.setAttribute('tag', "false"); 
		    		ev.innerHTML = 'Швейные услуги <input class="category-inp" type="checkbox" name="category[]" value="Швейные услуги">';    	
		    	}
		    	break;
		    case 'plumber':
		    	if(ev.getAttribute('tag') == 'false'){
		    		ev.style.color = '#6C63FF';
		    		ev.setAttribute('tag', "true"); 
		    		ev.innerHTML = 'Услуги сантехника<input class="category-inp" checked type="checkbox" name="category[]" value="Услуги сантехника">';
		    	}
		    	else{
		    		ev.style.color = '#0A0A0A';
		    		ev.setAttribute('tag', "false"); 
		    		ev.innerHTML = 'Услуги сантехника <input class="category-inp" type="checkbox" name="category[]" value="Услуги сантехника">';    	
		    	}
		    	break;
		    default:
			    console.log("ага");
			  
    	}

    	



    	//ev.is(":checked") = true;
    	//ev.target.style.color = '#6C63FF';
    	//alert("Дартути");
    }
    function changeImg(ev){
    	if(ev.getAttribute('tag') == 'false')
    	{
    		ev.innerHTML = "<img class='img_like' src='imgs/icon-like-active.png'>";
    		ev.setAttribute('tag', 'true');
    	}
    	else
    	{
    		ev.innerHTML = "<img class='img_like' src='imgs/icon-like.png'>";
    		ev.setAttribute('tag', 'false');
    	}
    }

function changeNewFile(obj) {
	var filename = obj.files[0].name;
	var filepath = obj.value;
	//document.getElementsByClassName("img_card")[0].style.backgroundImage = "url('imgs/icon-edit.png')";
	document.getElementsByClassName("uploadnewphoto")[0].innerHTML = "Новое фото " + filename + " успешно загружено. ";
}
function changeFile(obj, n) {
	var filename = obj.files[0].name;
	var filepath = obj.value;
    //document.getElementsByClassName("img_card")[0].style.backgroundImage = "url('imgs/icon-edit.png')";
	document.getElementsByClassName("uploadphoto")[n].innerHTML = "Новое фото " + filename + " успешно загружено. ";
}
function addNewCard() {
	var costinput = document.getElementById("addnewcost").value;
	if (costinput == "" || costinput.length == 0 || costinput == null) {
		return confirm(`Поле ввода цены пустое. Хотите ли вы сделать объявление бесплатным?`);
	}
}
function editOldCard(editid) {
	var noweditid = "setoldcost_" + editid;
	var costinputold = document.getElementById(noweditid).value;
	if (costinputold == "" || costinputold.length == 0 || costinputold == null) {
		return confirm(`Поле ввода цены пустое. Хотите ли вы сделать объявление бесплатным?`);
	}
}
</script>