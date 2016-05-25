var elixir 	= require('laravel-elixir');
var gulp 	= require('gulp');
var del 	= require('del');
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
elixir.config.sourcemaps = false;

var task = elixir.Task;

elixir.extend("remove", function(path) {
    new task("remove",function(){
        del(path);
    });
});

elixir(function(mix) {
    
    // Convert SASS Script to CSS Script
    mix.sass('app.sass', 'public/build/app/css/app.css');

    // Merge Script
    mix.scripts('*.js', 'public/build/app/js/app.js');

    // Copy Image
    mix.copy('resources/assets/images', 'public/build/app/images');

    // Copy jQuery Library File
    mix.copy('node_modules/jquery/dist/jquery.min.js', 'public/build/library/jquery/jquery.min.js');

    // Copy Bootstrap Library File
    mix.copy('node_modules/bootstrap-sass/assets/fonts/bootstrap', 'public/build/library/bootstrap/fonts');
    mix.copy('node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js', 'public/build/library/bootstrap/js/bootstrap.min.js');

    // Copy Font-Awesome Library File
    mix.copy('node_modules/font-awesome/css/font-awesome.min.css', 'public/build/library/font-awesome/css/font-awesome.min.css');
    mix.copy('node_modules/font-awesome/fonts', 'public/build/library/font-awesome/fonts');
    
    // Copy Jquery Scrollbar Library File
    mix.copy('node_modules/jquery.scrollbar/jquery.scrollbar.min.js', 'public/build/library/jquery-scrollbar/jquery.scrollbar.min.js');
    mix.copy('node_modules/jquery.scrollbar/jquery.scrollbar.css', 'public/build/library/jquery-scrollbar/jquery.scrollbar.css');
});
