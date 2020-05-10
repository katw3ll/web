<?php
	$sql = "SELECT `музыканты`.`Имя` AS `Дирижеры из Аландии` FROM `музыканты` JOIN `исполненния` ON `музыканты`.`№ музыканта` = `исполненния`.`№ музыканта-дирижера` WHERE `музыканты`.`Страна` = 'Аландия' GROUP BY `Дирижеры из Аландии`";
	if (isset($_GET['text'])){
		$sql = $_GET['text'];
	}
	require_once('db.php');	
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Level 5</title>
    <link href="./css/style.css" rel="stylesheet">
</head>
<body>
	<h1>Пример 5</h1>
    <div class="info">
        <b>Задание</b>:  <br>
        Указать имена дирижеров из Аландии.
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
            <input type="text" name="text" value="<?=$sql?>"> <button>Отправить</button>
        </form>
    </div>

    <div class="table">

	<table><tbody>
		<?php show_table($sql); ?>
	</tbody></table>
	
    </div>

</body></html>