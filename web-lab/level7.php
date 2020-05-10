<?php
	$sql = "SELECT `сочинения`.`Название`, `ансамбли`.`Название` , `исполненния`.`Дата исполнения` FROM `исполненния`  JOIN `ансамбли` ON `ансамбли`.`№ ансамбля` = `исполненния`.`№ ансамбля` JOIN `сочинения` ON `сочинения`.`№ сочинения` = `исполненния`.`№ сочинения` WHERE `исполненния`.`Дата исполнения` >= '1980-01-01 00:00:00' AND `Дата исполнения` < '1986-01-01 00:00:00'";
	if (isset($_GET['text'])){
		$sql = $_GET['text'];
	}
	require_once('db.php');	
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Level 7</title>
    <link href="./css/style.css" rel="stylesheet">
</head>
<body>
	<h1>Пример 7</h1>
    <div class="info">
        <b>Задание</b>:  <br>
        Какие произведения и какими ансамблями исполнялись с 1980 по 1985 года.
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