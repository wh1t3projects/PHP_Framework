**Syntax**: `theme_img(string $imageFile)`


Get the external path (user-accessible) to the specified image. The file must be located inside the `IMG` folder of the theme. If inside a sub-folder, it must be specified as a *relative* path.

<br/>

**Parameters**

*$imageFile*
<br/>
   The file to get the path to.

<br/>

**Return values**

The external path to the specified file.

<br/>

**Examples**:

*Print the path to the **hello.png** file*
<br/>
`echo theme_img('hello.png');`


*Print a **img** element with the path to **hello.png** from a web page*
<br/>
`<img src='<?php echo theme_img('hello.png');?>'/>`