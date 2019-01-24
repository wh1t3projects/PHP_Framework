**Syntax**: `theme_navbar_item(string $text, [string $link, bool $isActive, string $elementID])`


Render a navigation item meant to be used in a navigation bar (usually in the header of the page).

<br/>

**Parameters**

*$text*
<br/>
   The text to display

**Optional** *$link*
<br/>
    The link of the item. Can be a path or a URL. If not set, the link will be a sharp ('#')

**Optional** *$isActive*
<br/>
    Set the item to be seen as `active` by the end user or not. This add the `active` class to the element. If not set, it is determined automatically based on the provided `$link`.

**Optional** *$elementID*
<br/>
    The HTML ID and name of the item.

<br/>

**Return values**

The HTML code for rendering a navigation item inside a navigation bar with the specified text, link, state and ID.

<br/>

**Examples**:

*Print a navigation item with the text **Welcome!** and a link to the root of the website*
<br/>
`echo theme_navbar_item('Welcome!', '/');`<br/>

**NOTE**: You can see this example in context in the *header.php* file.