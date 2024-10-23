// Import dependencies
const express = require('express');
const http = require('http');
const socketio = require("socket.io");

// Initialize Express and HTTP server
const app = express();
const server = http.createServer(app);

const io = socketio(server, {
    cors: {
      origin: "*",
      method: ["GET", "POST"],
    },
  });
// Set up Socket.IO on the HTTP server
// const io = new Server(server);

// Serve static files (HTML, CSS, JS) from the public directory
app.use(express.static('public'));

// Listen for connections to the server
io.on('connection', (socket) => {
    socket.on("sendMessage", async (message) => {
        console.log("dfgsdgsd ########", message);
         // save in database 
        io.emit("sendMessage", message);
      });

  // Handle user disconnection
  socket.on('disconnect', () => {
    console.log('user disconnected');
  });
});

// Run the server on port 3000
server.listen(3000, () => {
  console.log('listening on *:3000');
});
