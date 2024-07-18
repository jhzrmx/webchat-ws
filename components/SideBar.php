<?php 
function sideBar($display) {
	if ($display === "desktop") {
		?>
		<div class="hidden h-dvh pl-4 py-4 md:block ">
	        <div class="w-full h-full overflow-hidden bg-gray-300 rounded-lg p-3">
	            <div class="flex items-center mb-3 mt-1">
	            	<h2 class="w-full ml-2 text-2xl text-left font-bold">My Chats</h2>
	            	<button class="w-12 h-11 rounded-full hover:bg-gray-50 px-1">
	            		<img class="w-10 h-10 justify-end" src="../img/icons/profile-circle-svgrepo-com.svg">
	            	</button>
	            </div>
	            <div class="w-full flex mb-2">
	            	<button id="getUsers" class="w-full font-semibold py-2 rounded-l-lg bg-gray-100 hover:bg-gray-50">Users</button>
	            	<button id="getFriends" class="w-full font-semibold py-2 rounded-r-lg bg-gray-200 hover:bg-gray-50">Friends</button>
	            </div>
	            <div class="w-full h-full flex overflow-y-auto">
		            <ul id="usersFriendsList" class="w-full">
		                <!-- Example list items -->
		            </ul>
	            </div>
	        </div>
	    </div>
		<?php
	} else {
		?>
		<div id="sideBarMobile" class="block md:hidden h-dvh w-full p-4 absolute top-0 left-0">
	        <div class="w-full h-full overflow-hidden bg-gray-300 rounded-lg p-3">
	            <div class="flex items-center mb-3 mt-1">
	            	<h2 class="w-full ml-2 text-2xl text-left font-bold">My Chats</h2>
	            	<button class="w-12 h-11 rounded-full hover:bg-gray-50 px-1">
	            		<img class="w-10 h-10 justify-end" src="../img/icons/profile-circle-svgrepo-com.svg">
	            	</button>
	            	<button id="closeSideBarMobile" class="w-12 h-11 rounded-full hover:bg-gray-50 px-1">
	            		<svg id="mdi-close" viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg>
	            	</button>
	            </div>
	            <div class="w-full flex mb-2">
	            	<button id="getUsersMobile" class="w-full font-semibold py-2 rounded-l-lg bg-gray-100 hover:bg-gray-50">Users</button>
	            	<button class="w-full font-semibold py-2 rounded-r-lg bg-gray-200 hover:bg-gray-50">Friends</button>
	            </div>
	            <div class="w-full h-full flex overflow-y-auto">
		            <ul id="usersFriendsListMobile" class="w-full">
		                <!-- Example list items -->
		            </ul>
	            </div>
	        </div>
	    </div>
		<?php
	}
}
?>