<?php
	$sql = "SELECT `музыканты`.`Имя` AS `Композиторы`, `музыканты`.`Дата рождения` FROM `музыканты` JOIN `сочинения` ON `музыканты`.`№ музыканта` = `сочинения`.`№ музыканта-автора`  WHERE `музыканты`.`Дата рождения` >= '1970-01-01 00:00:00' AND `музыканты`.`Дата рождения` < '1980-01-01 00:00:00'";
	if (isset($_GET['text'])){
		$sql = $_GET['text'];
	}
	require_once('db.php');	
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Level 8</title>
    <link href="./css/style.css" rel="stylesheet">
</head>
<body>
	<h1>Пример 8</h1>
    <div class="info">
        <b>Задание</b>:  <br>
        Перечислить фамилии всех композиторов, родившихся в 70 – х годах
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