<?php
require '../backend/connection.php';
require '../backend/generateUID.php';
require '../components/SweetAlert.php';
require '../components/HTML.php';
require '../components/MessageList.php';

$html = new HTML("WebChat - My Chats");
$html->addLink('icon', 'https://static.xx.fbcdn.net/rsrc.php/yb/r/hLRJ1GG_y0J.ico');
$html->addScript("../js/tailwind3.4.5.js");
$html->addScript("../js/sweetalert.min.js");
$html->startBody();

function clearWebchatCookies() {
	setcookie('wcipa-ai', '', time() + (86400 * 30), '/');
	setcookie('wcipa-ui', '', time() + (86400 * 30), '/');
	setcookie('wcipa-pw', '', time() + (86400 * 30), '/');
}

if (isset($_COOKIE['wcipa-ai']) && isset($_COOKIE['wcipa-ui']) && isset($_COOKIE['wcipa-pw'])) {
	try {
		$stmt = $pdo->prepare("SELECT * FROM `accounts` JOIN `users` ON `accounts`.`user_id` = `users`.`user_id` WHERE `accounts`.`account_id` = :account_id");
		$stmt->bindParam(':account_id', $_COOKIE['wcipa-ai']);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (count($rows) > 0) {
			foreach ($rows as $row) {
				if ($_COOKIE['wcipa-pw'] !== $row['password']) {
					clearWebchatCookies();
					swalThen("Please login again.", "", "info", "() => window.location.href = '../'");
				}
				break;
			}
		}
	} catch (PDOException $e) {
		swal("Error", $e->getMessage(), "error");
	}
} else {
	clearWebchatCookies();
	header("location: ../");
}
?>

<div class="h-dvh grid grid-cols-1 md:grid-cols-3">
    <!-- Left sidebar for friends list (hidden on mobile) -->
    <div class="hidden h-dvh pl-4 py-4 md:block ">
        <div class="w-full h-full overflow-hidden bg-gray-300 rounded-lg p-3">
            <!-- Friends list -->
            <h2 class="text-2xl font-bold mx-2 mb-3">My Chats</h2>
            <div class="w-full flex mb-2">
            	<button id="getUsers" class="w-full font-semibold py-2 rounded-l-lg bg-gray-100 hover:bg-gray-50">Users</button>
            	<button class="w-full font-semibold py-2 rounded-r-lg bg-gray-200 hover:bg-gray-50">Friends</button>
            </div>
            <ul id="usersFriendsList" class="w-full">
                <!-- Example list items -->
            </ul>
        </div>
    </div>

    <!-- Main chat area (takes 2 columns on medium screens and larger) -->
    <div class="h-dvh md:col-span-2 p-4">
        <div class="w-full h-full flex flex-col overflow-hidden bg-gray-300 rounded-lg p-4">
        	
            <!-- User/Friend name (Top) -->
            <div id="userHeader" class="flex items-center justify-start mb-6">
            </div>
            
            <!-- Chat content (Middle) -->
            <div id="scrollableChats" class="flex-1 px-2 overflow-y-auto">
                <div id="chatContent" class="mb-4">
                </div>
            </div>
            
            <!-- Textarea and send button (Bottom) -->
            <div class="flex mt-4">
                <textarea id="messageContent" class="w-full rounded-3xl h-11 px-4 py-2 border-2 border-gray-300 focus:outline-none focus:border-blue-500 resize-none" rows="2" placeholder="Type your message..."></textarea>
                <button id="sendMessage" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-3xl hover:bg-blue-600 focus:outline-none">Send</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	const sendMessage = document.getElementById("sendMessage");
	const btnGetUsers = document.getElementById("getUsers");
	const usersFriendsList = document.getElementById("usersFriendsList");
	const userHeader = document.getElementById("userHeader");
	const messageContent = document.getElementById("messageContent");
	const scrollableChats = document.getElementById("scrollableChats");
	const chatContent = document.getElementById("chatContent");
	const senderUserId = "<?php echo $_COOKIE['wcipa-ui']; ?>";
    var receiverUserId = "";

    async function getUsers() {
    	usersFriendsList.innerHTML = `<p class="text-center"><br>Getting list of users...</p>`;
    	try {
    		const response = await fetch("../backend/getUsersHTML.php");
    		usersFriendsList.innerHTML = await response.text();
    	} catch (error) {
    		usersFriendsList.innerHTML = `<p class="text-center"><br>Unable to display all users.</p>`;
    	}
    }
    getUsers();
    btnGetUsers.onclick = () => {
    	getUsers();
    }

    const socket = new WebSocket('ws://<?php echo $_SERVER['HTTP_HOST']; ?>:8080');

	socket.onopen = (event) => {
	    console.log('WebSocket connection established.');
	};

	socket.onmessage = (event) => {
	    const message = JSON.parse(event.data);
	    // console.log('Received message:', message);
	    if (message['type'] === 'chat_message') {
	    	if (message['sender_user_id'] === senderUserId) {
	    		chatContent.innerHTML += `
					<div class="flex items-center text-white justify-end mt-3"><div class="bg-blue-500 px-3 py-2 rounded-2xl max-w-xs"><pre class=font-sans>${message['content']}</pre></div></div>`;
				messageContent.value = "";
	    	} else {
	    		chatContent.innerHTML += `
					<div class="flex items-center text-black justify-start mt-3"><div class="bg-white px-3 py-2 rounded-2xl max-w-xs"><pre class=font-sans>${message['content']}</pre></div></div>`;
	    	}
	    }
	    if (message['type'] === 'get_messages') {
	        chatContent.innerHTML = '';
	        const conversations = message['conversation'];
	        if (conversations.length > 0) {
	        	conversations.forEach(chat => {
		            const messageElement = document.createElement('div');
		            messageElement.classList.add('flex', 'mt-3');
		            if (chat.sender_user_id === senderUserId) {
		            	messageElement.classList.add('justify-end');
		                messageElement.innerHTML = `<div class="bg-blue-500 text-white px-4 py-2 rounded-2xl max-w-xs">${chat.text_sent}</div>`;
		            } else {
		                messageElement.classList.add('justify-start');
		                messageElement.innerHTML = `<div class="bg-gray-100 px-4 py-2 rounded-2xl max-w-xs">${chat.text_sent}</div>`;
		            }
		            chatContent.appendChild(messageElement);
		        });
	        }
	    }
		scrollableChats.scrollTop = scrollableChats.scrollHeight;
	};

	socket.onerror = (error) => {
	    swal("An error occured", "The webserver socket failed to connect.", "error");
	};

	socket.onclose = (event) => {
	    console.log('WebSocket connection closed.');
	};

	sendMessage.onclick = () => {
		getUsers();
		if (messageContent.value.trim() === "") return;
		const messageToSend = {
		    type: 'chat_message',
		    field1: "<?php echo $_COOKIE['wcipa-ai']; ?>",
		    field2: "<?php echo $_COOKIE['wcipa-pw'] ?>",
		    sender_user_id: senderUserId,
		    receiver_user_id: receiverUserId,
		    content: messageContent.value
		};
		socket.send(JSON.stringify(messageToSend));
    }

    async function getMessages(receiverUserIdToSend) {
    	history.pushState(null,null,'?id=' + receiverUserIdToSend);
    	receiverUserId = receiverUserIdToSend;
    	const responseheader = await fetch("../backend/getHeaderUserHTML.php?uid=" + receiverUserId);
    	userHeader.innerHTML = await responseheader.text();
    	const messageToSend = {
		    type: 'get_messages',
		    field1: "<?php echo $_COOKIE['wcipa-ai']; ?>",
		    field2: "<?php echo $_COOKIE['wcipa-pw'] ?>",
		    sender_user_id: senderUserId,
		    receiver_user_id: receiverUserIdToSend
		};
		scrollableChats.scrollTop = scrollableChats.scrollHeight;
		socket.send(JSON.stringify(messageToSend));
    }

    messageContent.addEventListener("keydown", (event) => {
        if (event.key === "Enter" && !event.shiftKey) {
            event.preventDefault();
            sendMessage.click();
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