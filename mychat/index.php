<?php
require '../backend/connection.php';
require '../backend/verifyLogin.php';
require '../backend/generateUID.php';
require '../components/SweetAlert.php';
require '../components/HTML.php';
require '../components/SideBar.php';
require '../components/MessageList.php';

$html = new HTML("WebChat - My Chats");
$html->addLink('stylesheet', '../styles/inter-variable.css');
$html->addLink('icon', '../img/icons/favicon.png');
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

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare(" SELECT `full_name` FROM users WHERE `user_id` = :retrieved_user_id; ");
	$stmt->bindParam(':retrieved_user_id', $_GET['id']);
	$stmt->execute();
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($rows) == 0) {
		swalThen("Error", "The user you are trying to reach does not exist.", "error", "() => window.location.href = '../'");
		$html->endBody();
		exit();
	}
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
                <textarea id="messageContent" class="w-full rounded-3xl h-11 px-4 py-2 border-2 border-gray-300 focus:outline-none focus:border-blue-500 resize-none" rows="2" maxlength="2048" placeholder="Type your message..."></textarea>
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
	const senderAccId = "<?php echo $_COOKIE['wcipa-ai']; ?>";
	const senderHP = "<?php echo $_COOKIE['wcipa-pw']; ?>";
	var receiverUserId = "<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>";
	const $usersFriendsList = $("#usersFriendsList, #usersFriendsListMobile");
	const $scrollableChats = $("#scrollableChats");
	const $messageContent = $("#messageContent");
	var searchValue = "";
	var isSideBarMobileOpened = true;

	$usersFriendsList.html('<p class="text-center"><br>Getting list of users...</p>');

    if (receiverUserId === "") {
    	$("#bottomTextBar").hide();
    }

	function clickToShowHideSideBar(button_id, target_show_hide) {
	    const target = $(target_show_hide);
	    $(button_id).click((e) => {
	        if (isSideBarMobileOpened) {
	            target.fadeOut();
	        } else {
	            target.fadeIn();
	        }
	        isSideBarMobileOpened = !isSideBarMobileOpened;
	    });
	}

	clickToShowHideSideBar("#openSideBarMobile, #closeSideBarMobile", "#sideBarMobile");

	function debounce(func, timeout = 500){
		let timer;
		return (...args) => {
			clearTimeout(timer);
			timer = setTimeout(() => { func.apply(this, args); }, timeout);
		};
	}

	async function getUsers(search) {
		try {
			const url = search.length > 0 
			? `../backend/getUsersHTML.php?search=${encodeURIComponent(search)}` 
			: "../backend/getUsersHTML.php";
			const response = await fetch(url);
			$usersFriendsList.html(await response.text());
		} catch (error) {
			$usersFriendsList.html('<p class="text-center"><br>Unable to display users.</p>');
		}
	}

	const debouncedGetUsers = debounce(getUsers);
	$('#search, #searchMobile').on('input', function() {
		searchValue = $(this).val();
		$('#search, #searchMobile').val(searchValue);
		debouncedGetUsers(searchValue);
	});

	getUsers(searchValue);
	$scrollableChats.scrollTop($scrollableChats.prop("scrollHeight"));

	function playMessageSound() {
		const messageTone = new Audio("../audio/message.aac");
		messageTone.play();
	}

	const socket = new WebSocket('ws://<?php echo $_SERVER['HTTP_HOST']; ?>:8080');

	socket.onopen = function(event) {
	    console.log('WebSocket connection established.');
	    // TODO: User logged in to the same account on different device have issues in receiving a message due to user_id is used in clientId
	    socket.send(JSON.stringify({
            type: 'logged_in',
            user_id: senderUserId
        }));
	};

	socket.onmessage = function(event) {
		getUsers(searchValue);
		const message = JSON.parse(event.data);
		// console.log(JSON.stringify(message));
	    if (message['type'] === 'chat_message') {
	    	if (message['receiver_user_id'] === receiverUserId) {
	    		updateAllMessages(message['receiver_user_id']);
	    		console.log("I send the message here.");
	    	} else if (message['sender_user_id'] === receiverUserId) {
	    		updateAllMessages(message['sender_user_id']);
	    		console.log("I received the message from the current selected user.");
	    		playMessageSound();
	    	} else {
	    		console.log("I also received the message but not from the selected user.");
	    		playMessageSound();
	    	}
	    	$messageContent.attr("placeholder", "Type your message...");
	    }
	};

	socket.onerror = function(error) {
		swal("An error occured", "The WebSocket server failed to connect.", "error");
	};

	socket.onclose = function(event) {
		console.log('WebSocket connection closed.');
	};

	$("#logout, #logoutMobile").on("click", () => {
		swal({
            title: "Logout?",
            text: "You can log into your account at anytime.",
            icon: "info",
            buttons: true,
            buttons: {
                cancel: 'No',
                confirm : {text: "Yes", className:'bg-blue-500'},
            },
            dangerMode: false,
        }).then((willLogout) => {
            if (willLogout) {
                window.location.href = "../logout.php";
            }
        });
	});

	$("#sendMessage").on("click", () => {
		if ($messageContent.val().trim() === "") return;
		const messageToSend = {
			type: 'chat_message',
			field1: senderAccId,
			field2: senderHP,
			sender_user_id: senderUserId,
			receiver_user_id: receiverUserId,
			content: $messageContent.val().trim()
		};
		socket.send(JSON.stringify(messageToSend));
		$messageContent.attr("placeholder", "Sending...");
		$messageContent.val('');
	});

	window.addEventListener('popstate', function(event) {
		const urlParams = new URLSearchParams(window.location.search);
		receiverUserId = urlParams.get('id');
		if (receiverUserId) {
			getMessages(receiverUserId);
		}
	});

	async function getMessages(receiverUserIdToSend) {
		history.pushState(null, null, '?id=' + receiverUserIdToSend);
		receiverUserId = receiverUserIdToSend;
		$("#bottomTextBar").css("display", "flex");
		$("#sideBarMobile").hide();
		isSideBarMobileOpened = false;
		$("#noUserSelMessage").hide();
		await updateUserHeader(receiverUserIdToSend);
		await updateAllMessages(receiverUserIdToSend);
	}

	async function updateUserHeader(receiverUserIdToSend) {
		try {
			const responseheader = await fetch("../backend/getHeaderUserHTML.php?uid=" + receiverUserIdToSend);
			const userDetails = await fetch("../backend/getHeaderUser.php?uid=" + receiverUserIdToSend);
			$("#userHeader").html(await responseheader.text());
			const userDetailsText = await userDetails.text();
			const resultOfJson = JSON.parse(userDetailsText);
			if (resultOfJson['success']) {
				$(document).attr("title", "WebChat - " + resultOfJson.header[0].full_name);
			}
		} catch (error) {
			swal("Error", "An error occured while fetching user header: " + error, "error");
		}
	}

	async function updateAllMessages(receiverUserIdToSend) {
		try {
			const responsechat = await fetch("../backend/getReceiverChatsHTML.php?uid=" + receiverUserIdToSend);
			$("#chatContent").html(await responsechat.text());
		} catch (error) {
			swal("Error", "An error occured while fetching messages: " + error, "error");
		}
		$scrollableChats.scrollTop($scrollableChats.prop("scrollHeight"));
	}

	$messageContent.on("keydown", function(event) {
		if (event.key === "Enter" && !event.shiftKey) {
	    	event.preventDefault();
	    	$("#sendMessage").click();
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