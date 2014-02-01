jQuery Integration View Helper Module
=========

*this module is part of Yima Application Framework*

The jQuery() view helper simplifies setup of your jQuery environment in your application.
All jQuery view helpers put their javascript code onto this stack. It acts as a collector for js scripts in your application.

Why we need this?
------------
We working on modular system and each module can plugged into application and played his role,
if module need some jQuery script in view then we have to use up jquery library,
how to know that library not attached before, or which version from which address attached ?

In other hand we may used another js library like mootools in theme layout of site, and we need to
use no conflict mode for jQuery scripts. Are we must edit whole modules script for this?

Also we have a collector of whole jQuery scripts, you can do more if thinking more.

Usage
------------

Basic usage in view layout:
 ```php
    <?php
    echo $this->jQuery()
        ->setNoConflict()
        ->setNoConflictHandler('$j')
        ->appendScript('
            $(document).ready(function() {
                console.log($.fn);
            });

            (function ($) {
                var SlickEditor = {
                    TextCellEditor: function (args) {
                    },
                    LongTextCellEditor: function (args) {
                    }
                };
                // $ inside immediately functions not replaced with noConflict handler
                $.extend(window, SlickEditor);
            })(jQuery);
        ');
    ?>
 ```
 this will output:
 ```html
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>

    <script type="text/javascript">var $j = jQuery.noConflict();</script>
    <script type="text/javascript">
        $j(document).ready(function() {
            console.log($j.fn);
        });

        (function ($) {
            var SlickEditor = {
                TextCellEditor: function (args) {
                },
                LongTextCellEditor: function (args) {
                }
            };
            // $ inside immediately functions not replaced with noConflict handler
            $.extend(window, SlickEditor);
        })(jQuery);
    </script>
 ```

Configuration
-----------
```php
    return array(
        'yima-jquery' => array(
            /**
             * Each Delivery Resolve to a jQuery version library address
             *
             * foreach deliveries key we must have a service in serviceManager
             * with name 'YimaJquery\Deliveries\[ServiceName]' in example for key 'service-name'
             *
             */
            'deliveries'       => array(
                'cdn',
                // or with construct options
                /*
                'cdn' => array(
                    'cdn-base'      => '//cdnjs.cloudflare.com/ajax/libs',
                    'cdn-subfolder' => 'jquery/',
                    'cdn-file-path' => '/jquery.min.js',
                ),
                */
            ),

            // decorator class or registered service
            // decorators get container of scripts and render as html or whatever else
            'decorator' => 'yimaJquery\Decorator\DefaultDecorator'
        ),
    );
```

Instruction
-----------
No more instruction yet! explore codes see comments and the way it works.


Installation
-----------

Composer installation:

require ```rayamedia/yima-jquery``` in your ```composer.json```

Or clone to modules folder

Enable module in application config


## Support ##
To report bugs or request features, please visit the [Issue Tracker](https://github.com/RayaMedia/yimaJquery/issues).

* Please feel free to contribute with new issues, requests and code fixes or new features. *
