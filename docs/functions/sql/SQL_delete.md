**Syntax**: `SQL_delete(string $tableName, string $columnToMatch, string $valueToMatch [, string $addtionalArguments])`


Delete row(s) from a table that match the given criteria

<br/>

**Parameters**

*$tableName*
<br/>
   The table to delete row(s) from

*$columnToMatch*
<br/>
    The column to search a match in

*$valueToMatch*
<br/>
    The value to match

**Optionnal** *$additionalArguments*
<br/>
    Addtional arguments to append to the query<br/>
    **Warning**: Make sure that the specified arguments works for all types of SQL servers. Otherwise, errors may occur if a different SQL driver than expected is used. One way to work around this kind of issue is to adapt your arguments based on the currently loaded driver


<br/>

**Return values**

`TRUE` if the operation was successful. `FALSE` if an error occurred.

<br/>

**Example**:

*Delete any row with 'test' as their value in the column 'users'*
<br/>
`SQL_delete('aTable','users','test');`