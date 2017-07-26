let nodeWindows = require('node-windows').Service;
let service = new nodeWindows({
	name:'POS',
	description: 'POS standalone',
	script: __dirname + '\\bin\\app'
});
service.on('install',function(){
	service.start();
});
service.on('uninstall',function(){
	console.log('Uninstall complete.');
	console.log('The service exists: ',service.exists);
});
//
module.exports = service;