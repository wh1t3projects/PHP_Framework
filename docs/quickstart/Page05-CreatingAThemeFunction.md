##### Creating a theme function

As you may have noticed, we've just rewrote the same HTML code a few times for our columns. While this may be fine in this case, since we don't have to write a lot of the same code for every column, we still can improve this to avoid repetition. The best way to do this, is to create a function in our theme.<br/>
This allow our code to not only be lighter, but performance can (sometime) benefit from that. This also allows us to modify only a few lines (let say, to change the layout) instead of modifying everything, potentially saving you a **lot** of time.<br/>

Our function will be very simple in this case. It will accept only two arguments: the title of the column and the text to display. It will then render our HTML code and return it to the caller (which is the web page).<br/>

**NOTE**: Functions provided by the theme are stored in the `functions.php` file. Each theme have its own set of functions, but they all have a common list of basic functions they all provide. You can find more information about themes and their functions in the [theme](../theme/Home) section of the documentation.<br/>
<br/>

To begin, go into the default theme's folder (in `/themes/default` by default), then open the `functions.php` file for editing.<br/>
As you can see, our theme is currently providing only the basic set of functions. We will create a new one that will handle our columns. We will name it `theme_column`. <br/>
If you want, you can actually name it with any name you like, but it should start with the prefix `theme_` to indicate it is part of the theme. This is, in part, to avoid a 'collision' with another function that could be provided by a module. In case you don't know, redeclaring a function in PHP will result in a fatal error and this **will** crash the Framework.<br/>

Now, go at the bottom of the file, add a line if needed and paste the following:
```php
function theme_column($title, $text) {
    return "<div class=\"col-md-4\">
        <h2>$title</h2>
        <p>$text</p>
      </div>";
}

```
Our function accept two arguments. The first one is `$title` and the second, `$text`. Inside, we can see our very small HTML code with the the title and the text variables.<br/>
The `h2` section is where the title goes and is displayed bigger than our text in the `p` section.<br/>
The [return](http://php.net/manual/en/function.return.php) function that we use here is to tell PHP to return the following string (with the variables replaced by their content) to the caller.<br/>
Also, we have backslashes just before the double quotes. That's because our return's argument starts and ends with a double quote and we need to escape them so they don't close our argument prematurely. We also need these quotes for HTML to understand that `col-md-4` is our CSS class's name.<br/>
There are more efficient ways (performance-wise) to deal with this, but it doesn't always provide clear code and, therefore, hasn't been used in this guide to help with code readability.<br/>

[Previous](./Page04-AddingSomeContent) | [Next](./Page06-UsingAThemeFunction)