// IMPORTANT NOTICE
//
// All of the source-code provided in this package is copyrighted and licensed according to the
// license purchased on Binpress. Any usage or distribution of this code outside the bounds of the
// provided license is prohibited.

// This package is compatible with TinyMCE 4.x. For TinyMCE 3.x, please use the package lioniteimages-tinymce3.zip and 
// refer to the README file there

Getting started
===============

Extract the plug-in zip package into your TinyMCE /plugins directory. 
It should create a /lioniteimages directory - if not, move the files manually into 
that directory (/plugins/lioniteimages).

Add the image manager to the TinyMCE configuration:
---------------------------------------------------

The plug-in name is 'lioniteimages' and needs to be added to the plug-in list:

    tinyMCE.init({
	    ...
	    plugins : "lioniteimages", // And any other plugins you might have
	    toolbar : "bold italic underline strikethrough | lioniteimages", // Adding the button to the interface
        ...
    });

Image manager configuration
---------------------------

The image manager is packaged with a PHP connector which needs to be configured for 
the directory structure of your application. If you are using a different server-side language, 
you will need to port the connector to your language to use the plug-in.

To change the configuration, edit the config.php file that's inside /lioniteimages/connector/php:

    // The URL that points to the upload folder on your site. 
    // Can be a relative or full URL (include the protocol and domain)
    $imageURL = '/demos/http/uploads';

    // Full upload system path. Make sure you have write permissions to this folder
    $uploadPath = '/var/www/demos/http/uploads';

The image URL value would be used in the HTML 'src' attribute of inserted images, 
so change it accordingly. Depending on your application, you might separate directories 
for different users. You can add a dynamic part to the directory names based on user identifier. 

For example:

    $userId = Auth::getId();
    $imageURL = '/uploads/' . $userId;
    $uploadPath = '/var/www/project/public/uploads/' . $userId;

Authentication
--------------

In some cases you want to limit access to image management. Implement your authentication logic 
inside the isAuth() function.

    function isAuth() {
        //Perform your own authrization to make sure user is allowed to upload
        return true; //Return true when authenticated, false when denied
    }
