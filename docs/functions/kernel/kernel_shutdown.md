**Syntax**: `kernel_shutdown([int $exitCode])`


Similar to calling `exit()` except that it trigger the normal shutdown of the framework before exiting. Use [kernel_planic](./kernel_panic) or [kernel_log](./kernel_log) with log level 1 instead if you want to stop the execution immediately.

<br/>

**Parameters**

**Optional** *exitCode*
<br/>
   Exit code to return to the caller (command line or web server).

<br/>

**Return values**

*No value is returned*

<br/>

**Examples**:

*Shutdown normally*
<br/>
`kernel_shutdown();`


*Shutdown with exit code 20*
<br/>
`kernel_shutdown(20);`
