**Syntax**: `theme_navbar_dropdown(string $text, array $itemsArray [, string $elementID])`


Render a navigation dropdown item meant to be used in a navigation bar (usually in the header of the page).

<br/>

**Parameters**

*$text*
<br/>
   The text of the dropdown to display

*$itemsArray*
<br/>
    An associative array of items to be displayed inside the dropdown.<br/>
    Each elements of the array consist of the *key* being used for the display text and the *value* for the link, just like this: `'SomeText' => 'http//example.org'`.<br/>

**Optional** *$elementID*
<br/>
    The HTML ID and name of the dropdown.

<br/>

**Return values**

The HTML code for rendering a dropdown inside a navigation bar with the specified text, items array and ID.

<br/>

**Examples**:

*Print a dropdown with the text **Welcome!** and two items. The first display **Hello world!** with a link to the **example.org** website and the second display **Home** with a link to to the root of the website*
<br/>
```php
echo theme_navbar_dropdown('Welcome!', array(
    'Hello world' => 'http://example.org',
    'Home' => '/'
));
```
<br/>

*Print a **Documentation** dropdown with a few helpful links*
```php
echo theme_navbar_dropdown('Documentation', array(
    'Home' => '#',
    'Getting started' => '#',
    'Modules' => '#',
    'Themes' => '#'
));
```
**NOTE**: You can see this example in context in the *header.php* file.