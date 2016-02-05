var io = require('socket.io');
var connect = require('connect');
var app = connect();
var server = app.listen(process.env.VMC_APP_PORT || 1337);
io = io.listen(server);

var Bitcoin = require('bitcoinjs');
node = new Bitcoin.Node();
node.start();

var chain = node.getBlockChain();
chain.addListener('txSave', function(e) {
  console.log(e);
});

app.use(function(req, res) {
  res.end('Hello Index');
});

io.sockets.on('connection', function (socket) {
  socket.emit('message', { result: 'Connection succeeded' });
});

/*
var testEmit = function() {
  io.sockets.emit('message', {now: new Date()});
}
setInterval(testEmit, 10000);
*/