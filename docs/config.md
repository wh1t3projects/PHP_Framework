### Available settings in the configuration wizard
Below is the description for each available settings in the Framework configuration wizard.<br/>
#### System

*Which folder is the root of the website?*</br>
Setting name: **webroot**<br/>
Default: **webroot**<br/>
This setting defines which folder, from the framework's root, holds all the web pages. These pages can contain PHP code, but is not mandatory. This is called the *webroot* folder, because this is where the the default document resides and all files under that directory are **publicly available**.<br/>
This folder must exist and be readable.<br/>

*Which folder hold the themes?*<br/>
Setting name: **themes**<br/>
Default: **themes**<br/>
This setting defines which folder, from the framework's root, holds the themes and their related files. This includes not only the footer and header, but also any CSS, images and fonts. This folder is not directly accessible and only the currently selected theme will have its files accessible via the "folder" defined by **themes_fromWebroot**<br/>

*Which folder hold the modules?*<br/>
Setting name: **modules**<br/>
Default: **modules**<br/>
This setting defines which folder, from the framework's root, holds the modules that can be added to the installation.<br/>
<br/>
#### Database
*What is the SQL server host name or address?*<br/>
Setting name: **sql_host**<br/>
Default: Null<br/>
The SQL host to connect to when a SQL connection is required. Not required if the application doesn't require a connection to a SQL server.<br/>

*What is the username?*<br/>
Setting name: **sql_user**<br/>
Default: Null<br/>
The username to use when authenticating against the SQL server. Not required if the application doesn't require a connection to a SQL server.<br/>

*What is the password?*<br/>
Setting name: **sql_pass**<br/>
Default: Null<br/>
The password to use when authenticating against the SQL server. Not required if the application doesn't require a connection to a SQL server.<br/>

*What is the name of the database?*<br/>
Setting name: **sql_db**<br/>
Default: Null<br/>
The database to use when executing SQL queries. Not required if the application doesn't require a connection to a SQL server.<br/>

*Which driver to use?*<br/>
Setting name: **sql_drv**<br/>
Default: Null<br/>
The setting define which SQL driver to use. This driver will be loaded during the framework's startup and will cause a Kernel panic if the driver doesn't exist. New drivers can be installed by copying relevant files to `Kernel/SQL/`. If the setting is `Null` or empty, all SQL related functions will not be defined and, therefore, be unavailable.<br/>
**NOTE**: The list shown in the wizard is generated based on the currently available drivers, **without** checking if they currently work. If a new driver is installed **after** the wizard has been started, the page must be reloaded (causing any changes to be lost) before they will bw shown. It is always possible to change the selected driver after saving the configuration.<br/>

*What is the tables prefix?*<br/>
Setting name: **sql_prefix**<br/>
Default: **prefix_**<br/>
The prefix to add to the tables' name when executing queries.<br/>
**NOTE**: This exclude the [sql_query()](functions/SQL/sql_query) function, which allow "raw" queries to be sent to the SQL server.<br/>
<br/>

#### Application
*What is the base URL?*<br/>
Setting name: **app_location**<br/>
Default: *Determined during setup. Based on the current request URL*<br/>
This setting defines what is the second part of the URL (after the hostname). This is considered **visible** *root* of the application.<br/>

*Where the framework is installed?*<br/>
Setting name: **app_real_location**<br/>
Default: *Determined during setup. Based on the current **real** location of the Framework on the server*<br/>
This setting defines where is the application installed on the server. Used to determine full paths when generating them.<br/>

*What is the name of the theme?*<br/>
Setting name: **theme**<br/>
Default: **default**<br/>
This setting defines which theme will be used when rendering pages. The theme must exist when saving.<br/>

*What is the name of the default document to show?*<br/>
Setting name: **default_document**<br/>
Default: **index**<br/>
This setting defines which document will be used for rendering when not specified in a request. The value must **not** contain the extension.<br/>

*What is the extension of all the documents to execute in webroot?*<br/>
Setting name: **documents_extension**<br/>
Default: **html**<br/>
This setting defines the extension to apply to all requested pages. This extension is appended to the files' name and is used internally to find the correct page in the **webroot** folder.<br/>
**NOTE**: No matter what extension is used, all files with this extension will be processed as PHP scripts when they are requested.<br/>

*What is the ISO 639-1 language code?*<br/>
Setting name: **lang**<br/>
Default: **en**<br/>
This settings defines the application's web interface language. The value must be in the format defined by *ISO 639-1*.<br/>
**NOTE**: Changing this setting will not have **any effect** on the behavior of the Framework. It is therefore only useful to the modules and any other part of the application that uses it.<br/>

*What is your time zone (in PHP format)?*<br/>
Setting name: **timezone**<br/>
Default: *Determined during setup. Based on the current web server settings (if available)*<br/>
This setting defines which timezone to use when calculating dates. Using the correct value is important since this is used by the logging system and **all** functions (including PHP functions) that requires the time.<br/>

*What are the file"s extension that can be downloaded directly?*<br/>
Setting name: **allowed_file_ext**<br/>
Default: **jpg;gif;png;txt**<br/>
This setting defines which type of files (based on their extension) are allowed to be downloaded directly from the **webroot** folder. They will **not** be processed as PHP scripts.<br/>

#### Theme
*Check if the theme is compatible with the current version of the Framework?*<br/>
Setting name: **theme_checkVersion**<br/>
Default: **Yes**<br/>
This setting defines if the Framework should check if the selected theme reports to be compatible with the current version.<br/>

*What is the folder that hold the css files?*<br/>
Setting name: **themes_css**<br/>
Default: **css**<br/>
This setting defines which folder, inside the theme's folder, hold the CSS files. This setting is only used by the theme when generating the publicly available path.<br/>

*What is the folder that hold the js files?*<br/>
Setting name: **themes_js**<br/>
Default: **js**<br/>
This setting defines which folder, inside the theme's folder, hold the JavaScript files. This setting is only used by the theme when generating the publicly available path.<br/>

*What is the folder that hold the image files?*<br/>
Setting name: **themes_img**<br/>
Default: **img**<br/>
This setting defines which folder, inside the theme's folder, hold the images. This setting is only used by the theme when generating the publicly available path.<br/>

*What is the public folder that refer to the theme?*<br/>
Setting name: **theme_fromWebroot**<br/>
Default: **theme**<br/>
This setting defines which "folder" used for public path to the theme's files. This is used by the theme when generating paths, but also by the framework to expose the theme's files.<br/>


#### Debug

*Enable debugging?*<br/>
Setting name: **debug**<br/>
Default: **False** (**No** in the setup wizard)<br/>
This setting control whether or not debugging is enabled. If disabled, all warnings and errors (except when a Kernel panic occur) are hidden from the user. Also, the logs are not written to the log file.<br/>
**NOTE**: A value of `FALSE` is recommended for production setups only, since this may prevent proper debugging.<br/>
Also, this setting does **not** disable the logging system internally. Logs can still be recovered by modules at runtime and a Kernel panic will dump the current log to **panic.log**.<br/>

*When debugging is enabled, which file is used for storing the log? (root is the framework's root)*<br/>
Setting name: **debug_file**<br/>
Default: **/debug.log**<br/>
This setting defines where the log file should be stored (along with it's name). The root (/) is the application's root. If a sub-folder is used, this folder must exist before saving.<br/>

*Allow application to run even if a kernel panic occurred before?*<br/>
Setting name: **debug_panic**<br/>
Default: **True** (**Yes** in the setup wizard)<br/>
This setting control whether or not subsequent executions should be allowed if a Kernel panic occurs. Disabling it can be useful if the stability of the code is fully trusted and that a Kernel panic means that no more requests should be processed until the issue has been investigated (like if an attacker tried to do something terribly wrong). This effectively put the Framework in some sort of "lockdown" mode.<br/>

*When debugging is enabled, what do you want to show?*<br/>
Setting name: **debug_output_level**<br/>
Default: **0**<br/>
This setting control which type of debugging messages should be sent to the browser. Please see the **debug_level** setting for a list of acceptable values.<br/>

*When debugging is enabled, what do you want to send to the log?*<br/>
Setting name: **debug_level**<br/>
Default: **4**<br/>
This setting control which type of debugging messages should be sent to the log file. Accepted values are:<br/>
* 0: Nothing
* 1: Fatal errors only
* 2: Critical errors
* 3: Warnings and errors
* 4: Everything (verbose)