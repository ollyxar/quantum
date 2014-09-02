/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : 'en';
}

CKEDITOR.editorConfig = function (config) {
    config.language = getCookie('lang');
    config.toolbar = 'Full';
    config.height = 500;
    config.allowedContent = true;
    config.skin = 'moono';
    config.extraPlugins = 'imagebrowser,codemirror';
    config.filebrowserBrowseUrl = '/admin/template/js/qfinder/qfinder.php?type=Files';
    config.filebrowserImageBrowseUrl = '/admin/template/js/qfinder/qfinder.php?type=Images';
    config.filebrowserFlashBrowseUrl = '/admin/template/js/qfinder/qfinder.php?type=Flash';
    config.filebrowserUploadUrl = '/admin/template/js/qfinder/qfinder.php?type=Files';
    config.filebrowserImageUploadUrl = '/admin/template/js/qfinder/qfinder.php?type=Images';
    config.filebrowserFlashUploadUrl = '/admin/template/js/qfinder/qfinder.php?type=Flash';

};