<?php
function headerUser($fullname) {
	?>
	<div class="rounded-full bg-blue-500 h-12 w-12 flex items-center justify-center text-white mr-2"><?php echo substr($fullname, 0, 1); ?></div>
    <div id="nameUser" class="text-2xl font-bold"><?php echo $fullname; ?></div>
	<?php
}
?>