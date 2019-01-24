**Syntax**: `kernel_date([string $format])`


This function return the current date and time based on the timezone set in the configuration. If the format is not specified, the default `Y-m-d H:i:s` is used.

<br/>

**Parameters**

**Optional** *format*
<br/>
   The format of date to use. If not specified, the default `Y-m-d H:i:s` is used.

<br/>

**Return values**

Returns a formatted date string. May return **false** if an invalid format is given

<br/>

**Examples**:

*Show the current time and date using the default format*
<br/>
`echo kernel_date();`


*Show the current date in the year-month-day format using numbers only*
<br/>
`echo kernel_date('Y-m-d');`


*Show the current time in the 24 hours format*
<br/>
`echo kernel_date('H:i:s');`