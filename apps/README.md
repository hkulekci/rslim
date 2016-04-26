# Example folder organization

```
/apps
    /your_app_name
        /controllers
            controller_name.action_name.php
        /templates
            /controller_name
                action_name.html
            _main_template_file_name.html
        /webroot
            index.php
        app_config.php
/lib  # run composer require --prefer-dist reformo/rslim "^1.0" command in here.
      # Rest of the folders and files will be created automatically. Just added sample folders
      # After composer installed the packages you can run the command  'cp -rf vendor/reformo/rslim/apps ../apps' here
      # to generate apps folder mentioned above automatically
    /vendor
        autoload.php
        /composer
        /reformo
        /slim
        /twig
    composer.json
    composer.lock

```

