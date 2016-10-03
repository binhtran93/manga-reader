var gulp = require('gulp'),
    sass = require('gulp-sass'),
    concat = require('gulp-concat'),
    watch = require('gulp-watch'),
    minifyCss = require('gulp-minify-css'),
    uglify = require('gulp-uglify'),
    autoprefixer = require('gulp-autoprefixer')
    order = require('gulp-order'),
    sourcemaps = require('gulp-sourcemaps'),
    webpack = require('gulp-webpack'),
    plumber = require('gulp-plumber');
    

var dest = {
    destJs: 'public/js',
    destCss: 'public/css'
};

var src = {
    srcSass: [
        'node_modules/bootstrap/dist/css/bootstrap.min.css',
        'node_modules/font-awesome/scss/font-awesome.scss',
        'resources/assets/css/sb-admin.css',
        'resources/assets/sass/**/*.scss'
    ],
    srcJs: [
        'angular/app/app.js',
        'resources/assets/**/*.js'
    ],
    srcLibJs: [
        'node_modules/angular/angular.min.js',
        'node_modules/jquery/dist/jquery.min.js',
        'node_modules/bootstrap/dist/js/bootstrap.min.js',
    ]
};


//gulp.task('webpack', function () {
//   return gulp.src('public/js/scripts.js') 
//       .pipe(webpack( require('./webpack.config.js') ))
//       .pipe(gulp.dest('public/'))
//});

// compile sass
gulp.task('sass', function () {
    gulp.src(src.srcSass)
        .pipe(plumber())
        .pipe(sass())
        .pipe(sourcemaps.init())
        .pipe(concat('app.min.css'))
        .pipe(minifyCss())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(dest.destCss))
});

// compile js
gulp.task('js', function () {
    gulp.src(src.srcJs)
        .pipe(plumber({
            errorHandler: function(err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(sourcemaps.init()) 
        .pipe(concat('app.min.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(dest.destJs))
});

// compile lib js
gulp.task('libJs', function () {
    gulp.src(src.srcLibJs)
        .pipe(plumber({
            errorHandler: function(err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(sourcemaps.init()) 
        .pipe(concat('vendor.min.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(dest.destJs))
});

gulp.task('fonts', function() {
  return gulp.src('node_modules/font-awesome/fonts/*')
    .pipe(gulp.dest('public/fonts'))
});

gulp.task('watch', function () {
    gulp.watch(src.srcSass, ['sass']);
});

gulp.task('default', ['watch', 'sass', 'fonts', 'js', 'libJs']);