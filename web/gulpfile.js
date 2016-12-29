var gulp = require('gulp');
var less = require('gulp-less');
var cleanCSS = require('gulp-clean-css');
var rename = require("gulp-rename");
var uglify = require('gulp-uglify');
var concatCss = require('gulp-concat-css');
var concat = require('gulp-concat');

var aVendors = ['jquery','underscore','font-awesome','datatables','datatables-plugins','datatables-bootstrap'];

// Compile LESS files from /less into /css
gulp.task('less', function() {
    return gulp.src('less/bookme.less')
        .pipe(less())
        .pipe(gulp.dest('dist/css'));
       
});


// Minify compiled CSS
gulp.task('minify-css', ['less'], function() {
    return gulp.src(['dist/css/*.css','!dist/css/*.min.css'])
        .pipe(cleanCSS({ compatibility: 'ie8' }))
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest('dist/css'));
      
});

// Copy JS to dist
gulp.task('js', function() {
    return gulp.src(['js/*.js'])
        .pipe(gulp.dest('dist/js'));
        
})

// Minify JS
gulp.task('minify-js', ['js'], function() {
    return gulp.src(['js/*.js','!js/*.min.js'])
        .pipe(uglify())
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest('dist/js'));
        
});

// Copy vendor libraries from /bower_components into /vendor
gulp.task('vendor-css', function() {
    
    var aVendorPaths = [];
    
    aVendors.forEach(function(value,index,array){
        aVendorPaths.push('vendor/'+value+'/css/*.css');
    });
    
   return gulp.src(aVendorPaths)
    .pipe(concatCss("vendor.css"))
    //.pipe(cleanCSS({ compatibility: 'ie8' }))
    .pipe(gulp.dest('dist/vendor/css'));
});

// Copy vendor libraries from /bower_components into /vendor
gulp.task('vendor-js', function() {
    
    var aVendorPaths = [];
    
    aVendors.forEach(function(value,index,array){
        aVendorPaths.push('vendor/'+value+'/js/*.js');
    });
    
     gulp.src(aVendorPaths)
    .pipe(concat("vendor.js"))
    .pipe(gulp.dest('dist/vendor/js'));
});


gulp.task('vendor-images', function() {
    var aVendorPaths = [];
    
    aVendors.forEach(function(value,index,array){
        
         aVendorPaths.push('vendor/'+value+'/images/*'); 
        
    });
    
    return gulp.src(aVendorPaths)
            .pipe(rename({dirname: ''}))
            .pipe(gulp.dest('dist/vendor/images/'));
});

gulp.task('vendor-fonts', function() {
    var aVendorPaths = [];
    
    aVendors.forEach(function(value,index,array){
        
         aVendorPaths.push('vendor/'+value+'/fonts/*'); 
        
    });
    
    return gulp.src(aVendorPaths)
            .pipe(rename({dirname: ''}))
            .pipe(gulp.dest('dist/vendor/fonts/'));
});


gulp.task('copy-bolt-local', function() {
    return gulp.src('dist/**/*')
          .pipe(gulp.dest('/home/ubuntu/bolt/public/extensions/local/icomefromthenet/bookme/dist'));
});


// Run everything
gulp.task('default', ['vendor-css','vendor-images','vendor-fonts','vendor-js','minify-css', 'minify-js','copy-bolt-local']);


