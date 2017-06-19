### Requirements
> Node js version >= 6.11, or choose nodejs with included async/await natively

### Installation
```sh
$ npm install
```

### How to start
Cluster
```sh
$ node bin/app-cluster
```
Single thread
```sh
$ node bin/app
```
Cluster with pm2
```sh
$ pm2 start app.json
```

### Next todo
* Print menu orders command (server side), see `GET /printKitchen` route
* Print billing command (server side), see `GET /printBill` route
* Open cash draw command (server side), see `GET /openCashDraw` route
* Print cashier report command (server side), see `GET /printCashierReport` route
* Close cashier query (please fix SQL query at `public/js/cashier.js`)
* Additional tax / service (please add at `public/js/order.js`)
* Open menu (please add at `public/js/order.js`)
* Void menu (please add at `public/js/order.js`)
* Transform sql to transactional for `add order menu` and `payment`