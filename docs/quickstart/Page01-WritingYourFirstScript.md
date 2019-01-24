### Writing your first web page (aka Hello World!)

The Framework is built in a way that makes it easy to start building a website. The **webroot** folder is where all the pages must be saved (except if a module overridden this behavior by redirecting requests to itself).<br/>
All files needs to use the extension defined by the `documents_extension` setting. For example, if the setting is set to `html`, then your file need be named like the following: *filename.html*. If you change the setting to `hello`, then you would need to use *filename.hello* instead.<br/>
Also, when accessing your page from your web browser, never append the file's extension to the URL, unless you intend to download it directly. You will receive a 404 error if you try. That's because, when you specify an extension and it doesn't represent a page with the defined extension, the Framework will think that you want to download the specified file. Since the requested file doesn't exist, it fails.<br/> **NOTE**: Only files' extensions defined by the `allowed_file_ext` setting can be directly downloaded. Access to all other files will be denied and the Framework will reply with a 404 error (to protect a remote attacker from discovering which files are inside).<br/>
In any cases, the extension you choose doesn't matter and will **always** be processed as a PHP file. Therefore, any code you write inside will be executed.<br/>
Any other file that is present within the folder can be called from a PHP script.<br/>
<br/>

[Previous](./Home) | [Next](./Page02-TheScript)