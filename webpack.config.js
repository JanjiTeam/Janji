const Encore = require('@symfony/webpack-encore');
const StylelintPlugin = require('stylelint-webpack-plugin');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/js/app.js')
    .addEntry('calendar', './assets/js/calendar.js')
    .addEntry('appointment', './assets/js/appointment.js')
    .addEntry('register', './assets/js/register.js')

    .copyFiles({
        from: './assets/images',
    })

    .splitEntryChunks()

    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    /* eslint-disable no-param-reassign */
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
    /* eslint-enable no-param-reassign */

    .enablePostCssLoader()
    .enableEslintLoader()
    .addPlugin(new StylelintPlugin({
        configFile: '.stylelintrc.json',
        context: 'assets/css',
        files: '**/*.css',
        failOnError: Encore.isProduction(),
    }));

module.exports = Encore.getWebpackConfig();
