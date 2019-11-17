<?php
/**
 * CMS_MARKUPMARKDOWN
 */

cInclude("classes", "class.htmlelements.php");
cInclude("includes", "functions.lang.php");

$content = $a_content['CMS_MARKUPMARKDOWN'][$val];
$content = urldecode($content);
$content = htmldecode($content);
$content = str_replace("&nbsp;", " ", $content);
if ($content == "") {
  $content = "&nbsp;";
}


if ($edit) {

    $div = new cHTMLDiv;
    $div->setID(implode('_', array('MARKUPMAKRDOWN', $db->f('idtype'), $val)));
    $div->setEvent('focus', 'this.style.border=\'1px solid #bb5577\'');
    $div->setEvent('blur', 'this.style.border=\'1px dashed #bfbfbf\'');
    $div->setStyleDefinition('border', '1px dashed #bfbfbf');
    $div->updateAttributes(array('contentEditableMarkup' => 'true'));
    $div->setStyleDefinition('direction', langGetTextDirection($lang));

    $editlink = new cHTMLLink();
    $editlink->setLink($sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_MARKUPMARKDOWN&typenr=$val&lang=$lang"));

    $editimg = new cHTMLImage();
    $editimg->setSrc($cfg['path']['contenido_fullhtml'] . $cfg['path']['images'] . 'but_edittext.gif');

    $savelink = new cHTMLLink();
    $savelink->setLink("javascript:setcontent('$idartlang','0')");

    $saveimg = new cHTMLImage();
    $saveimg->setSrc($cfg['path']['contenido_fullhtml'] . $cfg['path']['images'] . 'but_ok.gif');

    $savelink->setContent($saveimg);

    $editlink->setContent($editimg);

    $div->setContent($content);

    $tmp = implode('', array($div->render(), $editlink->render(), ' ', $savelink->render()));
    $tmp = str_replace('"', '\"', $tmp);

} else {

  $tmp = $content;
  $tmp = str_replace('"', '\"', $tmp);

}

$tmp = addslashes($tmp);
$tmp = str_replace('$', '\\\$', $tmp);

