**Syntax**: `SQL_update(string $tableName, string $columnToUpdate, string $valueToSet,string $columnToMatch, string $valueToMatch [, string $additionalArguments])`


Update row(s) that match the given criteria

<br/>

**Parameters**

*$tableName*
<br/>
   The table to update row(s) in

*$columnToUpdate*
<br/>
    The column to update

*$valueToSet*
<br/>
    The new value to set

*$columnToMatch*
<br/>
    The column to find a match in

*$valueToMatch*
<br/>
    The value to match

**Optionnal** *$additionalArguments*
<br/>
    Addtional arguments to append to the query<br/>
    **Warning**: Make sure that the specified arguments works for all types of SQL servers. Otherwise, errors may occur if a different SQL driver than expected is used. One way to work around this kind of issue is to adapt your arguments based on the currently loaded driver

<br/>

**Return values**

`TRUE` if the query was successful. `FALSE` if an error occurred.

<br/>

**Examples**:

To be completed
==
*Example 1 description*
<br/>
`Block of code`


*Example 2 description*
<br/>
`Block of code`