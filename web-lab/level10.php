<?php
	$country = '';
	if (isset($_GET['c'])){
		$country = $_GET['c'];
	}

	$sql = "SELECT COUNT(DISTINCT `музыканты`.`Имя`) AS `Количество`FROM `музыканты` JOIN `сочинения` ON `музыканты`.`№ музыканта` = `сочинения`.`№ музыканта-автора`  WHERE `музыканты`.`Страна` = '$country' ";
	require_once('db.php');	
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Level 10</title>
    <link href="./css/style.css" rel="stylesheet">
</head>
<body>
	<h1>Пример 10</h1>
    <div class="info">
        <b>Задание</b>:  <br>
        Запрос с параметром. Сколько композиторов родилось в конкретной стране (ввод с клавиатуры)
    </div>
	<div class="nav">
		<?php require_once('nav.php');	?>
	</div>

    <div class="query">
		<div style="width: 800px;">
		<b>Запрос</b>:<br>
		<?=$sql?>
		<br>
		<br>
		</div>

        <div>
            Введите запрос к базе данных:
        </div>
        <form method="GET">
			<b>Страна: </b><br>
			<input type="text" name="c" value="<?=$country?>" style="width: 100px;">
            <button>Отправить</button>
        </form>
    </div>

    <div class="table">

	<table><tbody>
		<?php show_table($sql); ?>
	</tbody></table>
	
    </div>

</body></html>