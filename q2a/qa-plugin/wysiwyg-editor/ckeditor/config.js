/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {

	// The toolbar arrangement, two rows of buttons
	config.toolbar = [
		{ name: 'basic', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ] },
		{ name: 'color', items: [ 'TextColor', 'BGColor' ] },
		{ name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
		{ name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
		{ name: 'markdown', items: [ 'Markdown' ] },
		'/',
		{ name: 'font', items: [ 'Font', 'FontSize', 'Format' ] },
		{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'Blockquote' ] },
		/*{ name: 'links', items: [ 'Link', 'Unlink' ] },*/
		{ name: 'insert', items: [ 'Image', 'Table',     'HorizontalRule', 'SpecialChar', 'Smiley' ] },
		{ name: 'last', items: [ 'RemoveFormat', 'Maximize' ] },
	];

	// Set the most common block elements
	config.format_tags = 'p;h1;h2;h3;pre';
	config.entities = false;

	// Make dialogs simpler
	config.removeDialogTabs = 'image:advanced;link:advanced;table:advanced';

	// Use native spell checking (note: Ctrl+right-click is required for native context menu)
	config.disableNativeSpellChecker = false;

	// Extra plugins
	//config.extraPlugins = 'entities,bbcode';
	config.extraPlugins = 'markdown,uploadimage,imagebrowser';

	// Required for the images to be displayed as thumbnails
	config.disableObjectResizing = true;

	config.uploadimageConfig = false;

	config.uploadUrl = Routing.generate( 'dcr_ckeditor_upload');
	config.filebrowserUploadUrl = Routing.generate('dcr_ckeditor_upload' );
	config.imageBrowser_listUrl = Routing.generate( 'dcr_ckeditor_browse' );
};
