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
        let now = new Date();
        let myCookie = req.cookies[name];
        let error = new Error('Invalid cookie!');
        let pathname = req._parsedUrl.pathname, method = req.method;
        let isLoginPage = pathname === '/login' && method === 'GET';
        let isAuthing = pathname === '/auth' && method === 'POST';
        //
        locals.time = now;
        delete locals.ERR;
        if (myCookie) {
            try {
                myCookie = Crypt.de(myCookie);
                let {id, time, outlet, posCashier} = JSON.parse(myCookie);

                /* get user info */
                let getUserQuery = `SELECT * FROM user WHERE id = ${id}`;
                let getUser = await compile(getUserQuery);
                if (getUser.constructor === Error) throw getUser;
                if (getUser.length !== 1) throw new Error(`Session: Invalid username "${username}"!`);

                /* get user outlet */
                let getOutletQuery = 'SELECT * FROM mst_outlet WHERE status = 1 AND id = ?';
                let getOutlet = await compile(getOutletQuery, outlet);
                if (getOutlet.constructor === Error) throw getOutlet;
                if (getOutlet.length !== 1) throw new Error(`Session: Invalid outlet "${outlet}"!`);

                /* get existing working shift */
                let getExistShiftQuery = `
                    SELECT
                        a.id, a.code, a.user_id, a.working_shift_id,
                        a.outlet_id, a.start_time, a.end_time, a.begin_saldo,
                        a.closing_saldo, b.name shift_name, c.name outlet_name,
                        b.start_time should_start_time, b.end_time should_end_time
                    FROM pos_cashier_transaction a
                    LEFT JOIN ref_pos_working_shift b on a.working_shift_id = b.id
                    LEFT JOIN mst_outlet c on a.outlet_id = c.id
                    WHERE a.id=?
                `;
                let getExistShift = await compile(getExistShiftQuery, posCashier);
                if (getExistShift.constructor === Error) throw getExistShift;
                if (!getExistShift.length) throw new Error(`Session: Invalid working shift for "${username}"!`);

                let maxTime = time + cookie.maxAge;
                if (maxTime < now.getTime()) throw error;
                if (isLoginPage || isAuthing) return res.redirect('/');
                if (((now.getTime() - time) / 1000) > 10) {
                    /* update session if diff == 10 second */
                    let token = Crypt.en(JSON.stringify({id, time: now.getTime(), outlet, posCashier}));
                    let updateUserQuery = 'UPDATE user SET token = ? WHERE id = ?';
                    let updateUser = await compile(updateUserQuery, [token, id]);
                    if (updateUser.constructor === Error) throw updateUser;

                    getUser[0].token = token;
                    cookie.expire = maxTime;
                    res.cookie(name, token, cookie);
                }

                del.split(',').forEach(function (key) {
                    delete getUser[0][key]
                });

                locals.user = getUser[0];
                locals.outlet = getOutlet[0];
                locals.posCashier = getExistShift[0];
                locals.message = '';

                return next();
            } catch (e) {
                locals.message = e.message;
                if (isLoginPage || isAuthing) return next();
                return res.redirect('/login');
            }
        }
        if (isLoginPage || isAuthing) return next();
        return res.redirect('/login');
    });
    app.get('/', function (req, res, next) {
        locals.page = 'Table';
        res.render('table')
    });
    app.get('/order/:id', function (req, res, next) {
        locals.page = 'Order';
        res.render('order')
    });
    app.get('/cashier', function (req, res, next) {
        locals.page = 'Cashier';
        res.render('cashier')
    });
    app.get('/login', async function (req, res, next) {
        let outlets = await compile('SELECT * FROM mst_outlet WHERE status = 1 ORDER BY name');

        if (outlets.constructor == Error) throw outlets;

        delete locals.outlet;
        delete locals.user;
        delete locals.posCashier;

        locals.page = 'Login';
        locals.outlets = outlets;
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
        let {body} = req, {username, password, outlet} = body;
        //
        try {
            if (!username) throw new Error(`Empty username!`);
            if (!password) throw new Error(`Empty password!`);
            if (!outlet) throw new Error(`Empty outlet!`);

            /* get user info */
            let getUserQuery = `SELECT * FROM user WHERE name = '${username}'`;
            let getUser = await compile(getUserQuery);
            if (getUser.constructor === Error) throw getUser;
            if (getUser.length !== 1) throw new Error(`Invalid username "${username}"!`);
            if (getUser[0].status !== '1') throw new Error(`Inactive account "${username}"!`);
            if (getUser[0].password !== password) throw new Error(`Invalid password "${password}" for ${username} account!`);

            /* get user outlet */
            let getOutletQuery = 'SELECT * FROM mst_outlet WHERE status = 1 AND id = ? ORDER BY name';
            let getOutlet = await compile(getOutletQuery, outlet);
            if (getOutlet.constructor === Error) throw getOutlet;
            if (getOutlet.length !== 1) throw new Error(`Invalid outlet "${outlet}"!`);

            let {id} = getUser[0];

            /* get should working shift */
            let getShiftQuery = `
                SELECT @now := now();
                SELECT id, name, description, status, start_time, DATE(stime), end_time, DATE(etime)
                FROM (
                    SELECT 
                        TIMESTAMP(
                            date_add(
                                DATE(@now),
                                INTERVAL 
                                    CASE 
                                        WHEN start_time < end_time THEN 0
                                        WHEN start_time > end_time AND @now >= start_time THEN 0
                                        WHEN start_time > end_time THEN -1
                                    END
                                day
                            ), start_time
                        ) stime,
                        TIMESTAMP(
                            date_add(
                                DATE(@now), 
                                    INTERVAL 
                                    CASE 
                                        when start_time > end_time and @now >= start_time then 1
                                        else 0
                                    END
                                DAY
                            ), end_time
                        ) etime,
                        a.*
                        FROM ref_pos_working_shift a
                ) a
                WHERE @now BETWEEN stime AND etime;
            `;
            let getShift = await compile(getShiftQuery);
            if (getShift.constructor === Error) throw getOutlet;
            if (getShift[1].length !== 1)  throw new Error(`Duplicate time shift!`);

            /* get existing working shift */
            let getExistShiftQuery = `
                SELECT
                    a.id, a.code, a.user_id, a.working_shift_id,
                    a.outlet_id, a.start_time, a.end_time, a.begin_saldo,
                    a.closing_saldo, b.name shift_name, c.name outlet_name,
                    b.start_time should_start_time, b.end_time should_end_time
                FROM pos_cashier_transaction a
                LEFT JOIN ref_pos_working_shift b on a.working_shift_id = b.id
                LEFT JOIN mst_outlet c on a.outlet_id = c.id
                WHERE a.user_id=? and a.outlet_id=? and (a.end_time is null or a.end_time = 0)
            `;
            let getExistShift = await compile(getExistShiftQuery, [id, outlet]);
            if (getExistShift.constructor === Error) throw getExistShift;
            if (!getExistShift.length) {
                let now = await compile(`SELECT NOW() val;`);
                let code = await compile(`SELECT CONCAT('PCS/', DATE_FORMAT(CURRENT_DATE, '%Y%m%d')) id;`);
                //let code = await compile(`SELECT CONCAT('PCS/', curr_item_code('', DATE_FORMAT(CURRENT_DATE, '%Y%m%d'))) id;`);
                let insertionData = {
                    code : code[0].id,
                    user_id : id,
                    working_shift_id : getShift[1][0].id,
                    outlet_id : outlet,
                    start_time : now[0].val,
                    created_date : now[0].val,
                    begin_saldo : null,
                    closing_saldo : null,
                    created_by : id
                };
                let insertion = await compile(`INSERT INTO pos_cashier_transaction SET ?`, insertionData);
                if (insertion.constructor === Error) throw insertion;
            }
            getExistShift = await compile(getExistShiftQuery, [id, outlet]);
            if (getExistShift.constructor === Error) throw getExistShift;

            /* set user token */
            let time = new Date().getTime();
            let posCashier = getExistShift[0].id;
            let token = Crypt.en(JSON.stringify({id, time, outlet, posCashier}));
            let updateUserQuery = 'UPDATE user SET token = ? WHERE id = ?';
            let updateUser = await compile(updateUserQuery, [token, id]);
            if (updateUser.constructor === Error) throw updateUser;
            getUser[0].token = token;

            del.split(',').forEach(function (key) {
                delete getUser[0][key]
            });

            delete locals.outlets;
            delete locals.message;

            locals.user = getUser[0];
            locals.outlet = getOutlet[0];
            locals.posCashier = getExistShift[0];
            cookie.expire = time + cookie.maxAge;
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