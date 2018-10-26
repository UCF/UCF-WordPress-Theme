# UCF WordPress Theme

A generic, responsive WordPress theme for UCF websites, built off of the [Athena Framework](https://ucf.github.io/Athena-Framework/). Suitable as a standalone theme or as a parent theme.


## Installation Requirements

This theme is developed and tested against WordPress 4.9.8+ and PHP 5.4.x+.

### Required Plugins
These plugins *must* be activated for the theme to function properly.
* [Advanced Custom Fields PRO](https://www.advancedcustomfields.com/pro/)

### Supported Plugins
The plugins listed below are extended upon in this theme--this may include custom layouts for feeds, style modifications, etc. These plugins are not technically required on sites running this theme, and shouldn't be activated on sites that don't require their features. A plugin does not have to be listed here to be compatible with this themes that don't require their features. A plugin does not have to be listed here to be compatible with this theme.
* [Athena Shortcodes](https://github.com/UCF/Athena-Shortcodes-Plugin)
* [Automatic Sections Menus Shortcode](https://github.com/UCF/Section-Menus-Shortcode)
* [UCF Academic Calendar Plugin](https://github.com/UCF/UCF-Academic-Calendar-Plugin)
* [UCF Alert Plugin](https://github.com/UCF/UCF-Alert-Plugin)
* [UCF Charts Plugin](https://github.com/UCF/UCF-Charts-Plugin)
* [UCF College Custom Taxonomy](https://github.com/UCF/UCF-Colleges-Tax-Plugin)
* [UCF Events](https://github.com/UCF/UCF-Events-Plugin)
* [UCF Footer](https://github.com/UCF/UCF-Footer-Plugin)
* [UCF News](https://github.com/UCF/UCF-News-Plugin)
* [UCF Pegasus List Shortcode](https://github.com/UCF/UCF-Pegasus-List-Shortcode)
* [UCF People Custom Post Type](https://github.com/UCF/UCF-People-CPT)
* [UCF Post List Shortcode](https://github.com/UCF/UCF-Post-List-Shortcode)
* [UCF Section](https://github.com/UCF/UCF-Section-Plugin)
* [UCF Social](https://github.com/UCF/UCF-Social-Plugin)
* [UCF Spotlights](https://github.com/UCF/UCF-Spotlights-Plugin)
* [UCF Weather Shortcode](https://github.com/UCF/UCF-Weather-Shortcode)
* [WP Shortcode Interface](https://github.com/UCF/WP-Shortcode-Interface)
* [Yoast SEO](https://wordpress.org/plugins/wordpress-seo/)


## Configuration

* Import field groups (`dev/acf-fields.json`) using the ACF importer under Custom Fields > Tools.
* Create and set a static front page under Settings > Reading.
* If your site requires primary navigation, create a menu and assign it to the Header Menu location.  If no Header Menu is set, ucf.edu's primary navigation will be used.
* If you plan on using [premium webfonts using Cloud.Typography](https://ucf.github.io/Athena-Framework/getting-started/install/#cloudtypography-premium-font-configuration), ensure that webfonts have been properly configured to a Cloud.Typography CSS Key that [allows access to your environment](https://dashboard.typography.com/user-guide/managing-domains). A Cloud.Typography CSS Key URL can be added via the WordPress Customizer (Appearance > Customize > Web Fonts).


## Development

Note that compiled, minified css and js files are included within the repo.  Changes to these files should be tracked via git (so that users installing the theme using traditional installation methods will have a working theme out-of-the-box.)

[Enabling debug mode](https://codex.wordpress.org/Debugging_in_WordPress) in your `wp-config.php` file is recommended during development to help catch warnings and bugs.

### Requirements
* node
* gulp-cli

### Instructions
1. Clone the UCF-WordPress-Theme repo into your development environment, within your WordPress installation's `themes/` directory: `git clone https://github.com/UCF/UCF-WordPress-Theme.git`
2. `cd` into the UCF-WordPress-Theme directory, and run `npm install` to install required packages for development into `node_modules/` within the repo
3. Copy `gulp-config.template.json`, make any desired changes, and save as `gulp-config.json`.
3. Run `gulp default` to process front-end assets.
4. If you haven't already done so, create a new WordPress site on your development environment, install the required plugins listed above, and set the UCF WordPress Theme as the active theme.
5. Make sure you've done all the steps listed under "Configuration" above.
6. Run `gulp watch` to continuously watch changes to scss and js files.  If you enabled BrowserSync in `gulp-config.json`, it will also reload your browser when scss or js files change.
