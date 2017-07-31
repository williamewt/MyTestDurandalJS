var gulp = require('gulp');
// var sass = require('gulp-sass');
var rename = require('gulp-rename');
// var minify = require('gulp-minify');

// gulp.task('style', function() {
//     gulp.src('sass/style.scss')
//         .pipe(sass().on('error', sass.logError))
//         .pipe(gulp.dest('./css/'));
// });

gulp.task('config', function(){
  gulp.src("./config.exemple.php")
  .pipe(rename("./config.php"))
  .pipe(gulp.dest("./"));
});

// gulp.task('compress', function() {
//   gulp.src('./js/lib/*.js')
//     .pipe(minify({
//         ext:{
//             src: '.debug.js',
//             min:'.min.js'
//         },
//         exclude: ['tasks'],
//         ignoreFiles: ['.combo.js', '.min.js', '-min.js']
//     }))
//     .pipe(gulp.dest('./js/dist/'));
// });


gulp.task('default', ['config']);