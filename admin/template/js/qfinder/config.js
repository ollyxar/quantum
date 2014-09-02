function getLCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : 'en';
}

QFinder.customConfig = function (config) {
    config.uiColor = '#CCCCCC';
    config.removePlugins = 'basket';
    config.language = getLCookie('lang');
};
