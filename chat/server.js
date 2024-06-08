const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const path = require('path');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

app.use(express.static(path.join(__dirname, 'public')));

io.on('connection', (socket) => {
    console.log('a user connected');

    socket.on('chat message', (data) => {
        io.emit('chat message', data);
    });

    socket.on('disconnect', () => {
        console.log('user disconnected');
    });
});

app.get('/patient', (req, res) => {
    res.sendFile(path.join(__dirname, 'patient.html'));
});

app.get('/carer', (req, res) => {
    res.sendFile(path.join(__dirname, 'carer.html'));
});

server.listen(3000, () => {
    console.log('listening on *:3000');
});
