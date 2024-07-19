<?php
require '../backend/connection.php';
require '../backend/verifyLogin.php';
require '../backend/generateUID.php';
require '../components/SweetAlert.php';
require '../components/HTML.php';
require '../components/SideBar.php';
require '../components/MessageList.php';

$html = new HTML("WebChat - My Chats");
$html->addLink('icon', 'https://static.xx.fbcdn.net/rsrc.php/yb/r/hLRJ1GG_y0J.ico');
$html->addScript("../js/tailwind3.4.5.js");
$html->addScript("../js/jquery-3.7.1.min.js");
$html->addScript("../js/sweetalert.min.js");
$html->startBody();

function clearWebchatCookies() {
	setcookie('wcipa-ai', '', time() + (86400 * 30), '/');
	setcookie('wcipa-ui', '', time() + (86400 * 30), '/');
	setcookie('wcipa-pw', '', time() + (86400 * 30), '/');
}

if (!verifyLogin($pdo)) {
	clearWebchatCookies();
	swalThen("Please login again.", "", "info", "() => window.location.href = '../'");
}

sideBar("mobile");

?>

<div class="h-dvh grid grid-cols-1 md:grid-cols-3">
    <!-- Left sidebar for friends list (hidden on mobile) -->
    <?php sideBar("desktop"); ?>

    <!-- Main chat area (takes 2 columns on medium screens and larger) -->
    <div class="h-dvh md:col-span-2 p-4">
        <div class="w-full h-full flex flex-col overflow-hidden bg-gray-300 rounded-lg p-4">
            <!-- User/Friend name (Top) -->
            <div class="flex mb-4">
            	<div id="userHeader" class="w-full flex items-center justify-start">
            	</div>
				<button id="openSideBarMobile" class="hover:bg-gray-50 rounded px-2">
					<svg class="w-8 h-8 justify-end md:hidden" id="mdi-menu" viewBox="0 0 24 24">
						<path d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z" />
					</svg>
				</button>
            </div>
            <!-- Chat content (Middle) -->
            <div id="scrollableChats" class="flex-1 px-2 overflow-y-auto">
                <div id="chatContent" class="mb-4">
                </div>
            </div>
            <!-- Textarea and send button (Bottom) -->
            <div id="bottomTextBar" class="flex mt-4">
                <textarea id="messageContent" class="w-full rounded-3xl h-11 px-4 py-2 border-2 border-gray-300 focus:outline-none focus:border-blue-500 resize-none" rows="2" placeholder="Type your message..."></textarea>
                <button id="sendMessage" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-3xl hover:bg-blue-600 focus:outline-none">Send</button>
            </div>
            <div id="noUserSelMessage" class="flex w-full h-full">
            	<p class="w-full flex items-center justify-center">
            		Get started by clicking on user/friend's name.
            	</p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	const senderUserId = "<?php echo $_COOKIE['wcipa-ui']; ?>";
	var receiverUserId = "<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>";
	const $btnGetUsers = $("#getUsers, #getUsersMobile");
	const $usersFriendsList = $("#usersFriendsList, #usersFriendsListMobile");
	const $sideBarMobile = $("#sideBarMobile");
	const $toggleSideBarMobile = $("#toggleSideBarMobile");
	const $userHeader = $("#userHeader");
	const $scrollableChats = $("#scrollableChats");
	const $chatContent = $("#chatContent");
	const $bottomTextBar = $("#bottomTextBar");
	const $messageContent = $("#messageContent");
	const $sendMessage = $("#sendMessage");
	const $noUserSelMessage = $("#noUserSelMessage");
	var isSideBarMobileOpened = false;

	$usersFriendsList.html('<p class="text-center"><br>Getting list of users...</p>');
	$sideBarMobile.hide();

    if (receiverUserId.length === 0) {
    	$bottomTextBar.hide();
    }

	function clickToShowHide(button_id, target_show_hide) {
	    const target = $(target_show_hide);
	    target.hide();
	    $(button_id).click((e) => {
	        if (isSideBarMobileOpened) {
	            target.fadeOut();
	        } else {
	            target.fadeIn();
	        }
	        isSideBarMobileOpened = !isSideBarMobileOpened;
	    });
	}

	clickToShowHide("#openSideBarMobile, #closeSideBarMobile", "#sideBarMobile");

	async function getUsers() {
		try {
			const response = await fetch("../backend/getUsersHTML.php");
			$usersFriendsList.html(await response.text());
		} catch (error) {
			$usersFriendsList.html('<p class="text-center"><br>Unable to display all users.</p>');
		}
	}

	getUsers();
	$scrollableChats.scrollTop($scrollableChats.prop("scrollHeight"));

	$btnGetUsers.on("click", function() {
		getUsers();
	});

	const socket = new WebSocket('ws://<?php echo $_SERVER['HTTP_HOST']; ?>:8080');

	socket.onopen = function(event) {
	    console.log('WebSocket connection established.');
	    socket.send(JSON.stringify({
            type: 'register',
            user_id: '<?php echo $_COOKIE['wcipa-ui']; ?>'
        }));
	};

	socket.onmessage = function(event) {
		getUsers();
		const message = JSON.parse(event.data);
		console.log(JSON.stringify(message));
		/*
	    Issue on this code: The chat bubbles no longer adds on the other side even the receiver_user_id of of the sent websocket is the receiverUserId of the current chat
		if (message['type'] === 'chat_message' && message['receiver_user_id'] == receiverUserId) {
	    	if (message['sender_user_id'] === senderUserId) {
	    		chatContent.innerHTML += `
					<div class="flex items-center text-white justify-end mt-3"><div class="bg-blue-500 px-3 py-2 rounded-2xl max-w-xs"><pre class=font-sans>${message['content']}</pre></div></div>`;
				messageContent.value = "";
	    	} else {
	    		chatContent.innerHTML += `
					<div class="flex items-center text-black justify-start mt-3"><div class="bg-white px-3 py-2 rounded-2xl max-w-xs"><pre class=font-sans>${message['content']}</pre></div></div>`;
	    	}
	    	$scrollableChats.scrollTop($scrollableChats.prop("scrollHeight"));
	    }
	    */
	    if (message['type'] === 'chat_message') {
	    	updateAllMessages(message['receiver_user_id']);
	    }
	};

	socket.onerror = function(error) {
		swal("An error occured", "The webserver socket failed to connect.", "error");
	};

	socket.onclose = function(event) {
		console.log('WebSocket connection closed.');
	};

	$sendMessage.on("click", () => {
		if ($messageContent.val().trim() === "") return;
		const messageToSend = {
			type: 'chat_message',
			field1: "<?php echo $_COOKIE['wcipa-ai']; ?>",
			field2: "<?php echo $_COOKIE['wcipa-pw']; ?>",
			sender_user_id: senderUserId,
			receiver_user_id: receiverUserId,
			content: $messageContent.val()
		};
		socket.send(JSON.stringify(messageToSend));
		$messageContent.val('');
	});

	async function getMessages(receiverUserIdToSend) {
		history.pushState(null, null, '?id=' + receiverUserIdToSend);
		receiverUserId = receiverUserIdToSend;
		$bottomTextBar.css("display", "flex");
		$("#sideBarMobile").hide();
		isSideBarMobileOpened = false;
		$noUserSelMessage.hide();
		await updateUserHeader(receiverUserIdToSend);
		await updateAllMessages(receiverUserIdToSend);
	}

	async function updateUserHeader(receiverUserIdToSend) {
		const responseheader = await fetch("../backend/getHeaderUserHTML.php?uid=" + receiverUserId);
		$userHeader.html(await responseheader.text());
	}

	function timeSince(date) {
    	const intervals = [
    		{ label: "year", seconds: 31536000 },
    		{ label: "month", seconds: 2592000 },
    		{ label: "day", seconds: 86400 },
    		{ label: "hour", seconds: 3600 },
    		{ label: "minute", seconds: 60 },
    	];
    	const now = new Date();
    	const seconds = Math.floor((now - date) / 1000);
    	for (const interval of intervals) {
    		const count = Math.floor(seconds / interval.seconds);
    		if (count >= 1) {
    			return `${count} ${interval.label}${count > 1 ? 's' : ''} ago`;
    		}
    	}
    	return "just now";
	}

	async function updateAllMessages(receiverUserIdToSend) {
		const responsechat = await fetch("../backend/getReceiverChats.php?uid=" + receiverUserId);
		const textjson = await responsechat.text();
		const resultOfJson = JSON.parse(textjson);
		$chatContent.html('');
		const conversations = resultOfJson['conversation'];
		if (conversations.length > 0 && resultOfJson['success']) {
			conversations.forEach(chat => {
				const messageElement = $('<div>').addClass('flex mt-3');
				if (chat.sender_user_id === senderUserId) {
					messageElement.addClass('justify-end');
					messageElement.html(`<div title="Sent ${timeSince(new Date(chat.sent_dt))}" class="bg-blue-500 text-white px-4 py-2 rounded-2xl max-w-xs whitespace-pre-wrap">${chat.text_sent}</div>`);
        		} else {
        			messageElement.addClass('justify-start');
        			messageElement.html(`<div title="Sent ${timeSince(new Date(chat.sent_dt))}" class="bg-gray-100 px-4 py-2 rounded-2xl max-w-xs whitespace-pre-wrap">${chat.text_sent}</div>`);
				}
				$chatContent.append(messageElement);
			});
		}
		$scrollableChats.scrollTop($scrollableChats.prop("scrollHeight"));
	}

	$messageContent.on("keydown", function(event) {
		if (event.key === "Enter" && !event.shiftKey) {
	    	event.preventDefault();
	    	$sendMessage.click();
		}
	});
</script>

<?php
if (isset($_GET['id'])) {
	?>
	<script type="text/javascript">getMessages("<?php echo $_GET['id']; ?>");</script>
	<?php
}
$html->endBody();
?>