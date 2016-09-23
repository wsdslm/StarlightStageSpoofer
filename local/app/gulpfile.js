var gulp = require('gulp');
var gutil = require('gulp-util');
var browserSync = require('browser-sync');
var webpack = require('webpack');
var _ = require('lodash');

var config = {
    development: function(opts) {
        return _.assign(require('./webpack.development.config'), opts || {});
    },
    production: function(opts) {
        return _.assign(require('./webpack.production.config'), opts || {});
    }
};

gulp.task('default', ['build:production']);

gulp.task('serve', ['build:watch'], function() {
    browserSync.init({
        proxy: "http://spoofer.dev",
        port: 3000
    });
});

gulp.task('build:watch', function(cb) {
    return webpack(config.development({ watch: true }), webpackCallback(cb));
});

gulp.task('build:development', function(cb) {
    return webpack(config.development(), webpackCallback(cb));
});

gulp.task('build:production', function(cb) {
    return webpack(config.production(), webpackCallback(cb));
});

function webpackCallback(err, stats) {
    if (_.isFunction(err)) {
        var cb = err;
        var done = false;
        return function(err, stats) {
            if (webpackCallback(err, stats) && !done) {
                cb();
                done = true;
            }
        }
    }

    if (err) {
        throw new gutil.PluginError(err);
        return false;
    }

    gutil.log("[webpack]", stats.toString());
    browserSync.reload();
    return true;
}
