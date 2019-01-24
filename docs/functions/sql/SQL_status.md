**Syntax**: `SQL_status([object $link])`


Check the status of the SQL session on the remote server.

<br/>

**Parameters**

**Optional** *$link*
<br/>
   A valid SQL ressource identifier to verify.

<br/>

**Return values**

`TRUE` if the session is active on the remote server, `FALSE` otherwise or if the ressource identifier is invalid

<br/>

**Examples**:

*Check the status of the session stored in the driver's memory*
<br/>
`SQL_status();`


*Check the status of a given session*
<br/>
`SQL_status($resid);`


*Display "Working!" if the session is valid* <br/>
`if (SQL_status()) { echo "Working!";}`