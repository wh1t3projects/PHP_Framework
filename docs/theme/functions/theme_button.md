**Syntax**: `theme_button(string $buttonText, [string $buttonLink, string $elementID])`


Render a button using the primary button class.

<br/>

**Parameters**

*$buttonText*
<br/>
   The text to display on the button

**Optional** *$buttonLink*
<br/>
    The link of the button. Can be a path or a URL. If not set, the link will be a sharp ('#');

**Optional** *$elementID*
<br/>
    The HTML ID and name of the button.

<br/>

**Return values**

The HTML code for rendering a button using the `btn-primary` class with the specified text, link and ID.

<br/>

**Examples**:

*Print a button with the text **Hello!** that point to the **example.org** website*
<br/>
`echo theme_button('Hello!', 'http://example.org/');`


*Print a button with the text **Hello!** that point to the **hello** page at root*
<br/>
`echo theme_button('Hello!', '/hello');`