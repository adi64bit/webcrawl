const elixir = require('laravel-elixir');

elixir.config.css.sass.pluginOptions.includePaths = ['node_modules/toastr/build'];
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

elixir((mix) => {
    mix.sass('vendor.scss', 'public/css/vendor.css')
        .sass('app.scss')
        .webpack('app.js')
        .scripts([
            '../vendor/jquery/jquery-3.3.1.min.js',
            '../vendor/bootstrap/js/bootstrap.bundle.min.js',
            '../vendor/metisMenu/metisMenu.js',
            '../vendor/jquery-slimscroll/jquery.slimscroll.min.js',
            '../vendor/bootstrap-progressbar/js/bootstrap-progressbar.min.js',
            '../vendor/jquery-sparkline/js/jquery.sparkline.min.js',
            '../vendor/particlesjs/particles.min.js',
        ], 'public/js/vendor.js');
    mix.version(['css/app.css', 'css/vendor.css', 'js/app.js', 'js/vendor.js']);
});