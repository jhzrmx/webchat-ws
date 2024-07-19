<?php 
function sideBar($display) {
	if ($display === "desktop") {
		?>
		<div class="hidden h-dvh pl-4 py-4 md:block ">
	        <div class="w-full h-full overflow-hidden bg-gray-300 rounded-lg p-3">
	            <div class="flex items-center mb-3 mt-1">
	            	<h2 class="w-full ml-2 text-2xl text-left font-bold">Chats</h2>
	            	<button id="logout" title="Logout" class="w-14 h-11 rounded-full transform rotate-180 hover:bg-gray-50 px-1">
	            		<svg class="w-9 h-9 justify-end" fill="none" viewBox="0 0 24 24" width="24">
	            			<path d="M17 16L21 12M21 12L17 8M21 12L7 12M13 16V17C13 18.6569 11.6569 20 10 20H6C4.34315 20 3 18.6569 3 17V7C3 5.34315 4.34315 4 6 4H10C11.6569 4 13 5.34315 13 7V8" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
	            		</svg>
	            	</button>
	            	<button id="profile" title="Profile" class="w-14 h-11 rounded-full hover:bg-gray-50 px-1">
	            		<img class="w-9 h-9 justify-end" src="../img/icons/profile-circle-svgrepo-com.svg">
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
	            	<h2 class="w-full ml-2 text-2xl text-left font-bold">Chats</h2>
	            	<button id="logoutMobile" title="Logout" class="w-14 h-11 rounded-full transform rotate-180 hover:bg-gray-50 px-1">
	            		<svg class="w-8 h-8 justify-end" fill="none" viewBox="0 0 24 24" width="24">
	            			<path d="M17 16L21 12M21 12L17 8M21 12L7 12M13 16V17C13 18.6569 11.6569 20 10 20H6C4.34315 20 3 18.6569 3 17V7C3 5.34315 4.34315 4 6 4H10C11.6569 4 13 5.34315 13 7V8" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
	            		</svg>
	            	</button>
	            	<button id="profileMobile" title="Profile" class="w-14 h-11 rounded-full hover:bg-gray-50 px-1">
	            		<img class="w-9 h-9 justify-end" src="../img/icons/profile-circle-svgrepo-com.svg">
	            	</button>
	            	<button id="closeSideBarMobile" title="Close" class="w-12 h-11 rounded-full hover:bg-gray-50 px-1">
	            		<svg id="mdi-close" fill="#292d32" viewBox="0 0 24 24">
	            			<path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
	            		</svg>
	            	</button>
	            </div>
	            <div class="w-full flex mb-2">
	            	<button id="getUsersMobile" class="w-full font-semibold py-2 rounded-l-lg bg-gray-100 hover:bg-gray-50">Users</button>
	            	<button id="getFriendsMobile" class="w-full font-semibold py-2 rounded-r-lg bg-gray-200 hover:bg-gray-50">Friends</button>
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