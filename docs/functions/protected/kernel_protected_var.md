**Syntax**: `kernel_protected_var(string $varName [, object $varData])`


Get or set a protected variable for the script calling it

<br/>

**Parameters**

*$varName*
<br/>
   The name of the variable

**Optional** *$varData*
<br/>
   The data to put into the variable

<br/>

**Return values**

The stored data for the given variable. If the variable doesn't exist, a `Warning` will be emitted and `NULL` is returned.<br/>
If *$varData* is set, the currently stored data is replaced and `TRUE` is returned. If the new data is `NULL`, the variable is removed from memory and `TRUE` is returned.

<br/>

**Examples**:

*Create a new variable called **hello** and store the string **world** in it*
<br/>
`kernel_protected_var('hello','world');`


*Get the content of the variable **hello** and assign it to a local variable*
<br/>
`$localVar = kernel_protected_var('hello');`


*Delete (or **unset**) the variable **hello***
<br/>
`kernel_protected_var('hello', null);`


<br/>

**Additional information**

If *$varData* is not set and the variable does not exist for the current script, a **warning** will be sent to the log.