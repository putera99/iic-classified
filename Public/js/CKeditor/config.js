/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';

    //字体.
    config.font_names = '宋体;楷体_GB2312;新宋体;黑体;隶书;幼圆;微软雅黑;Arial;Comic Sans MS;Courier New;Tahoma;Times New Roman;Verdana;' ;

    //皮肤
    config.skin = 'office2003';
    
    //宽度
//    config.width = '860px';
//    config.height = '350px';

    //ToolBar
    config.toolbar_Full =
    [
        ['Source','-','Preview','-','Templates'],
        ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
        ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
        '/',
        ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['Link','Unlink','Anchor'],
        ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
        '/',
        ['Styles','Format','Font','FontSize'],
        ['TextColor','BGColor'],
        ['Maximize', 'ShowBlocks','-','About']
    ];

    //ToolBar的所有配置
//    config.toolbar_Full =
//    [
//        ['Source','-','Save','NewPage','Preview','-','Templates'],
//        ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
//        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
//        ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
//        '/',
//        ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
//        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
//        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
//        ['Link','Unlink','Anchor'],
//        ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
//        '/',
//        ['Styles','Format','Font','FontSize'],
//        ['TextColor','BGColor'],
//        ['Maximize', 'ShowBlocks','-','About']
//    ];
        
};
