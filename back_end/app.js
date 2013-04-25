var connect = require('connect');
var app = connect()
  .use(function(req, res) {
    res.end('Hello Connect!');
  })
  .listen(process.env.VMC_APP_PORT || 1337);
