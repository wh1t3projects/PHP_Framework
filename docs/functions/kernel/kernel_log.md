**Syntax**: `kernel_log([string $message] [, int $messageLevel])`


Send the specified **$message** to the framework log. If `debug` is enabled, the log is written to the file specified by `debug_file` each time a message is sent to it. If **$messageLevel** is not specified, the default level (5) is used.

Can also be used to get a full copy of the current log.

<br/>

**Parameters**

**Optional** *message*
<br/>
   The message to send to the log
<br/>

**Optional** *$messageLevel*
<br/>
The type of the message which can be one of the following:
```
5 (Normal): Normal logging. Can be used for anything. (DEFAULT)
4 (Warning): Something wrong occurred but it's not critical. The function will likely continue using a workaround.
3 (Critical): An error occurred that may cause the function to crash.
2 (Fatal): An fatal error occurred that may the module to crash.
1 (PANIC): An unexpected or fatal error occurred and will result in a complete crash. 
```
If the level 3 or 2 is used, additional debugging informations of the function that called `kernel_log` will be sent to the log.
If the level 1 is used, [kernel_panic()](./kernel_panic) will be called after sending the message with debugging informations to the log.

<br/>

**Return values**

Return `TRUE` if a message is specified and the log level is valid. <br/>
Return `FALSE` if an invalid message or log level is provided. The error will be sent to the log

If no parameters is given, an array containing the current log is returned.

<br/>

**Examples**:

*Using the log normally*
<br/>
`kernel_log('Hello world!');`


*Giving a warning*
<br/>
`kernel_log('Invalid argument given' ,4);`



*A critical error occurred*<br/>
`kernel_log('You are doing something wrong...' ,3);`



*A FATAL error occurred*<br/>
`kernel_log('This was unexpected. I'm failing' ,2);`



*No more hope*<br/>
`kernel_log('This wasn't expected and I don't like that. I'M PANICKING!' ,1);`