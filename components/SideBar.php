<?php 
function sideBar($display) {
	if ($display === "desktop") {
		?>
		<div class="hidden h-dvh pl-4 py-4 md:block ">
	        <div class="w-full h-full flex flex-col overflow-hidden bg-gray-300 rounded-lg p-3">
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
	            <div class="w-full flex flex-row p-1 mb-3 h-9 rounded-3xl bg-gray-50">
                    <svg id="mdi-account-search" class="h-7 w-7 mx-2 fill-gray-300" viewBox="0 0 24 24"><path d="M15.5,12C18,12 20,14 20,16.5C20,17.38 19.75,18.21 19.31,18.9L22.39,22L21,23.39L17.88,20.32C17.19,20.75 16.37,21 15.5,21C13,21 11,19 11,16.5C11,14 13,12 15.5,12M15.5,14A2.5,2.5 0 0,0 13,16.5A2.5,2.5 0 0,0 15.5,19A2.5,2.5 0 0,0 18,16.5A2.5,2.5 0 0,0 15.5,14M10,4A4,4 0 0,1 14,8C14,8.91 13.69,9.75 13.18,10.43C12.32,10.75 11.55,11.26 10.91,11.9L10,12A4,4 0 0,1 6,8A4,4 0 0,1 10,4M2,20V18C2,15.88 5.31,14.14 9.5,14C9.18,14.78 9,15.62 9,16.5C9,17.79 9.38,19 10,20H2Z" /></svg>
                        <input type="text" id="search" placeholder="Search" maxlength="100" class="w-full bg-gray-50 focus:outline-none mr-2">
                </div>
	            <!-- div class="w-full flex mb-2">
	            	<button id="getUsers" class="w-full font-semibold py-2 rounded-l-3xl bg-gray-100 hover:bg-gray-50 mr-1">Users</button>
	            	<button id="getFriends" class="w-full font-semibold py-2 rounded-r-3xl bg-gray-200 hover:bg-gray-50">Friends</button>
	            </div -->
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
	        <div class="w-full h-full flex flex-col overflow-hidden bg-gray-300 rounded-lg p-3">
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
	            <div class="w-full flex flex-row p-1 mb-3 h-9 rounded-3xl bg-gray-50">
                    <svg id="mdi-account-search" class="h-7 w-7 mx-2 fill-gray-300" viewBox="0 0 24 24"><path d="M15.5,12C18,12 20,14 20,16.5C20,17.38 19.75,18.21 19.31,18.9L22.39,22L21,23.39L17.88,20.32C17.19,20.75 16.37,21 15.5,21C13,21 11,19 11,16.5C11,14 13,12 15.5,12M15.5,14A2.5,2.5 0 0,0 13,16.5A2.5,2.5 0 0,0 15.5,19A2.5,2.5 0 0,0 18,16.5A2.5,2.5 0 0,0 15.5,14M10,4A4,4 0 0,1 14,8C14,8.91 13.69,9.75 13.18,10.43C12.32,10.75 11.55,11.26 10.91,11.9L10,12A4,4 0 0,1 6,8A4,4 0 0,1 10,4M2,20V18C2,15.88 5.31,14.14 9.5,14C9.18,14.78 9,15.62 9,16.5C9,17.79 9.38,19 10,20H2Z" /></svg>
                        <input type="text" id="searchMobile" placeholder="Search" maxlength="100" class="w-full bg-gray-50 focus:outline-none mr-2">
                </div>
	            <!-- div class="w-full flex mb-2">
	            	<button id="getUsersMobile" class="w-full font-semibold py-2 rounded-l-3xl bg-gray-100 hover:bg-gray-50 mr-1">Users</button>
	            	<button id="getFriendsMobile" class="w-full font-semibold py-2 rounded-r-3xl bg-gray-200 hover:bg-gray-50">Friends</button>
	            </div -->
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