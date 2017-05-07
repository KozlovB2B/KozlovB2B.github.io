var server = new require('ws').Server({port: 8002});
var fs = require('fs');
var path = require('path');

var recorders = {};

function dispatch(socket, message) {
    if (typeof message == 'object') {

        if (socket.filename) {
            var file_to_write = 'files/' + socket.filename + '.mp3';

            fs.writeFile(path.resolve(__dirname, file_to_write), new Buffer(message), function (err) {
                if (err) {
                    console.log('Error while writing ' + file_to_write, err);
                } else {
                    console.log(file_to_write + ' now available!');
                }
            });
        }
    } else {
        try {
            var data = JSON.parse(message);
        } catch (e) {
            console.log('Unknown message: ' + message);

            return false;
        }

        if (!data.command) {
            console.log('Unknown message: ' + message);
        }

        if (!data.key) {
            console.log('Unknown message: ' + message);
        }

        switch (data.command) {
            case 'set_filename':
                if (data.filename) {
                    socket.filename = data.filename;
                    console.log(socket.filename + ' has set');
                } else {
                    console.log('No filename specified!');
                }

                break;
            case 'register':
                if (!recorders[data.key]) {
                    socket.key = data.key;
                    recorders[data.key] = socket;
                    console.log('Registration success for: ' + data.key);
                    socket.send(JSON.stringify({'message': 'registered'}));
                } else {
                    console.log('Registration fail for: ' + data.key + ' - recorder already connected!');
                    socket.send(JSON.stringify({'message': 'not_registered'}));
                }

                break;
            case 'ping':
                if (recorders[data.key]) {
                    console.log('Recorder available: ' + data.key);
                    socket.send(JSON.stringify({'message': 'recorder_available'}));
                } else {
                    console.log('Recorder unavailable: ' + data.key);
                    socket.send(JSON.stringify({'message': 'recorder_unavailable'}));
                }

                break;
            case 'start':
                if (recorders[data.key]) {
                    recorders[data.key].send(JSON.stringify({'command': 'start', 'filename': data.filename}));
                    socket.send(JSON.stringify({'message': 'recorder_available'}));
                    console.log('Start command sent to ' + data.key);
                } else {
                    console.log('Recorder ' + data.key + " unavailable.");
                    socket.send(JSON.stringify({'message': 'recorder_unavailable'}));
                }

                break;
            case 'stop':
                if (recorders[data.key]) {
                    recorders[data.key].send(JSON.stringify({'command': 'stop'}));
                    socket.send(JSON.stringify({'message': 'recorder_available'}));

                    console.log('Stop command sent to ' + data.key);
                } else {
                    console.log('Recorder ' + data.key + " unavailable.");
                    socket.send(JSON.stringify({'message': 'recorder_unavailable'}));
                }

                break;
        }
    }
}

server.on('connection', function (socket) {
    socket.on('message', function (message) {
        try {
            dispatch(socket, message);
        } catch (e) {
            console.log(e.message);
        }
    });

    socket.on('close', function () {
        try {
            if (socket.key && recorders[socket.key]) {
                console.log('Deleting ' + socket.key + ' from recorders pool.');
                delete recorders[socket.key];
            } else {
                console.log('Socket has no pool.');
            }
        } catch (e) {
            console.log(e.message);
        }
    });
});

console.log("Records coordinator listen port 8002");