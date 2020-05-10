<?php
session_start();

function get_the_utms() { 
	?>
<script> 
	<?php 
if (isset($_GET['utm_source'])) {
    $_SESSION['utm'] = json_encode($_GET);	
	$sess = $_SESSION['utm'];
	?>	
	console.log('Сессия обновлена');
	console.log('$_SESSION[\'utm\'] = <?php print_r($sess);?>');  
	<?php
} else { ?>
	console.log("UTM Get пустой. Сессия не обновлена.");
	<?php
	};
	?>
</script>
<?php
}

get_the_utms();
?>