Reverb
======

Extremely lightweight MVC pattern 'framework' for PHP. 

This MVC pattern implementation (I hesitate to call it a framework, as it is so lightweight) has only been tested on Apache, but it should work without modification on a server running NGinX as well. I have provided the Apache config required to get it working below, perhaps the NGinX equivalent will follow shortly :)


Requires the following lines in httpd.conf:

    <Directory {document root dir}>    
    RewriteEngine on
    RewriteRule /?html/(.*)/(.*) reverb/gateway/gateway_html.php?_component=$1&_action=$2 [NC,QSA]
    RewriteRule /?html/(.*) reverb/gateway/gateway_html.php?_component=$1&_action=Index [NC,QSA]
    </Directory>

Overview:
At the top level, there are two directories. 'reverb' is where the workings live, and 'site' is for any site/project-specific code (models/views/controllers, configuration, etc).

reverb Directory:
In here you can find the gateway implementations, which recieve the requests, forward them off to the relevent controller component, construct output/views and send the response to the client.
This is also where the database layer is located. reverb/lib/DbInterface.php is a mysql_i wrapper that exposes a straightforward API for creating and executing MySQL queries.


site Directory:
There is a very simple config file here, as well as a hellow world page.
