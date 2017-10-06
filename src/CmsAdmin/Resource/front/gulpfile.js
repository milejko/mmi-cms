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
    base:'./../base/**/*',
    front: [
        './css/**/*',
        './fonts/**/*',
        './images/**/*',
        './js/**/*',
        './node_modules/bootstrap/dist/js/bootstrap.min.js',
        './node_modules/bootstrap-daterangepicker/daterangepicker.js',
        './node_modules/chart.js/dist/Chart.min.js',
        './node_modules/datatables.net/js/jquery.dataTables.js',
        './node_modules/datatables.net-bs/js/dataTables.bootstrap.js',
        './node_modules/fullcalendar/dist/fullcalendar.min.js',
        './node_modules/fullcalendar/dist/gcal.min.js',
        './node_modules/gaugeJS/dist/gauge.min.js',
        './node_modules/ion-rangeslider/js/ion.rangeSlider.min.js',
        './node_modules/jquery/dist/jquery.min.js',
        './node_modules/jquery/dist/jquery.min.map',
        './node_modules/jquery-ui-dist/jquery-ui.min.js',
        './node_modules/jquery-validation/dist/jquery.validate.min.js',
        './node_modules/jquery.maskedinput/src/jquery.maskedinput.js',
        './node_modules/ladda/dist/ladda.min.js',
        './node_modules/ladda/dist/spin.min.js',
        './node_modules/moment/min/moment.min.js',
        './node_modules/quill/dist/quill.min.js',
        './node_modules/quill/dist/quill.min.js.map',
        './node_modules/pace-progress/pace.min.js',
        './node_modules/popper.js/dist/umd/popper.min.js',
        './node_modules/popper.js/dist/umd/popper.min.js.map',
        './node_modules/select2/dist/js/select2.min.js',
        './node_modules/toastr/toastr.js',
        './node_modules/font-awesome/css/font-awesome.min.css',
        './node_modules/font-awesome/css/font-awesome.css.map',
        './node_modules/simple-line-icons/css/simple-line-icons.css',
        './node_modules/font-awesome/fonts/**',
        './node_modules/simple-line-icons/fonts/**'
    ],
    output:'./../web/'
};

gulp.task('clean', function(){
    return gulp.src(project_path.output, {read: false})
        .pipe(clean({force: true}));
});

gulp.task('old', ['clean'], function(){
    return gulp.src(project_path.base, {base: './../base/'})
        .pipe(gulp.dest(project_path.output));
});

gulp.task('new', function(){
    return gulp.src(project_path.front, {base: './'})
        .pipe(gulp.dest(project_path.output));
});

gulp.task('serve:new', ['new' , 'sass'], function() {
    
    browserSync.init({
        proxy: 'http://localhost/cmsAdmin',
    });

  gulp.watch('scss/**/*.scss', ['sass', 'build:dist', 'new' ], browserSync.reload);
  gulp.watch('js/**/*.js', ['sass', 'build:dist', 'new' ], browserSync.reload);

});

gulp.task('serve:old', ['old' , 'sass'], function() {

    browserSync.init({
        proxy: 'http://localhost/cmsAdmin',
    });

    gulp.watch('scss/**/*.scss', ['sass', 'build:dist', 'new' ], browserSync.reload);
    gulp.watch('js/**/*.js', ['sass', 'build:dist', 'new' ], browserSync.reload);

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
