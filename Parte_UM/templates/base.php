<?php
?>
<!DOCTYPE html>
<html>
<!-- me deu um baita trabalho usar buffers e no fim eu nem usei kkk-->
	<?php
		include __DIR__.'/components/head.php';
	?>
	
	<body class="d-flex flex-column min-vh-100">
		<?php
			include __DIR__.'/components/header.php';
		?>

				<main class="flex-fill container">

					<?= $pageContent ?? ' aaa ' ?>
				
				</main>


		<?php
			include __DIR__.'/components/footer.php';
		?>

	</body>

</html>