**Syntax**: `theme_js(string $jsFile)`


Get the external path (user-accessible) to the specified JS file. The file must be located inside the `JS` folder of the theme. If inside a sub-folder, it must be specified as a *relative* path.

<br/>

**Parameters**

*$jsFile*
<br/>
   The file to get the path to.

<br/>

**Return values**

The external path to the specified file.

<br/>

**Examples**:

*Print the path to the **helper.js** file*
<br/>
`echo theme_js('helper.js');`


*Print the **script** element of the **BODY** with the path to **helper.js** from **footer.php** (can be anywhere else, including **header.php**)*
<br/>
`<script type="text/javascript" src="<?php echo theme_js('helper.js');?>" </script>`