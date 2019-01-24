**Syntax**: `SQL_resid([object $link])`


Get or set the SQL ressource identifier.

<br/>

**Parameters**

**Optional** *$link*
<br/>
   The new ressource identifier to use by default.

<br/>

**Return values**

If **$link** is not provided, the current ressource identifier is returned. If it is provided, the status of the session on the remote server will be verified. If the verification fail, `FALSE` will be returned and the current ressource ID will be kept. Otherwise, `TRUE` will be returned.

<br/>

**Examples**:

*Get the current ressource identifier and assign it to the `resid` variable*
<br/>
`$resid = SQL_resid();`


*Set a new ressource identifier*
<br/>
`SQL_resid($newResID)`