# WebChat
A simple real-time chat application written in PHP8.1.28 with Rachet as websocket and MySQL as database
The sql execution uses PHP Data Objects, which also means you can use other database as well.

## How to use?
1. Clone this repository to your desired webserver (i.e., `htdocs` folder in XAMPP)
2. Start the MySQL (or other RDB) server, then import the `webchat_ws.sql` file to your database
3. Run WebSocket server via command line:
   ```
   php start_chat_server
   ```
4. Run the webserver and navigate to the website
