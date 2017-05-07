var page = require('webpage').create(),
    system = require('system'),
    address, destination;

if (system.args.length === 1) {
    phantom.exit();
}

address = system.args[1];
destination = system.args[2];
page.settings.clearMemoryCaches = true;
page.open(address, function () {
    page.render(destination);
    phantom.exit();
});