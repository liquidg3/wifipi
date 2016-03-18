var exec            = require('child_process').exec,
    _               = require('lodash'),
    socket          = require('socket.io-client')('https://appointments.spruce.me'),
    // socket          = require('socket.io-client')('https://taysmacbook.ngrok.io'),
    reg             = /..:..:..:..:..:../i,
    connectedUsers  = {};

/**
 * Check all connected devices
 */
var check = function () {

    if (!socket.connected) {
        return;
    }

    exec('arp -a', function (err, stdout, stderr) {

        if (stdout) {

            var peeps       = stdout.split('\n'),
                connected   = [];

            //loop through each connected user and get their mac address
            _.each(peeps, function (peep) {

                var matches = reg.exec(peep),
                    mac;

                if (matches) {

                    mac = matches[0];
                    connected.push(mac);

                    if (!connectedUsers[mac]) {
                        connectedUsers[mac] = true;
                        userConnected(mac);
                    }

                }

            });

            //check all previously connected users and see if any are missing now
            _.each(connectedUsers, function (val, mac) {

                if (connected.indexOf(mac) === -1) {

                    userDisconnected(mac);
                    delete connectedUsers[mac];

                }


            });


        }

    });

};


var userConnected = function (mac) {
    console.log('user connected', mac);
    socket.emit('captive-checkin', mac);
};

var userDisconnected = function (mac) {
    console.log('user disconnected', mac);
    socket.emit('captive-checkout', mac);
};


setInterval(check, 2000);