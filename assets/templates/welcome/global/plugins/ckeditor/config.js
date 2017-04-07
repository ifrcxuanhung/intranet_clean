/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    config.baseFloatZIndex = 102000;
    config.filebrowserBrowseUrl = $base_url+'assets/bundles/kcfinder/browse.php?type=images';
    config.filebrowserImageBrowseUrl = $base_url+'assets/bundles/kcfinder/browse.php?type=images';
    config.filebrowserFlashBrowseUrl = $base_url+'assets/bundles/kcfinder/browse.php?type=images';
    config.filebrowserUploadUrl = $base_url+'assets/bundles/kcfinder/upload.php?type=images';
    config.filebrowserImageUploadUrl = $base_url+'assets/bundles/kcfinder/upload.php?type=images';
    config.filebrowserFlashUploadUrl = $base_url+'assets/bundles/kcfinder/upload.php?type=images';
};
