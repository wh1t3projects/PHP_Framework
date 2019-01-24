## Themes

### What are themes?
Themes are bundles of files that defines how a page will look like to the end user. This can be to describe how a button should look like or how to place the different elements on the page. They can also defines how big are each elements (or group) and enable the developer who is using it to add functionality to an otherwise static page. It may also have specific rendering instructions for the engine using it.<br/>
For the Framework, themes contains three main PHP files: a header, a footer and the functions library (more can be used). The header and the footer are **always** rendered, while the library contains the functions for not only these two files, but also the page that is being rendered. It also contains different folders for other types of files that are required.
<br/>

### Composition of a theme
#### The main files:
##### functions.php
This is main file where all the rendering functions are stored. It is *included* before rendering. If another PHP file is required for rendering, this is best place to include it from.
##### header.php
Contains the header of the page. This file will always be rendered FIRST. Once rendering is complete, the `SHOWHEADER` event is triggered.<br/>
This file must contain the start of the `HTML` element as well as the `HEAD` element and, if possible, `BODY` too.
##### footer.php
Contains the footer of the page. This file will always be rendered LAST. Once rendering is complete, the `SHOWFOOTER` event is triggered.<br/>
This file must contains the end of the `HTML` element and, if possible, the end of `BODY` too.

#### The folders
Each theme comes with at least four different folders. Each of these folders store a specific kind of file:
```
CSS: CSS files
IMG: Images (Only for the theme)
JS: JS (or JavaScript) files
FONTS: Font files
```
The structure of each folder is unique to each theme. However, mixing type of files is not recommended, as this will prevent you from using the functions correctly.<br/>
Note that the `IMG` folder is used to store images for the theme ONLY. Users' uploaded content or any other image must **not** be stored here. This is because they can be lost if a new theme is installed.
<br/>

### Functions
Every theme can have a different set of functions and they may provide their own documentation. However, some basic functions will **always** be present in **all** themes and will always work the same way from a theme to another.<br/>
The list of functions can be found [here](./functions/Home)<br/>
<br/>

### The default theme
The default theme is built from the Bootstrap [Jumbotron](https://getbootstrap.com/docs/4.2/examples/jumbotron) example. It allows you to test the framework's basic systems and see how a theme can be built.<br/>
If you are familiar with Bootstrap, using this theme will be easy. Most of the features used by the example have been converted into functions, enabling you to reuse each elements without having to rewrite everything.<br/>
It also include Font Awesome (only the `Solid` font family is included).<br/>
This theme is recommended to new developers who would like to experiment with the Framework before going deeper or to those who would like to build their application quickly, without creating a custom UI.<br/>

**NOTE**: The HTML output of the theme is not perfect. If you check the source code of a rendered page, you will notice it. Be reassured, however, that this will not affect the functionality of the theme or the Framework.<br/>
If this is important to you, consider using a module that will beatify it. Note that this may impact the performance.