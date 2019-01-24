**Syntax**: `SQL_query(string $query, [object $link])`


Send an SQL query to the remote SQL server.

<br/>

**Parameters**

*$query*
<br/>
   The full SQL query to send.

<br/>

**Optional** *$link*
<br/>
   An optional SQL ressource identifier returned by [SQL_connect](./SQL_connect)

<br/>

**Return values**

The result of the query. Depending on the query and the driver, it can be a string, a table or a boolean.

<br/>

**Examples**:

*Get all entry of the table "test" and assign it to a variable*
<br/>
`$result = SQL_query('SELECT * FROM test');`


*Get all entry of the table "test" by using a given ressource identifier and assign it to a variable*
<br/>
`$result = SQL_query('SELECT * FROM test',$resid);`

<br/>

**Additional information:**<br/>
The SQL query can be different from a server to another, it all depend on the driver. For example, a MySQL query may not work under MSSQL or PostgreSQL and vice-versa.