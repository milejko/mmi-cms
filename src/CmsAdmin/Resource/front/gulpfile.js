'use strict'

var gulp = require('gulp');
var browserSync = require('browser-sync').create();
var sass = require('gulp-sass');
var cssmin = require('gulp-cssmin');
var rename = require('gulp-rename');
var runSequence = require('run-sequence');
var clean = require('gulp-clean');

require('require-dir')('./gulp-tasks');

gulp.paths = {
    dist: 'dist',
    vendors: 'dist/vendors'
};

var paths = gulp.paths;

var project_path = {
    base: './../base/**/*',
    front: './dist/**/*',
    output: './../web/'
};

gulp.task('clean_assets', function () {
    return gulp.src(project_path.output, {read: false})
        .pipe(clean({force: true}));
});

gulp.task('old_assets', ['clean_assets'], function () {
    return gulp.src(project_path.base, {base: './../base/'})
        .pipe(gulp.dest(project_path.output));
});
gulp.task('copy_jstree', function () {
    return gulp.src('./js/**/*', {base: './'})
        .pipe(gulp.dest(project_path.output));
});

gulp.task('new_assets', ['clean_assets'], function () {
    return gulp.src(project_path.front, {base: './dist'})
        .pipe(gulp.dest(project_path.output));
});


gulp.task('serve:new', function () {
    return new Promise(function (suc, e) {
        runSequence('sass','copy_jstree', 'build:dist', 'new_assets', suc)
    }).then(function () {
        browserSync.init({
            proxy: 'http://localhost/cmsAdmin'
        });
        gulp.watch('./scss/**/*.scss', ['sass', 'new_assets'], browserSync.reload);
    })
});

gulp.task('sass', function () {
    return gulp.src('./scss/style.scss')
        .pipe(sass())
        .pipe(gulp.dest('./css'))
        .pipe(browserSync.stream());
});

gulp.task('sass:watch', function () {
    gulp.watch('./scss/**/*.scss');
});

gulp.task('default', ['serve']);
