const { mix } = require("laravel-mix");
require("laravel-mix-merge-manifest");

// var publicPath = 'publishable/assets';
var publicPath = "../../../public/vendor/webkul/customer/assets";

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();
mix.js(
    [
        __dirname + "/src/Resources/assets/js/app.js"
        // __dirname + "/src/Resources/assets/js/dropdown.js"
    ],
    "js/ui.js"
)
    // .copy(__dirname + "/src/Resources/assets/images", publicPath + "/images")
    .sass(__dirname + "/src/Resources/assets/sass/app.scss", "css/customer.css")
    .options({
        processCssUrls: false
    });

if (mix.inProduction()) {
    mix.version();
}
