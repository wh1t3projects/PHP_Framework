**Syntax**: `theme_css(string $cssFile)`


Get the external path (user-accessible) to the specified CSS file. The file must be located inside the `CSS` folder of the theme. If inside a sub-folder, it must be specified as a *relative* path.

<br/>

**Parameters**

*$cssFile*
<br/>
   The file to get the path to.

<br/>

**Return values**

The external path to the specified file.

<br/>

**Examples**:

*Print the path to the **style.css** file*
<br/>
`echo theme_css('style.css');`


*Print the **link** element of the **HEAD** with the path to **style.css** from **header.php***
<br/>
`<link rel="stylesheet" href="<?php echo theme_css('style.css');?>"/>`