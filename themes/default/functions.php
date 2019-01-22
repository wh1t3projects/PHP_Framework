<?php
/*
Copyright 2014 - 2019 Gaël Stébenne (alias Wh1t3c0d3r)

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/

function theme_getCompatibleVersions() {
    return array('2.0');
}

function theme_getWebLocation() {
    static $cachedWebLocation;
    if (is_null ($cachedWebLocation)) {
        $cachedWebLocation = substr($GLOBALS['INFO']['web_location'], strlen($GLOBALS['CONFIG']['app_location']) - 1, strlen($GLOBALS['INFO']['web_location']));
    }
    return $cachedWebLocation;
    
}
function theme_isCurrentLocation($location) {
    if ($location == theme_getWebLocation()) {
        return true;
    } else {
        return false;
    }
}
function theme_img($imageFile) {
    return $GLOBALS['CONFIG']['app_location'] . $GLOBALS['CONFIG']['themes_fromWebroot'] . '/' . $GLOBALS['CONFIG']['themes_img'] . '/' . $imageFile;
}
function theme_js($jsFile) {
    return $GLOBALS['CONFIG']['app_location'] . $GLOBALS['CONFIG']['themes_fromWebroot'] . '/' . $GLOBALS['CONFIG']['themes_js'] . '/' . $jsFile;
}
function theme_css($cssFile) {
    return $GLOBALS['CONFIG']['app_location'] . $GLOBALS['CONFIG']['themes_fromWebroot'] . '/' . $GLOBALS['CONFIG']['themes_css'] . '/' . $cssFile;
}
function theme_button($buttonText, $buttonLink = '#', $elementID = null) {
    return '<a ' . (!is_null ($elementID) ? "id=\"$elementID\" name=\"$elementID\" " : '') . "class=\"btn btn-primary btn-lg\" href=\"$buttonLink\" role=\"button\">$buttonText</a>";
}
function theme_button_secondary($buttonText, $buttonLink = '#', $elementID = null) {
    return '<a ' . (!is_null ($elementID) ? "id=\"$elementID\" name=\"$elementID\" " : '') . "class=\"btn btn-secondary\" href=\"$buttonLink\" role=\"button\">$buttonText</a>";
}
function theme_navbar_item($text, $link = '#', $isActive = null, $elementID = null) {
    if (is_null ($isActive)) {
        if (theme_isCurrentLocation($link)) {
            $isActive = true;
        } else {
            $isActive = false;
        }
    }
    if (substr($link, 0, 1) == '/') {
        $link = $GLOBALS['CONFIG']['app_location'] . substr($link, 1, strlen($link) - 1);
    }
    
    return "<li class=\"nav-item" . ($isActive ? ' active"' : '"') . (!is_null ($elementID) ? " id=\"$elementID\"" : '') . ">
        <a class=\"nav-link\" href=\"$link\">$text" . ($isActive ? ' <span class="sr-only">(current)</span></a>' : '</a>') . "
      </li>\n";
}
function theme_navbar_dropdown($text, $itemsArray, $elementID = null) {
    echo '<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#"' . (!is_null($elementID) ? " id=\"$elementID\"" : '') . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $text . '</a>
        <div class="dropdown-menu"' . (!is_null($elementID) ? " aria-labelledby=\"$elementID\"" : '') .'">' . "\n";
    foreach ($itemsArray as $text => $link) {
        if (substr($link, 0, 1) == '/') {
            $link = $GLOBALS['CONFIG']['app_location'] . substr($link, 1, strlen($link) - 1);
        }
          echo "<a class=\"dropdown-item\" href=\"$link\">$text</a>\n";
    }
    echo '</div>
    </li>' . "\n";
}
?>