**Syntax**: `kernel_override_url(string $URL, string $pathToScript [, int $mode])`


Configure the Framework to forward all requests that match the given **partial** URL to the specified script.<br/>
This is usually used to override the standard page rendering system to give access to additional features (like an Admin panel). A good example is the **WebAPI** module, which makes use of this function to expose the API endpoint.

<br/>

**Parameters**

*URL*
<br/>
   The partial URL to match

*pathToScript*
<br/>
    The script to forward the request to. The root (/) is the module's folder

**Optional** *mode*
<br/>
    The URL matching mode. `1` is *Normal* mode and `2` is *Explicit* mode. <br/>
    **Normal mode**: Only the beginning of the request URL must match (see **Additional information**)<br/>
    **Explicit mode**: The request URL must match exactly.<br/>
    
<br/>

**Return values**

`TRUE` if successful and override is registered. `FALSE` is an error occurred or if the partial URL is already registered.

<br/>

**Examples**:

*Redirect all requests for /helloworld to **hello.php***
<br/>
`kernel_override_url('/helloworld', 'hello.php')`<br/>
In this example, if a request is made to */helloword*, it will execute *hello.php* from the module's root folder. But, if a request is made to */helloworld/test*, it will not be executed and will result in a 404 error, unless *helloworld/test* exist in the *webroot* folder. <br/>
NOTE: `kernel_override_url('/helloworld', 'hello.php', 2)` will produce the same result<br/>

*Redirect all requests for /helloworld/&ast; to **hello.php***
<br/>
`kernel_override_url('/helloworld', 'hello.php', 1)`<br/>
In this example, all requests for */helloworld*, including */helloworld/test*, will be forwarded to *hello.php* in the module's root. The *Normal* mode is useful for Admin panels for example<br/>
<br/>

**Additional information**<br/>
Note that when using the *Normal* mode, the URL will NOT match if the request URL partially match. For example, if you register an override for */helloword* and the request URL indicates */helloworldtest*, it will NOT be matched. Only */helloworld* and anything after */helloworld/* (note the ending **slash**) will match.