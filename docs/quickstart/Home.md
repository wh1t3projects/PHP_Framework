## Getting started with the Framework

Welcome to the *Getting Started* guide! This guide will help you get set up and will teach you the basics on how to use the Framework. <br/>
First, you will need to install it so that you can use it. Don't worry, it's very easy!<br/>

Copy all files in into a folder that can be served by a web server (with PHP installed). Then try to access it from your web browser.<br/>
It should prompt you to launch the configuration wizard. Since this is your first time, you can use the default settings. They are good for most situations.<br/>
**NOTE**: If you would like the modules (and web pages) to be able to talk with a database server, you will need to specify the database settings.<br/>
Later, if you would like to change any setting, you do so by editing the `config.php` file.<br/>

Once you click **Save**, you will get a confirmation message if everything went fine, along with a link to test the framework. Note that, for security reasons, the wizard **will be disabled** after saving.<br/>

Note that, for the moment, only **Apache** is *natively* supported. If you would like to use another web server (like Nginx or IIS), you will need to manually configure it so that it redirects all requests to index.php. Take a look at the `.htaccess` file for more information.<br/><br/>

If you need more information about each individual settings, please take a look at the [configuration list](../config).<br/><br/>
[Start!](./Page01-WritingYourFirstScript)