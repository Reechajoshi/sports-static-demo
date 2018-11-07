/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	//config.toolbar = 'Actual';
	config.enterMode = CKEDITOR.ENTER_BR;
	config.removeFormatTags = config.removeFormatTags + ',div,p';
	
	config.toolbar_BasicToolbar =
	[
	//	['Source','-','Save','NewPage','Preview'], //,'-','Templates'],
	//    ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
	//    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	    '/',
	    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
	    //['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
	    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	    //['Link','Unlink','Anchor'],
	    //['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		['Smiley','SpecialChar'],
	    '/',
	    ['Styles','Format','Font','FontSize'],
	    ['TextColor','BGColor'],
	    ['Maximize']//, 'ShowBlocks']
	];
};
