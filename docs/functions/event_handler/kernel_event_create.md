**Syntax**: `kernel_event_create (string $eventName)`

Create an event. This actually just create an empty array in the *$EVENTS* array if it doesn't already exist.

<br/>

**Parameters**

*eventName*
<br/>
   The name of the event to create

<br/>

**Return values**

`TRUE` if successful. `FALSE` if an error occurred or if the event already exist.

<br/>

**Examples**:

*Create **MYCUSTOMEVENT** event*
<br/>
`kernel_event_create('MYCUSTOMEVENT')`