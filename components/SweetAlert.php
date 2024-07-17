<?php
function swal($title, $message, $type) {
	?>
	<script type="text/javascript">swal("<?php echo $title; ?>", "<?php echo $message; ?>", "<?php echo $type; ?>");</script>
<?php
}

function swalThen($title, $message, $type, $event) {
	?>
	<script type="text/javascript">swal("<?php echo $title; ?>", "<?php echo $message; ?>", "<?php echo $type; ?>").then( <?php echo $event; ?> );</script>
<?php
}
?>