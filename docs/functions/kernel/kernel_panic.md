**Syntax**: `kernel_panic()`


Trigger a Kernel panic that will result in a crash. When called, a full stack trace is generated to find out what caused the panic and then sent to the log. The log, in this case, is written to **panic.log**.

NOTE: If the file **panic.log** exist and `debug_panic` is set to `false`, the framework will **not** run until deleted.

<br/>

**Parameters**

*This function does not accept any parameters*

<br/>

**Return values**

*No value is returned.*

<br/>

**Examples**:

*No example available*