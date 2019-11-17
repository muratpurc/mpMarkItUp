<?php
/**
 * CMS_MARKUPTEXTILE
 */

cInclude("classes", "class.htmlelements.php");
cInclude("includes", "functions.lang.php");

$content = $a_content['CMS_MARKUPTEXTILE'][$val];
$content = urldecode($content);
$content = htmldecode($content);
$content = str_replace("&nbsp;", " ", $content);
if ($content == "") {
  $content = "&nbsp;";
}


if ($edit) {

    $div = new cHTMLDiv;
    $div->setID(implode('_', array('MARKUPTEXTILE', $db->f('idtype'), $val)));
    $div->setEvent('focus', 'this.style.border=\'1px solid #bb5577\'');
    $div->setEvent('blur', 'this.style.border=\'1px dashed #bfbfbf\'');
    $div->setStyleDefinition('border', '1px dashed #bfbfbf');
    $div->updateAttributes(array('contentEditableMarkup' => 'true'));
    $div->setStyleDefinition('direction', langGetTextDirection($lang));

    $editlink = new cHTMLLink();
    $editlink->setLink($sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_MARKUPTEXTILE&typenr=$val&lang=$lang"));

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


/**
 * CMS_MARKUPTEXTILE (CMS_HTML)
 */
/*
$tmp = $a_content['CMS_MARKUPTEXTILE'][$val];
$tmp = urldecode($tmp);

$tmp = addslashes(addslashes($tmp));
$tmp = str_replace("\\\'","'",$tmp);
$tmp = str_replace("\$",'\\\$',$tmp);

cInclude("includes", "functions.lang.php");
cInclude("classes", "class.htmlelements.php");

if ($edit) {
    if ($tmp == '') {
        $tmp = "&nbsp;";
    } 
    $insiteEditingDIV = new cHTMLDiv;
    $insiteEditingDIV->setId("HTML_".$db->f("idtype")."_".$val);
    $insiteEditingDIV->setEvent("Focus", "this.style.border='1px solid #bb5577';");
    $insiteEditingDIV->setEvent("Blur", "this.style.border='1px dashed #bfbfbf';");
    $insiteEditingDIV->setStyleDefinition("border", "1px dashed #bfbfbf");
    $insiteEditingDIV->setStyleDefinition("direction", langGetTextDirection($lang));

    $insiteEditingDIV->updateAttributes(array("contentEditable" => "true"));

    $insiteEditingDIV->setContent("_REPLACEMENT_");


    // Edit anchor and image
    $editLink = $sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_MARKUPTEXTILE&typenr=$val");
    $editAnchor = new cHTMLLink;
    $editAnchor->setLink("javascript:setcontent('$idartlang','" . $editLink . "');");
    
    $editButton = new cHTMLImage;
    $editButton->setSrc($cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_edithtml.gif");
    $editButton->setBorder(0);
    $editButton->setStyleDefinition("margin-right", "2px");
        
    $editAnchor->setContent($editButton);
    
    
    // Save anchor and image
    $saveAnchor = new cHTMLLink;
    $saveAnchor->setLink("javascript:setcontent('$idartlang','0')");
    
    $saveButton = new cHTMLImage;
    $saveButton->setSrc($cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_ok.gif");
    $saveButton->setBorder(0);
    
    $saveAnchor->setContent($saveButton);

    // Process for output with echo
    $finalEditButton = $editAnchor->render();
    $finalEditButton = addslashes(addslashes($finalEditButton));
    $finalEditButton = str_replace("\\\'","'",$finalEditButton);
    
    $finalEditingDiv = $insiteEditingDIV->render();
    $finalEditingDiv = addslashes(addslashes($finalEditingDiv));
    $finalEditingDiv = str_replace("\\\'","'",$finalEditingDiv);
    
    $finalEditingDiv = str_replace("_REPLACEMENT_", $tmp, $finalEditingDiv);
    
    $finalSaveButton = $saveAnchor->render();
    $finalSaveButton = addslashes(addslashes($finalSaveButton));
    $finalSaveButton = str_replace("\\\'","'",$finalSaveButton);
    
    $tmp =  $finalEditingDiv . $finalEditButton . $finalSaveButton;
}
*/
