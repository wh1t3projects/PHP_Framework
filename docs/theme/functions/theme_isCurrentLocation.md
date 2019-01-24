**Syntax**: `theme_isCurrentLocation(string $location)`


Check if the specified location is the current one.

<br/>

**Parameters**

*$location*
<br/>
   The location to compare the current one against. Must be the full path from root to work (`/hello` for example) since the match is made from root. Note that the location is the *internal* one (the real URL doesn't matter).

<br/>

**Return values**

Return `TRUE` if it is the current location, `FALSE` otherwise.

<br/>

**Examples**:

*Check if current location is the **hello** page at root*
<br/>
`$return = theme_isCurrentLocation('/hello');`


*Check if current location is the **world** page inside the **hello** folder which is at root*
<br/>
`$return = theme_isCurrentLocation('/hello/world');`