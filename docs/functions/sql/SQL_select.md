**Syntax**: `SQL_select(string $columnName, string $tableName [, string $whereColumnName, string $whereValueToSearch])`


Execute a *select* query on the SQL server

<br/>

**Parameters**

*columnName*
<br/>
   The column to select


*tableName*
<br/>
    The table to execute the select in
<br/>

**Optional** *whereColumnName*
<br/>
    The column to use in a *where* condition

**Optional** *whereValueToSearch*
<br/>
    The value to look for in the column specified by *whereColumnName*
    
<br/>

**Return values**

A table containing all the results. **Null** if an error occurred

<br/>

**Examples**:

*Get everything from table **config***
<br/>
`SQL_select("*", "config");`


*Get **URL** from table **config***
<br/>
`SQL_select("URL", "config");`

*Get **URL** from table **config** where the column **active** is **1***
<br/>
`SQL_select("URL", "config", "active", 1);`

<br/>

**Additional information**

Unlike [SQL_query](./SQL_query), all strings sent to this function are **escaped** before being sent to the SQL server. That means that any query sent by this function *should* be safe to execute.