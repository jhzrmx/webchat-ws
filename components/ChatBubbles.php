<?php
function senderBubble($sentMessage, $sentAgo) {
	?>
	<div class="flex mt-3 justify-end">
		<div title="Sent <?php echo $sentAgo; ?>" class="bg-blue-500 text-white px-4 py-2 rounded-2xl max-w-xs whitespace-pre-wrap"><?php echo $sentMessage; ?></div>
	</div>
<?php
}

function receiverBubble($sentMessage, $sentAgo) {
	?>
	<div class="flex mt-3 justify-start">
		<div title="Sent <?php echo $sentAgo; ?>" class="bg-gray-100 px-4 py-2 rounded-2xl max-w-xs whitespace-pre-wrap"><?php echo $sentMessage; ?></div>
	</div>
<?php
}
?>