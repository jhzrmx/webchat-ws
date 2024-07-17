<?php
function cardMessageList($fullname, $userId, $lastMessage) {
	?>
	<li class="flex items-center mb-1">
		<button onclick="getMessages('<?php echo $userId ?>')" class="flex justify-start items-center w-full hover:bg-gray-50 p-2 rounded-lg overflow-hidden">
			<div class="rounded-full bg-blue-500 h-10 w-10 fixed flex items-center justify-center text-white mr-2"><?php echo substr($fullname, 0, 1); ?></div>
			<div class="flex flex-col justify-start ml-12 text-left">
				<p class="font-semibold">
					<?php echo $fullname; ?>
				</p>
				<p class="text-xs truncate">
					<?php echo $lastMessage; ?>
				</p>
			</div>
		</button>
	</li>
	<?php
}
?>