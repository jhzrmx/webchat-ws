<?php
function signupPage($title) {
	?>
	<div class="bg-gray-300 h-screen flex items-center justify-center">
		<div class="bg-white p-8 rounded shadow-md w-96 m-12">
	        <h2 class="text-2xl font-semibold text-blue-600 mb-1 text-center">WebChat</h2>
	        <form method="POST">
	        	<label for="Full Name" class="block pt-3 text-gray-700 text-sm font-medium mb-2">Full Name</label>
	            <input type="text" id="fullname" name="fullname" class="w-full p-2 border-2 bg-gray-100 rounded focus:outline-none focus:border-blue-500 mb-1" maxlength="100" required>
	        	<label for="username" class="block pt-3 text-gray-700 text-sm font-medium mb-2">Username</label>
	            <input type="text" id="username" name="username" class="w-full p-2 border-2 bg-gray-100 rounded focus:outline-none focus:border-blue-500 mb-1" maxlength="50" required>
	            <label for="password" class="block pt-3 text-gray-700 text-sm font-medium mb-2">Password</label>
	            <input type="password" id="password" name="password" class="w-full p-2 bg-gray-100 border-2 rounded focus:outline-none focus:border-blue-500 mb-6" maxlength="75" required>
	            <button type="submit" name="signup" class="w-full p-2 rounded bg-blue-500 text-white hover:bg-blue-600 hover:cursor-pointer focus:bg-blue-600 mb-4">Signup</button>
	            <p class="text-center text-sm">Already have an account? <a href="?page=" class="text-blue-500">Login</a> instead.</p>
	        </form>
	    </div>
	</div>
	<script type="text/javascript">document.title = "<?php echo $title; ?>";</script>
<?php
}
?>