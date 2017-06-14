const uuid = require('node-uuid');
//
module.exports = function (Glob, locals, Mongoose) {
    let {session, org, user} = Mongoose.models;
    return async function (req, res, next) {
        let {header, expires, extend, regen, resave} = Glob.token;
        let token = req.headers[header.toLowerCase()];
        let method = req.method;
        let path = req._parsedUrl.pathname;

        if (!token) return next();
        let query = {token, active: true};
        try {
            let session_ = await session.findOne(query).lean();
            if (!session_) throw (new Error(`Invalid lookup for token ${token}`));

            let expiredAt = new Date(session_.expires).getTime(),
                currentTime = new Date().getTime();

            if (currentTime > expiredAt) throw (new Error(`Invalid session expires for token's ${token}`));

            let user_ = await user.findOne({_id: session_.user._id, active: true}).lean();
            if (!user_) throw (new Error(`Invalid lookup user for token's ${token}`));

            let org_ = await org.findOne({_id: user_.org._id, active: true}).lean();
            if (!org_) throw (new Error(`Invalid lookup user organization for token ${token}`));

            if (resave) {
                let $set = {};

                $set.count = session_.count + 1;
                if (extend) $set.expires = new Date(new Date().getTime() + expires);
                if (regen) $set.token = uuid.v4();

                session_ = await session.findOneAndUpdate(query, {$set}, {new: true}).lean();
            } else {
                session_ = new session({
                    token: uuid.v4(),
                    expires: new Date(new Date().getTime() + expires),
                    'user._id': user_._id,
                    'org._id': org_._id
                });
                await session_.save();
            }

            req.logged = {
                user: user_,
                session: {
                    _id: session_._id,
                    token: session_.token,
                    expires: session_.expires,
                    count: session_.count,
                    createdAt: session_.createdAt,
                    active: session_.active,
                    notes: session_.notes
                }
            };
            next();
        } catch (e) {
            await session.findOneAndUpdate(query, {$set:{active: false}}).lean();
            next(e)
        }
    };
};