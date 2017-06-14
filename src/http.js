const fs = require('fs'),
    path = require('path'),
    ejs = require('ejs'),
    bluebird = require('bluebird'),
    express = require('express'),
    bodyParser = require('body-parser'),
    cors = require('cors'),
    mysql = require('mysql'),
    uuid = require('node-uuid'),
    dateformat = require('dateformat'),
    bcrypt = require('bcrypt');
//
const {STATUS_CODES} = require('http'),
    Glob = require(`./glob`),
    httpCode = require(`${Glob.home}/utils/http.code`),
    Code4 = require(`${Glob.home}/utils/code4`),
    Upload = require(`${Glob.home}/utils/upload`)(Glob.upload);
//
const {env, name, description, version, ip, port, home, factory, config, regEx} = Glob;
const Db = mysql.createPool(config.mysql);
const compileQuery = function (sql, data, cb) {
    Db.getConnection(function (err, conn) {
        if (err) {
            if (!conn) conn.release();
            cb(err);
        } else {
            if (!data) {
                conn.query(sql, function (err, rows, fields) {
                    conn.release();
                    cb(err, rows, fields);
                });
            } else {
                conn.query(sql, data, function (err, rows, fields) {
                    conn.release();
                    cb(err, rows, fields);
                });
            }
        }
    });
};
const http = function () {
    let app = express();
    let locals = app.locals;
    let Auth = require(`${Glob.home}/src/auth`);
    //
    locals.logged = false;
    locals.name = Glob.name;
    locals.version = Glob.version;
    locals.description = Glob.description;
    //
    /** **************************************************************************
     ** express.js setup
     ** **************************************************************************/
    app.set('env', Glob.env);
    app.set('title', Glob.name);
    app.set('port', port);
    app.set('x-powered-by', false);
    app.set('view engine', 'ejs');
    app.set('views', path.join(Glob.home, 'views'));
    /** **************************************************************************
     ** commonly middleware setup
     ** **************************************************************************/
    app.use(bodyParser.json());
    app.use(bodyParser.urlencoded({extended: true}));
    app.use(express.static(path.join(Glob.home, 'public')));
    app.use(cors(), function (req, res, next) {
        res.header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
        next();
    });
    if (app.get('env') === 'production') {
        app.set('trust proxy', 1);
    }
    /** **************************************************************************
     ** authentication as middleware
     ** **************************************************************************/
    app.use(async function (req, res, next) {
        let url = req._parsedUrl.pathname;
        let {httpVersion, method, headers, params, query, body} = req;
        let request = {httpVersion, url, method, headers, params, query, body};
        ['params', 'query', 'body'].forEach(function (s) {
            if (!Object.keys(request[s]).length) delete request[s];
        });
        //
        //console.log(process.pid.toString(), '>', JSON.stringify(request));
        console.log(process.pid.toString(), '>', JSON.stringify({url, method}));
        next();
    });
    /** **************************************************************************
     ** authentication : http request
     ** **************************************************************************/
    app.get('/', function (req, res, next) {
        if (locals.logged) return res.render('home');

        res.redirect('/login');
    });
    app.get('/order', function (req, res, next) {
        if (locals.logged) return res.render('order');

        res.redirect('/login');
    });
    app.get('/login', function (req, res, next) {
        if (locals.logged) {
            delete locals.message;
            return res.redirect('/');
        }

        locals.logged = false;
        locals.message = locals.message || "Please fill form input..";
        res.render('login');
    });
    app.get('/logout', async function (req, res, next) {
        if (locals.logged) {
            let {id, username} = locals.logged;
            let updateUser = 'UPDATE user SET token = NULL WHERE id = ?';
            compileQuery(updateUser, id, function (e, rows, fields) {
                if (e) {
                    locals.message = `Cannot destroy token for ${username} account!`;
                    res.redirect('/');
                } else {
                    locals.logged = false;
                    res.redirect('/login')
                }
            });
        } else {
            res.redirect('/login')
        }

    });
    app.post('/auth', async function (req, res, next) {
        if (locals.logged) return res.redirect('/');

        let {body} = req, {username, password} = body;
        let selectUser = `SELECT * FROM user WHERE name = '${username}'`;
        //
        compileQuery(selectUser, 0, function (e, rows, fields) {
            if (e || !rows.length) {
                locals.message = `Invalid username "${username}"!`;
                res.redirect('/login');
            } else if (rows.length) {
                let user = rows[0];
                if (user.password !== password) {
                    locals.message = `Invalid password "${password}" for ${username} account!`;
                    res.redirect('/login');
                } else {
                    let token = uuid.v4();
                    let updateUser = 'UPDATE user SET token = ? WHERE id = ?';
                    compileQuery(updateUser, [token, user.id], function (e, rows, fields) {
                        if (e) {
                            locals.message = `Cannot tokenize for ${username} account!`;
                            res.redirect('/login');
                        } else {
                            locals.logged = {id: user.id, username, token};
                            res.redirect('/');
                        }
                    })
                }
            }
        });
    });
    /** **************************************************************************
     ** error handling : http request
     ** **************************************************************************/
    app.use(function (req, res, next) {
        let code = httpCode.NOTFOUND;
        let err = new Error();
        err.status = code.status;
        err.message = code.message;
        next(err);
    });
    app.use(function (err, req, res, next) {
        // avoid .map file error request
        let verb1 = req.url.length - 7 == req.url.lastIndexOf('.js.map');
        let verb2 = req.url.length - 8 == req.url.lastIndexOf('.css.map');
        let verb3 = '/favicon.ico';
        let code = httpCode.INTERNALSERVERERROR;
        let status = err.status || code.status;
        let message = err.message || code.message;
        locals.ERR = {status, message, error: err.errors};
        if (STATUS_CODES[status] !== err.message) {
            locals.ERR.message = code.message;
            locals.ERR.trace = err.message;
        }
        //
        if (!(verb1 || verb2 || verb3)) console.log(process.pid.toString(), '> SERVER ERROR HANDLING!', JSON.stringify(locals.ERR, 0, 2));
        if (app.get('env') === 'development' && err.stack) {
            // comment next line for skip logging error stack
            locals.ERR.stack = err.stack;
            if ((!(verb1 || verb2)) && locals.ERR.hasOwnProperty('stack')) {
                console.log(process.pid.toString(), '> SERVER ERROR STACK!');
                console.log(locals.ERR.stack);
            }
        }
        res.status(locals.ERR.status);
        res.json(locals.ERR);
    });
    //
    return app;
};

module.exports = async function () {
    return http();
};