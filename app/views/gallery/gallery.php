<?php
   	if ( isset( $this->view_data['data'] ) ) {
		$data = $this->view_data['data'];
		$totalImages = $data['totalImages'];
		$imagePerPage = 5;
		$currentPage = $data['page'];
		$totalPages = ceil( $totalImages / $imagePerPage );
		$userData = $data['userData'];
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_header.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/gallery.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_footer.css"/>
	<script src="<?php echo PUBLIC_FOLDER?>/js/jquery-3.5.1.js"></script>
	<script src="<?php echo PUBLIC_FOLDER?>/js/jquery-3.5.1.min.js"></script>
	<title>Gallery</title>
</head>
<body>
	<?php require_once(VIEWS . "_header.php");?>
	<div class="row offset-lg-1 col-lg-10" id="gallery">
		<?php if ( count( $data['gallery'] ) === 0 ) { ?>
			<p> <?php echo "No Edited images !"; ?> </p>
		<?php } else {
			foreach ( $data['gallery'] as $image ) {
		?>
			<div class="card" id="image">
				<div class="card-body">
					<div class="card-title">
						By <?php print( $image['username'] ); ?>
					</div>
				</div>
				<img src="<?php print( $image['src'] ); ?>" class="card-img" alt="...">
				<div class="card-footer w-100">
					<?php
						// $currDate = date_create();
						// $createdat = date_create($image['createdat']);
						// $diff = $createdat->diff( $currDate, true );
						$createdat = new DateTime( $image['createdat'] );
						$hours = $createdat->format('H');
						$minutes = $createdat->format('i');
						echo $createdat->format('Y-m-d').' '.$hours.'h '.$minutes;
					?>
					<div class="w-100">
						<img id="icone-like" src="<?php echo PUBLIC_FOLDER; ?>/images/like-icone.png"/>
						<img id="icone-comment" src="<?php echo PUBLIC_FOLDER; ?>/images/comment-icone.png"/>
					</div>
				</div>
			</div>
		<?php }
		} ?>
	</div>
	<div id="pagination">
		<?php
			for ( $i = 0; $i <= $totalPages; $i++ ) {
				if ( $i == $currentPage ) { echo " "; }
				else if ( $i == $totalPages + 1 ) {
					echo '<a href="'.SERVER.'/gallery/page='.$i.'" class="suivant">'.$i.'</a>';
				} else {
					echo '<a href="'.SERVER.'/gallery/page='.$i.'">'.$i.'</a>';
				}
			}
		?>
	</div>
	<!-- <?php //require_once(VIEWS . "_footer.php");?> -->
</body>
</html>