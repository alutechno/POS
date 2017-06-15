const fs = require('fs'),
    path = require('path'),
    ejs = require('ejs'),
    Promise = require('bluebird'),
    express = require('express'),
    cookieParser = require('cookie-parser'),
    bodyParser = require('body-parser'),
    cors = require('cors'),
    mysql = require('promise-mysql');
//
const {STATUS_CODES} = require('http'),
    Glob = require(`./glob`),
    httpCode = require(`${Glob.home}/utils/http.code`),
    Code4 = require(`${Glob.home}/utils/code4`),
    Crypt = require(`${Glob.home}/utils/crypt`),
    Upload = require(`${Glob.home}/utils/upload`)(Glob.upload);
//
const {env, name, description, version, session, port, home, config} = Glob;
const http = function (pool, compile) {
    let del = 'password,default_module,default_menu,status,created_date,modified_date,created_by,modified_by';
    let app = express();
    let locals = app.locals;
    let cookie = {
        path: '/',
        maxAge: session.maxAge,
        httpOnly: true
    };
    //
    locals.name = name;
    locals.version = version;
    locals.description = description;
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
    app.use(cookieParser())
    app.use(bodyParser.json());
    app.use(bodyParser.urlencoded({extended: true}));
    app.use(express.static(path.join(Glob.home, 'public')));
    app.use(cors(), function (req, res, next) {
        res.header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
        next();
    });
    if (app.get('env') === 'production') {
        //cookie.secure = true;
        app.set('trust proxy', 1);
    }
    app.use(async function (req, res, next) {
        let url = req._parsedUrl.pathname;
        let {httpVersion, method, headers, params, query, body} = req;
        let request = {httpVersion, url, method, headers, params, query, body};
        ['params', 'query', 'body'].forEach(function (s) {
            if (!Object.keys(request[s]).length) delete request[s];
        });
        //
        console.log(process.pid.toString(), '>', JSON.stringify(request));
        next();
    });
    app.use(async function (req, res, next) {
        let myCookie = req.cookies[name];
        let error = new Error('Invalid cookie!');
        let pathname = req._parsedUrl.pathname, method = req.method;
        let isLoginPage = pathname === '/login' && method === 'GET';
        let isAuthing = pathname === '/auth' && method === 'POST';
        //
        if (myCookie) {
            myCookie = Crypt.de(myCookie);
            try {
                let {id, time} = JSON.parse(myCookie);
                let getUserQuery = `SELECT * FROM user WHERE id = ${id}`;
                let getter = await compile(getUserQuery);

                time += cookie.maxAge;

                if (getter.constructor == Error) throw getter;
                if (time < new Date().getTime()) throw error;
                if (isLoginPage || isAuthing) return res.redirect('/');

                del.split(',').forEach(function (key) {
                    delete getter[0][key]
                });
                locals.user = getter[0];
                locals.message = '';
                return next()
            } catch (e) {
                if (isLoginPage || isAuthing) return next();
                return res.redirect('/login');
            }
        }
        if (isLoginPage || isAuthing) return next();
        return res.redirect('/login');
    });
    app.get('/', function (req, res, next) {
        res.render('home')
    });
    app.get('/order', function (req, res, next) {
        res.render('order')
    });
    app.get('/login', function (req, res, next) {
        locals.message = locals.message || "Please fill form input..";
        res.clearCookie(name);
        res.render('login');
    });
    app.get('/logout', async function (req, res, next) {
        res.clearCookie(name);
        res.redirect('/login')
    });
    app.post('/sql', async function (req, res, next) {
        let {query, params} = req.body;
        let data = [], error = null, result = await compile(query, params);
        if (result.constructor == Error) {
            error = result;
        } else {
            data = result
        }
        res.send({error, data});
    });
    app.post('/auth', async function (req, res, next) {
        let {body} = req, {username, password} = body;
        //
        try {
            let getUserQuery = `SELECT * FROM user WHERE name = '${username}'`;
            let getter = await compile(getUserQuery);

            if (getter.constructor == Error) throw getter;
            if (getter.length !== 1) throw new Error(`Invalid username "${username}"!`);
            if (getter[0].password !== password) throw new Error(`Invalid password "${password}" for ${username} account!`);

            let {id} = getter[0];
            let time = new Date().getTime();
            let token = Crypt.en(JSON.stringify({id, time}));
            let updateUserQuery = 'UPDATE user SET token = ? WHERE id = ?';
            let setter = await compile(updateUserQuery, [token, id]);

            if (setter.constructor == Error) throw setter;

            cookie.expire = time + cookie.maxAge;
            del.split(',').forEach(function (key) {
                delete getter[0][key]
            });
            locals.user = getter[0];
            locals.message = '';
            res.cookie(name, token, cookie);
            res.redirect('/');
        } catch (e) {
            locals.message = e.message;
            res.redirect('/login');
        }
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
        if (req.xhr || req.headers.accept.indexOf('json') > -1) {
            res.json(locals.ERR);
        } else {
            res.render('error')
        }
    });
    //
    return app;
};

module.exports = async function () {
    let pool = await mysql.createPool(config.mysql);
    let compile = async function (query, data) {
        try {
            let conn = await pool.getConnection();
            if (!conn) throw new Error(conn);

            let result = await conn.query(query, data);
            if (!result) throw result;

            pool.releaseConnection(conn);

            return result;
        } catch (e) {
            return e
        }
    };
    return http(pool, compile);
};