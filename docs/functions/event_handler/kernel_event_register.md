**Syntax**: `kernel_event_register (string $eventName, string $command)`


Register a function(s) to an event. This is similar to adding an entry into the corresponding event array except that *$command* is validated before adding it.

<br/>

**Parameters**

*eventName*
<br/>
   The name of the event to add to

<br/>
*command*
<br/>
    The "command" or *function* to add. The value will be verified to not include unauthorized or known dangerous calls (in the event handling context) before adding it.

<br/>

**Return values**

`TRUE` if successful. `FALSE` if an error occurred or if the provided value contains an unauthorized call.

<br/>

**Examples**:

*Register `echo 'Hello World!'` to **MYCUSTOMEVENT***
<br/>
`kernel_event_register('MYCUSTOMEVENT', "echo 'Hello World'");`