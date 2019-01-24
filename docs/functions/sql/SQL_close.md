**Syntax**: `SQL_close([object $link])`


Close an SQL connection

<br/>

**Parameters**

**OPTIONAL** *$link*
<br/>
   The SQL ressource identifier returned by [SQL_connect](./SQL_connect)

<br/>

**Return values**

`TRUE` if the connection was closed. `FALSE` otherwise or if an error occur.

<br/>

**Examples**:

*Close an SQL connection by using the ressource identifier stored in driver's memory*
<br/>
`SQL_close();`


*Close an SQL connection by specifying the ressource identifier*
<br/>
`SQL_close($link);`

<br/>

**Additional information**
<br/>
This function will return `FALSE` if the provided ressource identifier is invalid or if the connection is already closed. Please check the framework log for more details.