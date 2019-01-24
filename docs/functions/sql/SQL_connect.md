**Syntax**: `SQL_connect([string $host, string $user, string $pass, string $dbname])`


Connect to an SQL server using the driver specified in the configuration. If no parameters is specified, the function will take the information set in the config. Otherwise, **ALL** parameters must be specified.


<br/>

**Parameters**

**Optional** *$host*
<br/>
   SQL server host name or IP address.

**Optional** *$user*
<br/>
   Username to use for authentication

**Optional** *$pass*
<br/>
   Password to use for authentication

**Optional** *$dbname*
<br/>
   Database name.

<br/>

**Return values**

If the connection is successful and the database can be opened, the SQL ressource identifier is returned. If an error occur, `FALSE` will be returned and the error will be reported to the framework log.

<br/>

**Examples**:

*Connect to the SQL server with the default values (config.php)*
<br/>
`SQL_connect();`


*Connect to the SQL server with custom parameters*
<br/>
`SQL_connect("localhost","testuser","userpassword","testdb");`


<br/>

**Additional informations**
<br/>
If the connection is successful, the SQL_resid function will be called to store the ressource ID in memory.
