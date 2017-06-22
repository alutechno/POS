var Service = require('node-windows').Service;
// Create a new service object
var svc = new Service({
	name:'POS',
	description: 'POS standalone',
	script: 'C:\\POS\\bin\\app'
});
// Listen for the "install" event, which indicates the
// process is available as a service.
svc.on('install',function(){
	svc.start();
});
svc.install();

svc.on('uninstall',function(){
	console.log('Uninstall complete.');
	console.log('The service exists: ',svc.exists);
});
// Uninstall the service.
//svc.uninstall();
