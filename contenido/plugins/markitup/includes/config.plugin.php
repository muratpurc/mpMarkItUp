<?php
/**
 * Plugin markItUp settings
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © Murat Purc 2008
 * @package     Contenido
 * @subpackage  markItUp
 */


defined('CON_FRAMEWORK') or die('Illegal call');


// the plugin is to be active only at the backend
if (isset($GLOBALS['contenido'])) {

    // we are in backend, process pluin initialization

    $mIU_pluginPath = $cfg['path']['contenido_fullhtml'] . $cfg['path']['plugins'] . 'markitup/';

    plugin_include('markitup', 'includes/functions.markitup.php');

    $cfg['markitup']['emoticon_path'] = $mIU_pluginPath . '/_common/images/emoticon/';


    // #############################################################################################
    // BBCODE SET CONFIGURATION

    $cfg['markitup']['sets']['bbcode']['js_code'] = <<<CODE
    markItUpBbcodeSettings = {
        previewParserPath:	'{PREVIEWPARSERPATH}',
        markupSet: [
            {name:'Bold', key:'B', openWith:'[b]', closeWith:'[/b]'},
            {name:'Italic', key:'I', openWith:'[i]', closeWith:'[/i]'},
            {name:'Underline', key:'U', openWith:'[u]', closeWith:'[/u]'},
            {separator:'---------------' },
            {name:'Picture', key:'P', replaceWith:'[img][![Url]!][/img]'},
            {name:'Link', key:'L', openWith:'[url=[![Url]!]]', closeWith:'[/url]', placeHolder:'Your text to link here...'},
            {separator:'---------------' },
            {name:'Size', key:'S', openWith:'[size=[![Text size]!]]', closeWith:'[/size]',
            dropMenu :[
                {name:'Big', openWith:'[size=200]', closeWith:'[/size]' },
                {name:'Normal', openWith:'[size=100]', closeWith:'[/size]' },
                {name:'Small', openWith:'[size=50]', closeWith:'[/size]' }
            ]},
            {separator:'---------------' },
            {name:'Bulleted list', openWith:'[list]\\n', closeWith:'\\\n[/list]'},
            {name:'Numeric list', openWith:'[list=[![Starting number]!]]\\n', closeWith:'\\n[/list]'}, 
            {name:'List item', openWith:'[*] '},
            {separator:'---------------' },
            {name:'Quotes', openWith:'[quote]', closeWith:'[/quote]'},
            {name:'Code', openWith:'[code]', closeWith:'[/code]'}, 
            {separator:'---------------' },
            {name:'Clean', className:"clean", replaceWith:function(markitup) { return markitup.selection.replace(/\[(.*?)\]/g, "") } },
            {name:'Preview', className:"preview", call:'preview' }
        ]
    }
        
    $('#bbcode').markItUp(markItUpBbcodeSettings);

    $('#emoticons a').click(function() {
        emoticon = $(this).attr("title");
        $.markItUp( { replaceWith:emoticon } );
        return false;
    });
CODE;

    $cfg['markitup']['sets']['bbcode']['pre_editor_area'] = <<<CODE
    <div id="emoticons">
        <p>
            <a href="#" title=":)"><img alt=":)" border="0" src="{$cfg['markitup']['emoticon_path']}/emoticon-happy.png" /></a>
            <a href="#" title=":("><img alt=":(" border="0" src="{$cfg['markitup']['emoticon_path']}/emoticon-unhappy.png" /></a>
            <a href="#" title=":o"><img alt=":o" border="0" src="{$cfg['markitup']['emoticon_path']}/emoticon-surprised.png" /></a>
            <a href="#" title=":p"><img alt=":p" border="0" src="{$cfg['markitup']['emoticon_path']}/emoticon-tongue.png" /></a>
            <a href="#" title=";)"><img alt=";)" border="0" src="{$cfg['markitup']['emoticon_path']}/emoticon-wink.png" /></a>
            <a href="#" title=":D"><img alt=":D" border="0" src="{$cfg['markitup']['emoticon_path']}/emoticon-smile.png" /></a>
        </p>
    </div>
CODE;

    $cfg['markitup']['sets']['bbcode']['post_editor_area'] = '';


/*
# @todo: find a dotclear parser

    // #############################################################################################
    // DOTCLEAR SET CONFIGURATION

    $cfg['markitup']['sets']['dotclear']['js_code'] = <<<CODE

    markItUpDotclearSettings = {
        previewParserPath:	'{PREVIEWPARSERPATH}',
        onShiftEnter:		{keepDefault:false, replaceWith:'%%%\\n'},
        onCtrlEnter:		{keepDefault:false, replaceWith:'\\n\\n'},
        markupSet: [
            {name:'Heading 1', key:'1', openWith:'!!!!!', placeHolder:'Your title here...' },
            {name:'Heading 2', key:'2', openWith:'!!!!', placeHolder:'Your title here...' },
            {name:'Heading 3', key:'3', openWith:'!!!', placeHolder:'Your title here...' },
            {name:'Heading 4', key:'4', openWith:'!!', placeHolder:'Your title here...' },
            {name:'Heading 5', key:'5', openWith:'!', placeHolder:'Your title here...' },
            {separator:'---------------' },
            {name:'Bold', key:'B', openWith:'__', closeWith:'__'}, 
            {name:'Italic', key:'I', openWith:"''", closeWith:"''"}, 
            {name:'Stroke through', key:'S', openWith:'--', closeWith:'--'}, 
            {separator:'---------------' },
            {name:'Bulleted list', openWith:'(!(* |!|*)!)'}, 
            {name:'Numeric list', openWith:'(!(# |!|#)!)'}, 
            {separator:'---------------' },
            {name:'Picture', key:"P", replaceWith:'(([![Url:!:http://]!]|[![Alternative text]!](!(|[![Position:!:L]!])!)))'}, 
            {name:'Link', key:"L", openWith:"[", closeWith:'|[![Url:!:http://]!]|[![Language:!:en]!]|[![Title]!]]', placeHolder:'Your text to link here...' }, 
            {separator:'---------------' },
            {name:'Quotes', openWith:'{{', closeWith:'}}'}, 
            {name:'Code', openWith:'@@', closeWith:'@@'} , 
            {separator:'---------------' },
            {name:'Preview', call:'preview', className:'preview'}
        ]
    }

    $('#dotclear').markItUp(markItUpDotclearSettings);
CODE;

    $cfg['markitup']['sets']['dotclear']['pre_editor_area'] = '';

    $cfg['markitup']['sets']['dotclear']['post_editor_area'] = '';
*/


    // #############################################################################################
    // MARKDOWN SET CONFIGURATION

    $cfg['markitup']['sets']['markdown']['js_code'] = <<<CODE

    markItUpMarkdownSettings = {
        previewParserPath:	'{PREVIEWPARSERPATH}',
        onShiftEnter:       {keepDefault:false, openWith:'\\n\\n'},
        markupSet: [
            {name:'First Level Heading', key:'1', placeHolder:'Your title here...', closeWith:function(markItUp) { return miu.markdownTitle(markItUp, '=') } },
            {name:'Second Level Heading', key:'2', placeHolder:'Your title here...', closeWith:function(markItUp) { return miu.markdownTitle(markItUp, '-') } },
            {name:'Heading 3', key:'3', openWith:'### ', placeHolder:'Your title here...' },
            {name:'Heading 4', key:'4', openWith:'#### ', placeHolder:'Your title here...' },
            {name:'Heading 5', key:'5', openWith:'##### ', placeHolder:'Your title here...' },
            {name:'Heading 6', key:'6', openWith:'###### ', placeHolder:'Your title here...' },
            {separator:'---------------' },		
            {name:'Bold', key:'B', openWith:'**', closeWith:'**'},
            {name:'Italic', key:'I', openWith:'_', closeWith:'_'},
            {separator:'---------------' },
            {name:'Bulleted List', openWith:'- ' },
            {name:'Numeric List', openWith:function(markItUp) {
                return markItUp.line+'. ';
            }},
            {separator:'---------------' },
            {name:'Picture', key:'P', replaceWith:'![[![Alternative text]!]]([![Url:!:http://]!] "[![Title]!]")'},
            {name:'Link', key:'L', openWith:'[', closeWith:']([![Url:!:http://]!] "[![Title]!]")', placeHolder:'Your text to link here...' },
            {separator:'---------------'},	
            {name:'Quotes', openWith:'> '},
            {name:'Code Block / Code', openWith:'(!(\\\t|!|`)!)', closeWith:'(!(`)!)'},
            {separator:'---------------'},
            {name:'Preview', call:'preview', className:"preview"}
        ]
    }

    // mIu nameSpace to avoid conflict.
    miu = {
        markdownTitle: function(markItUp, char) {
            heading = '';
            n = $.trim(markItUp.selection||markItUp.placeHolder).length;
            for(i = 0; i < n; i++) {
                heading += char;
            }
            return '\\n'+heading;
        }
    }

    $('#markdown').markItUp(markItUpMarkdownSettings);

CODE;

    $cfg['markitup']['sets']['markdown']['pre_editor_area'] = '';

    $cfg['markitup']['sets']['markdown']['post_editor_area'] = '';



    // #############################################################################################
    // TEXTILE SET CONFIGURATION

    $cfg['markitup']['sets']['textile']['js_code'] = <<<CODE

    markItUpTextileSettings = {
        previewParserPath:	'{PREVIEWPARSERPATH}',
        onShiftEnter:		{keepDefault:false, replaceWith:'\\n\\n'},
        markupSet: [
            {name:'Heading 1', key:'1', openWith:'h1(!(([![Class]!]))!). ', placeHolder:'Your title here...' },
            {name:'Heading 2', key:'2', openWith:'h2(!(([![Class]!]))!). ', placeHolder:'Your title here...' },
            {name:'Heading 3', key:'3', openWith:'h3(!(([![Class]!]))!). ', placeHolder:'Your title here...' },
            {name:'Heading 4', key:'4', openWith:'h4(!(([![Class]!]))!). ', placeHolder:'Your title here...' },
            {name:'Heading 5', key:'5', openWith:'h5(!(([![Class]!]))!). ', placeHolder:'Your title here...' },
            {name:'Heading 6', key:'6', openWith:'h6(!(([![Class]!]))!). ', placeHolder:'Your title here...' },
            {name:'Paragraph', key:'P', openWith:'p(!(([![Class]!]))!). '},
            {separator:'---------------' },
            {name:'Bold', key:'B', closeWith:'*', openWith:'*'},
            {name:'Italic', key:'I', closeWith:'_', openWith:'_'},
            {name:'Stroke through', key:'S', closeWith:'-', openWith:'-'},
            {separator:'---------------' },
            {name:'Bulleted list', openWith:'(!(* |!|*)!)'},
            {name:'Numeric list', openWith:'(!(# |!|#)!)'}, 
            {separator:'---------------' },
            {name:'Picture', replaceWith:'![![Source:!:http://]!]([![Alternative text]!])!'}, 
            {name:'Link', openWith:'"', closeWith:'([![Title]!])":[![Link:!:http://]!]', placeHolder:'Your text to link here...' },
            {separator:'---------------' },
            {name:'Quotes', openWith:'bq(!(([![Class]!])!)). '},
            {name:'Code', openWith:'@', closeWith:'@'},
            {separator:'---------------' },
            {name:'Preview', call:'preview', className:'preview'}
        ]
    }

    $('#textile').markItUp(markItUpTextileSettings);
CODE;

    $cfg['markitup']['sets']['textile']['pre_editor_area'] = '';

    $cfg['markitup']['sets']['textile']['post_editor_area'] = '';


    // #############################################################################################
    // TEXY SET CONFIGURATION

    $cfg['markitup']['sets']['texy']['js_code'] = <<<CODE

    markItUpTexySettings = {
        previewParserPath:	'{PREVIEWPARSERPATH}',
        onShiftEnter:       {keepDefault:false, replaceWith:'\\n\\n'},
        markupSet: [
            {name:'Heading 1', key:'1', closeWith:function(markItUp) { return miu.texyTitle(markItUp, '#') }, placeHolder:'Your title here...', className:'h1'},
            {name:'Heading 2', key:'2', closeWith:function(markItUp) { return miu.texyTitle(markItUp, '*') }, placeHolder:'Your title here...', className:'h2'},
            {name:'Heading 3', key:'3', closeWith:function(markItUp) { return miu.texyTitle(markItUp, '=') }, placeHolder:'Your title here...', className:'h3'},
            {name:'Heading 4', key:'4', closeWith:function(markItUp) { return miu.texyTitle(markItUp, '-') }, placeHolder:'Your title here...', className:'h4'},
            {separator:'---------------' },
            {name:'Bold', key:'B', closeWith:'**', openWith:'**', className:'bold', placeHolder:'Your text here...'}, 
            {name:'Italic', key:'I', closeWith:'*', openWith:'*', className:'italic', placeHolder:'Your text here...'}, 
            {separator:'---------------' },
            {name:'Bulleted list', openWith:'- ', className:'list-bullet'}, 
            {name:'Numeric list', openWith:function(markItUp) { return markItUp.line+'. '; }, className:'list-numeric'}, 
            {separator:'---------------' },
            {name:'Picture', openWith:'[* ', closeWith:' (!(.([![Alt text]!]))!) *]', placeHolder:'[![Url:!:http://]!]', className:'image'}, 
            {name:'Link', openWith:'"', closeWith:'":[![Url:!:http://]!]', placeHolder:'Your text to link...', className:'link' },
            {separator:'---------------' },
            {name:'Quotes', openWith:'> ', className:'quotes'},
            {name:'Code block/Code in-line', openWith:'(!(/---[![Language:!:html]!]\\n|!|`)!)', closeWith:'(!(\\n\\\\---\\n|!|`)!)', className:'code'},
            {name:'Texy off', closeWith:'\'\'', openWith:'\'\'', className:'off', placeHolder:'No texty! in here!'},
            {separator:'---------------' },
            {name:'Preview', call:'preview', className:'preview'}
        ]
    }

    miu = {
        texyTitle: function (markItUp, char) {
            heading = '';
            n = $.trim(markItUp.selection || markItUp.placeHolder).length;
            for(i = 0; i < n; i++)	{
                heading += char;
            }
            return '\\n'+heading;
        }
    }

    $('#texy').markItUp(markItUpTexySettings);
CODE;

    $cfg['markitup']['sets']['texy']['pre_editor_area'] = '';

    $cfg['markitup']['sets']['texy']['post_editor_area'] = '';



    // #############################################################################################
    // WIKI SET CONFIGURATION

    $cfg['markitup']['sets']['wiki']['js_code'] = <<<CODE

    markItUpWikiSettings = {
        previewParserPath:	'{PREVIEWPARSERPATH}',
        onShiftEnter:		{keepDefault:false, replaceWith:'\\n\\n'},
        markupSet: [
            {name:'Heading 1', key:'1', openWith:'== ', closeWith:' ==', placeHolder:'Your title here...' },
            {name:'Heading 2', key:'2', openWith:'=== ', closeWith:' ===', placeHolder:'Your title here...' },
            {name:'Heading 3', key:'3', openWith:'==== ', closeWith:' ====', placeHolder:'Your title here...' },
            {name:'Heading 4', key:'4', openWith:'===== ', closeWith:' =====', placeHolder:'Your title here...' },
            {name:'Heading 5', key:'5', openWith:'====== ', closeWith:' ======', placeHolder:'Your title here...' },
            {separator:'---------------' },
            {name:'Bold', key:'B', openWith:"'''", closeWith:"'''"}, 
            {name:'Italic', key:'I', openWith:"''", closeWith:"''"}, 
            {name:'Stroke through', key:'S', openWith:'<s>', closeWith:'</s>'}, 
            {separator:'---------------' },
            {name:'Bulleted list', openWith:'(!(* |!|*)!)'}, 
            {name:'Numeric list', openWith:'(!(# |!|#)!)'}, 
            {separator:'---------------' },
            {name:'Picture', key:"P", replaceWith:'[[Image:[![Url:!:http://]!]|[![name]!]]]'}, 
            {name:'Link', key:"L", openWith:"[[![Link]!] ", closeWith:']', placeHolder:'Your text to link here...' },
            {name:'Url', openWith:"[[![Url:!:http://]!] ", closeWith:']', placeHolder:'Your text to link here...' },
            {separator:'---------------' },
            {name:'Quotes', openWith:'(!(> |!|>)!)', placeHolder:''},
            {name:'Code', openWith:'(!(<source lang="[![Language:!:php]!]">|!|<pre>)!)', closeWith:'(!(</source>|!|</pre>)!)'}, 
            {separator:'---------------' },
            {name:'Preview', call:'preview', className:'preview'}
        ]
    }

    $('#wiki').markItUp(markItUpWikiSettings);
CODE;

    $cfg['markitup']['sets']['wiki']['pre_editor_area'] = '';

    $cfg['markitup']['sets']['wiki']['post_editor_area'] = '';



    unset($mIU_pluginPath);
}
