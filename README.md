# WebChat
A simple real-time chat application written in PHP8.1.28 with Rachet as websocket and MySQL as database
The sql execution uses PHP Data Objects, which also means you can use other database as well.

## How to use?
1. Clone this repository to your desired webserver (i.e., `htdocs` folder in XAMPP)
2. Start the MySQL (or other DB) server, then import the `webchat_ws.sql` file to your database
3. Navigate to `backend/` directory
4. Run WebSocket server via command line:
   ```
   php chatserver.php
   ```
5. Run the webserver and navigate to the website
