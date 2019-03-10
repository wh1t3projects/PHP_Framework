**Syntax**: `kernel_protected_var_deleteScript(string $varName, string $pathToScript)`


Unshare a protected variable with another script.

<br/>

**Parameters**

*$varName*
<br/>
   The protected variable's name to unshare

<br/>

*$pathToScript*
<br/>
   The script the protected variable has been shared with. The root is the module's folder

<br/>

**Return values**

`TRUE` if successfully unshared. `FALSE` if an error occurred.

<br/>

**Examples**:

*Create the protected variable **hello**, store **world** in it and share it with the script **aScript.php. Then, unshare it*** 
<br/>
```php
kernel_protected_var('hello','world');
kernel_protected_var_addScript('hello','aScript.php');
kernel_protected_var_deleteScript('hello','aScript.php');
```