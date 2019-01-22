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
// The licence above is between PHP tags only to prevent it from being sent to the browser
?>
<html>
<head>
<title><?php echo $GLOBALS['THEME']['page_title'] ?></title>
    <link rel="stylesheet" href="<?php echo theme_css('bootstrap.min.css');?>"/>
    <link rel="stylesheet" href="<?php echo theme_css('fa/fontawesome.min.css');?>"/>
    <link rel="stylesheet" href="<?php echo theme_css('fa/solid.min.css');?>"/>
    
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
  <a class="navbar-brand" href="#">Home</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
      <?php echo theme_navbar_item('Welcome!', '/');
            echo theme_navbar_item('Configuration', '/config');
            echo theme_navbar_item('Modules', '/mods');
            echo theme_navbar_dropdown('Documentation', array(
    'Home' => 'https://github.com/wh1t3projects/PHP_Framework/wiki',
    'Getting started' => 'https://github.com/wh1t3projects/PHP_Framework/wiki/quickstart',
    'Modules' => 'https://github.com/wh1t3projects/PHP_Framework/wiki/modules',
    'Themes' => 'https://github.com/wh1t3projects/PHP_Framework/wiki/theme'
            )); ?>
    </ul>
  </div>
</nav>
