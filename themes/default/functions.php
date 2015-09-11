<?php
function theme_img($imageFile) {
    return $GLOBALS['CONFIG']['themes_fromWebroot'].'/'.$GLOBALS['CONFIG']['themes_img'].'/'.$imageFile;
}
function theme_image($imageFile) { //Alias to theme_img
    return theme_img($imageFile);
}

function theme_js($jsFile) {
    return $GLOBALS['CONFIG']['themes_fromWebroot'].'/'.$GLOBALS['CONFIG']['themes_js'].'/'.$jsFile;
}
function theme_javascript($jsFile){ //Alias to theme_js
    return theme_js($jfFile);
}

function theme_css($cssFile) {
    return $GLOBALS['CONFIG']['themes_fromWebroot'].'/'.$GLOBALS['CONFIG']['themes_css'].'/'.$cssFile;
}
?>