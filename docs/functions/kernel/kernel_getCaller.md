**Syntax**: `kernel_getCaller([int $depth])`


Get the function name that called the current function

<br/>

**Parameters**

**Optional** *depth*
<br/>
   Specify how much we should go deeper. For example, if we want to get the parent of the function that called the current one, we can use 1. We could also get the parent of the parent by using 2.

<br/>

**Return values**

Name of the caller function as a string

<br/>

**Examples**:

*Show the caller function name*
<br/>
```php
function test() {
test2();
}
function test2() {
echo kernel_getCaller(); // Will show 'test'
}
```


*Get and show the parent function name*
<br/>
```php
function parent() {
test();
}
function test() {
test2();
}
function test2() {
$parent = kernel_getCaller(1);
echo $parent; // Will show "parent"
}
```
