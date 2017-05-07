var trace = console.log;
//var trace = function(){};

/**
 * Записыватель
 * @constructor
 */
var Recorder = function (key) {
    this.key = key;
};

/**
 * @type {string}
 */
Recorder.prototype.key;

/**
 * Участник процесса
 * @constructor
 */
var Participant = function (id, name, role, key, room) {
    this.id = id;
    this.name = name;
    this.role = role;
    this.key = key;
    this.room = room;
};

/**
 * @type {string}
 */
Participant.prototype.id;

/**
 * @type {string}
 */
Participant.prototype.name;

/**
 * @type {string}
 */
Participant.prototype.key;

/**
 * @type {string}
 */
Participant.prototype.role;

/**
 * @type {string}
 */
Participant.prototype.room;

/**
 * @type {string}
 */
Participant.prototype.status;


/**
 * Координатор работы команды
 * @constructor
 */
var Coordinator = function () {

};

/**
 *
 * @type {{}}
 */
Coordinator.prototype.rooms = {};

/**
 *
 * @type {{}}
 */
Coordinator.prototype.recorders = {};

/**
 * Обрабатывает данные, пришедшие от клиента
 * @param {WebSocket} socket
 * @param {string} message
 */
Coordinator.prototype.dispatch = function (socket, message) {

    try {
        var data = JSON.parse(message);
    } catch (e) {
        trace('Message parsing exception: ', e.message);
        trace('Message was: ', message);

        return;
    }

    if (typeof data.type == 'undefined') {
        return;
    }

    switch (data.type) {
        case 'join':
            this.registerParticipant(socket, data);
            break;
        case 'register_recorder':
            this.registerRecorder(socket, data);
            break;
        case 'get_users_list':
            this.getUsersList(socket);

            break;
        case 'set_status':
            if (typeof socket['participant'] == 'object') {
                this.setStatus(socket['participant'], data.status);
            }
            break;
        // Только люди могут посылать команды рекордерам
        case 'command':
            if (typeof socket['participant'] == 'object') {
                this.performCommand(socket['participant'], data);
            }

            break;
        case 'status':
            break;
        default :
            break;
    }
};

/**
 * Устанавливает статус участника
 *
 * @param {Participant} participant
 * @param {string} status
 */
Coordinator.prototype.setStatus = function (participant, status) {
    participant.status = status;


    if (participant.role != 'user_head_manager') {
        this.notifyHead(participant, {
            'type': 'status_changed',
            'user': participant.id,
            'status': participant.status
        });
    }
};


/**
 * Получает список пользователей для руководителя
 *
 * @param {WebSocket} socket
 */
Coordinator.prototype.getUsersList = function (socket) {
    if (!socket['participant'] || socket['participant'].role != 'user_head_manager') {
        trace('Not head try to get getUsersList');

        return;
    }

    var data = {
        'type': 'users_list',
        'users': []
    };

    for(var i in this.rooms[socket['participant'].room]){
        if(!this.rooms[socket['participant'].room].hasOwnProperty(i)){
            continue;
        }

        data.users.push({
            'id': this.rooms[socket['participant'].room][i]['participant'].id,
            'status': this.rooms[socket['participant'].room][i]['participant'].status
        })
    }

    socket.send(JSON.stringify(data));
};

/**
 * Отправляет сообщение всем сокетам комнаты участника, кроме него самого
 *
 * @param {Participant} participant
 * @param {{}} data
 */
Coordinator.prototype.broadcast = function (participant, data) {
    if (!participant || !participant.room) {
        trace('Participant associated with no room');

        return;
    }

    for(var i in this.rooms[participant.room]){
        if(!this.rooms[participant.room].hasOwnProperty(i)){
            continue;
        }

        if (participant.id != this.rooms[participant.room][i]['participant'].id) {
            this.rooms[participant.room][i].send(JSON.stringify(data));
        }
    }
};

/**
 * Отправляет сообщение всем сокетам комнаты
 *
 * @param {Participant} participant
 * @param {{}} data
 */
Coordinator.prototype.notifyHead = function (participant, data) {
    if (!participant) {
        trace('Socket try to left coordinator but it has no participant attached!');

        return;
    }

    if (!participant.room || !participant.id) {
        trace('Socket not associated with room or user');

        return;
    }

    if (!this.rooms[participant.room]) {
        trace('There is no room with name "' + socket['participant'].room + '"');

        return;
    }

    for(var i in this.rooms[participant.room]){
        if(!this.rooms[participant.room].hasOwnProperty(i)){
            continue;
        }

        if (this.rooms[participant.room][i]['participant'].role == 'user_head_manager') {
            trace('Notifying head', data);

            this.rooms[participant.room][i].send(JSON.stringify(data));
        }
    }
};

/**
 * Присоединяет сокет к комнате
 *
 * @param {WebSocket} socket
 * @param data
 */
Coordinator.prototype.registerParticipant = function (socket, data) {
    if (!data.room || !data.user_id || !data.role || !data.user_name || !data.key) {
        trace('Malformed data received!');

        return;
    }

    if (!this.rooms[data.room]) {
        this.rooms[data.room] = {};

        trace('Creating room "' + data.room + '"');
    }

    socket['participant'] = new Participant(data.user_id, data.user_name, data.role, data.key, data.room);

    socket['participant'].status = 'online';

    this.rooms[socket['participant'].room][socket['participant'].id] = socket;

    trace('User ' + socket['participant'].name + ' joined room "' + socket['participant'].room + '"');

    if (socket['participant'].role != 'user_head_manager') {
        this.notifyHead(socket['participant'], {
            'type': 'status_changed',
            'user': socket['participant'].id,
            'status': socket['participant'].status
        });
    }
};

/**
 * Удаляет пользователя из комнаты
 *
 * @param {Participant} participant
 */
Coordinator.prototype.unRegisterParticipant = function (participant) {
    if (!participant) {
        trace('Socket try to left coordinator but it has no participant attached!');

        return;
    }

    if (!participant.room) {
        trace('Participant has no room!');

        return;
    }

    if (!this.rooms[participant.room]) {
        trace('There is no room with name "' + participant.room + '"');

        return;
    }

    if (!this.rooms[participant.room][participant.id]) {
        trace('Room has no such participant "' + participant.id + '"');

        return;
    }

    trace('User ' + participant.name + ' left room "' + participant.room + '"');

    delete this.rooms[participant.room][participant.id];

    if (!Object.keys(this.rooms[participant.room]).length) {
        trace('Room "' + participant.room + '" has no participants now - deleting room');

        delete this.rooms[participant.room];
    } else {
        trace('In room "' + participant.room + '" left ' + Object.keys(this.rooms[participant.room]).length + ' participants');
    }

    if (participant.role != 'user_head_manager') {
        this.notifyHead(participant, {
            'type': 'status_changed',
            'user': participant.id,
            'status': 'offline'
        });
    }
};


/**
 * Регистрирует записывающую программу
 *
 * @param {Participant} participant
 * @param data
 */
Coordinator.prototype.performCommand = function (participant, data) {
    if (!participant) {
        trace('Participant param is required!');

        return;
    }

    if (!participant.key) {
        trace('Participant try to perform command but have no key!');

        return;
    }

    if (typeof this.recorders[participant.key] == 'object') {
        trace('Command to recorder "' + participant.key + '": ' + data.command);

        this.recorders[participant.key].send(JSON.stringify(data));
    }
};

/**
 * Регистрирует записывающую программу
 *
 * @param {WebSocket} socket
 * @param data
 */
Coordinator.prototype.registerRecorder = function (socket, data) {
    if (!data.key) {
        trace('Recorder trying to register but have no key!');

        return;
    }

    socket['recorder'] = new Recorder(data.key);

    this.recorders[socket['recorder'].key] = socket;

    trace('Recorder registered using key: ' + data.key);
};

/**
 * Удаляет записывающую программу из списка
 *
 * @param {Recorder} recorder
 */
Coordinator.prototype.unRegisterRecorder = function (recorder) {
    if (!recorder.key) {
        trace('Recorder trying to unregister but have no key!');

        return;
    }

    delete this.recorders[recorder.key];

    trace('Recorder disconnected ' + recorder.key);
};

var server = new require('ws').Server({port: 8002});

var coordinator = new Coordinator();

server.on('connection', function (socket) {

    trace('Client connected. Memory usage:', process.memoryUsage().heapUsed);

    socket.on('message', function (message) {
        coordinator.dispatch(socket, message);
    });

    socket.on('close', function () {
        if (typeof socket['participant'] != 'undefined') {
            coordinator.unRegisterParticipant(socket['participant']);
        } else if (typeof socket['recorder'] != 'undefined') {
            coordinator.unRegisterRecorder(socket['recorder']);
        } else {
            trace("Unassigned socket closed connection.");
        }
    });
});

trace("Coordinator listen port 8002");