**Syntax**: `kernel_event_trigger(string $event)`

Trigger an event. Any event can be specified, as long as it exist. See [kernel_event_create](./kernel_event_create) for creating new events. All registered functions will be called in the order they were registered, starting with the **oldest** one.

Default events are, in the order they are called:

```
MODULESLOADED -- All the modules are loaded.
STARTUP -- About to render the page
SHOWHEADER -- Page header rendered
SHOWCONTENT -- Page content rendered
SHOWFOOTER -- Page footer rendered
SHUTDOWN -- The framework is shutting down
```

Note that manually calling any of these events is **not recommended** as it can lead to unexpected behavior.

<br/>

**Parameters**

*event*
<br/>
   The event name to trigger

<br/>

**Return values**

`TRUE` if the event has been triggered. Note that this doesn't mean the any code was executed nor that any of the code didn't failed at some point without crashing the framework.

`FALSE` if the event doesn't exist.

<br/>

**Examples**:

*Triggering the MYCUSTOMEVENT event*
<br/>
`kernel_event_trigger('MYCUSTOMEVENT')`;
