**Syntax**: `kernel_protected_var_addScript(string $varName, string $pathToScript)`


Share a protected variable with another script.<br/>
**NOTE**: The variable's name must not already exist in the target script

<br/>

**Parameters**

*$varName*
<br/>
   The protected variable's name to share

<br/>

*$pathToScript*
<br/>
   The script to share the protected variable with. The root is the module's folder

<br/>

**Return values**

`TRUE` if successfully shared. `FALSE` if an error occurred.

<br/>

**Examples**:

*Create the protected variable **hello**, store **world** in it and share it with the script **aScript.php*** 
<br/>
```php
kernel_protected_var('hello','world');
kernel_protected_var_addScript('hello','aScript.php');
```