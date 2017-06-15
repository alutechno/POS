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
    session: {
        maxAge: parseInt(ENV.MAX_AGE) || 15 * 60 * 1000, //default to 15 minutes,
        resave: true,
    },
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
    }
};