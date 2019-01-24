##### Creating a basic module
Modules can be very useful by providing you with more features that can be used within web pages and can even provide a full web app that will work on top of your website. You can get more information about modules in the [modules](../modules/Home) section of the documentation.<br/>

Here, we will only show you the basics on how to create a module. There is absolutely no limit on what you can with them, so you can go very deep and build a powerful web app.<br/>
One important thing to remember is that modules are loaded during the Framework **boot process**. Therefore, it is preferable to **only** declare functions, load libraries (if needed) and hook to [events](../functions/event_handler/kernel_event_register) during the initialization. Then, you can use these events to run your code.<br/>
Modules folder are named using the syntax *organization_moduleName*. For example, if we want to create a module named *helloWorld*, then our module's folder would be *wh1t3projects_helloWorld*.<br/>
The reason to specify the organization's name as part of the module's name is the same than with our function that we created earlier: to prevent a 'collision'. Only in this case, it's to prevent having a 'collision' if another organization or developer create a module with the same name and you would like to use both. Obviously, that would be impossible, since one's files would replace the other's.<br/>

Now create a folder inside the **modules** directory and create a file named `init.php` inside it. Open the new file for editing.<br/>

Here you can write any PHP code you want, but like previously stated, it is best to only create functions here and hook to events to run our code. A module can also only provide functions (never hooking to an event) and therefore, act only as a library for other modules or even web pages.<br/>
**NOTE**: In the event you wrote code that works by using another module as a library, or if you need features from it that are automated, you might want to first check if that module is installed by using the [kernel_checkIfModuleIsValid](../functions/kernel/kernel_checkIfModuleIsValid) function prior to doing anything. However, this guide doesn't cover its usage.<br/>

Add the usual `<?php` and `?>` at the beginning and ending of the file respectively, so that your module's **code** will be processed and not be sent as **text** to the browser.<br/>

Now you can get a bit original. Create a function with any name you want, but again, to prevent a 'collision' you need to prefix it with your **module's** name (and not the organization) followed by the underscore, like `helloWorld_hello`.<br/>
Write some code that will output something to the browser (instead of returning data), like `echo 'Hello World!';`. This will make it easier to see our module is working when calling it.<br/>
Now add a call, to your new function, inside a web page (make sure to put it inside a `div` with a `container` or you may not see your text!). Save the files and test it via your browser.<br/>

It works! Now, if you want, you can even call the `theme_column` function from within your new function and make it say anything you want. Make sure that you call your new function **only when rendering a page** in that case or you will get an error because the theme's functions are not loaded and, therefore, not declared.

[Previous](./Page06-UsingAThemeFunction) | [Next](./Page08-ThatsIt)