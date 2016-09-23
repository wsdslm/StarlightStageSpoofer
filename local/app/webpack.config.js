if (process.env.NODE_ENV === "production") {
    module.exports = require('./webpack.production.config');
} else {
    module.exports = require('./webpack.development.config');
}
