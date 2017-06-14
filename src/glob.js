const domain = 'localhost';
const fs = require('fs');
const ENV = process.env;
const env = ENV.NODE_ENV || 'development';
const home = `${process.env.PWD || __dirname}`;
const port = ENV.PORT || 8000;
const mysql = require(`${home}/config/mysql`);
const getIp = function () {
    try {
        return require(`${home}/utils/getip`)();
    } catch (e) {
        return domain;
    }
};
module.exports = {
    env: env,
    token: {
        header: 'X-Token',
        expires: parseInt(ENV.TOKEN_EXPIRES) || 15 * 60 * 1000, //default to 15 minutes
        extend: ENV.TOKEN_EXTEND || true,
        regen: ENV.TOKEN_REGEN || false,
        resave: ENV.TOKEN_RESAVE || true //if false regen value will force to true
    },
    regenToken: true,
    reqTimeOut: parseInt(eval(ENV.npm_package_reqTimeOut)) || 1 * 60 * 1000,
    name: ENV.NAME || JSON.parse(fs.readFileSync(`${home}/package.json`)).name,
    description: ENV.npm_package_description || JSON.parse(fs.readFileSync(`${home}/package.json`)).description,
    version: ENV.npm_package_version || JSON.parse(fs.readFileSync(`${home}/package.json`)).version,
    ip: getIp(),
    port: port,
    home: home,
    factory: 7,
    config: {mysql},
    upload: {
        uploadDir: `${home}/uploads`,
        maxFilesSize: 5000000,
        fieldName: 'uploads',
    },
    maxPaging: 10000,
    regEx: {
        //email : /[a-z0-9]+[_a-z0-9\.-]*[a-z0-9]+@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})/i,
        email: /^(?:(?:[\w`~!#$%^&*\-=+;:{}'|,?\/]+(?:(?:\.(?:"(?:\\?[\w`~!#$%^&*\-=+;:{}'|,?\/\.()<>\[\] @]|\\"|\\\\)*"|[\w`~!#$%^&*\-=+;:{}'|,?\/]+))*\.[\w`~!#$%^&*\-=+;:{}'|,?\/]+)?)|(?:"(?:\\?[\w`~!#$%^&*\-=+;:{}'|,?\/\.()<>\[\] @]|\\"|\\\\)+"))@(?:[a-zA-Z\d\-]+(?:\.[a-zA-Z\d\-]+)*|\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\])$/i,
        phone: /^\s*(?:\+?(\d{1,3}))?([-. (]*(\d{3})[-. )]*)?((\d{3})[-. ]*(\d{2,4})(?:[-.x ]*(\d+))?)\s*$/i,
        username: /^[a-zA-Z0-9]*$/i,
        password: /[a-zA-Z0-9\_\~\!\@\#\$\%\^\&\*\(\)\_\+\`\-\=\[\]\\\{\}\|\;\'\:\"\,\.\/\<\>\?]+/i,
        zipcode: /(^\d{5}([\-]?\d{4})?$)|(^(GIR|[A-Z]\d[A-Z\d]??|[A-Z]{2}\d[A-Z\d]??)[ ]??(\d[A-Z]{2})$)|(\b((?:0[1-46-9]\d{3})|(?:[1-357-9]\d{4})|(?:[4][0-24-9]\d{3})|(?:[6][013-9]\d{3}))\b)|(^([ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ])\ {0,1}(\d[ABCEGHJKLMNPRSTVWXYZ]\d)$)|(^(F-)?((2[A|B])|[0-9]{2})[0-9]{3}$)|(^(V-|I-)?[0-9]{5}$)|(^(0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2})$)|(^[1-9][0-9]{3}\s?([a-zA-Z]{2})?$)|(^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$)|(^([D-d][K-k])?( |-)?[1-9]{1}[0-9]{3}$)|(^(s-|S-){0,1}[0-9]{3}\s?[0-9]{2}$)|(^[1-9]{1}[0-9]{3}$)|(^\d{6}$)/i
    }
};