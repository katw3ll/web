<?php
	$sql = "SELECT `музыканты`.`Имя` , COUNT(`сочинения`.`№ музыканта-автора`) FROM `сочинения` JOIN `музыканты` ON `сочинения`.`№ музыканта-автора` = `музыканты`.`№ музыканта` GROUP BY `сочинения`.`№ музыканта-автора`";
	if (isset($_GET['text'])){
		$sql = $_GET['text'];
	}
	require_once('db.php');	
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Level 9</title>
    <link href="./css/style.css" rel="stylesheet">
</head>
<body>
	<h1>Пример 9</h1>
    <div class="info">
        <b>Задание</b>:  <br>
        Сколько произведений написал каждый из композиторов
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