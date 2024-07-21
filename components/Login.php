<?php
function loginPage($title) {
	?>
	<div class="bg-gray-300 h-screen flex items-center justify-center">
		<div class="bg-white p-8 rounded shadow-md w-96 m-12">
	        <h2 class="text-2xl font-semibold text-blue-600 mb-1 text-center">WebChat</h2>
	        <form id="myform" method="POST">
	        	<label for="username" class="block pt-3 text-gray-700 text-sm font-medium mb-2">Username</label>
	            <input type="text" id="username" name="username" class="w-full p-2 border-2 rounded focus:outline-none focus:border-blue-500 mb-1" maxlength="50" required>
	            <label for="password" class="block pt-3 text-gray-700 text-sm font-medium mb-2">Password</label>
	            <input type="password" id="password" name="password" class="w-full p-2 border-2 rounded focus:outline-none focus:border-blue-500 mb-6" maxlength="75" required>
	            <button type="submit" name="login" class="w-full p-2 rounded bg-blue-500 text-white hover:bg-blue-600 focus:bg-blue-600 mb-4">Login</button>
	            <p class="text-center text-sm">No account? Register <a href="?page=signup" class="text-blue-500">here</a>.</p>
	        </form>
	    </div>
	</div>
	<script type="text/javascript">document.title = "<?php echo $title; ?>";</script>
<?php
}
?>