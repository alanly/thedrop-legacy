# the drop.

This is the codebase behind [the drop](https://thedrop.pw/).

The Drop is a content repository site. Content is regularily added to a local directory (either manually or via automated processing) and the content is then indexed by this application and made available to registered users of the site.

### Working with the application.

The application is written upon the Laravel 4 web application framework, so the appropriate steps to setup a Laravel 4 application would apply here. This includes adding in the appropriate database server configuration and creating a "secret key".

Because this applicaton makes use of the [Cartalyst Sentry](https://github.com/cartalyst/sentry) authentication framework, you will also have to ensure that Sentry references the defined `User` model rather than its own included class. This can be configured under [Sentry's own configuration file](https://github.com/cartalyst/sentry/blob/master/src/config/config.php#L123).

In addition to the standard configuration, you will also have to define a directory containing all your media content for the application to index in the [`RepositoryFile`](https://github.com/alanly/thedrop/blob/master/app/models/RepositoryFile.php#L14) model. Another modification that should be made is changing the Trakt.TV API key to your own, also within the [`RepositoryFile`](https://github.com/alanly/thedrop/blob/master/app/models/RepositoryFile.php#L405) model. The centralization of these values under a single configuration file is on the "todo" list.

### Automated indexing.

The defined content directory is indexed by calling `php artisan thedrop:updateFileListing` from the console. This process can be automated via a cronjob. Removal of content should be performed from within the application as there currently is no implementation to determine deleted files.

On the "todo" list is an attempt to make use of Laravel's Filesystem events in order to further automate this process and make it a self-contained solution that's easier to manage and better performant.

### Contributing.

Feel free to issue pull requests for fixes, improvements, and even additional features that you think would be useful.

Security vulnerabilities and concerns can be addressed to me privately and directly via email at [me@alanly.ca](mailto:me@alanly.ca).

### Licensing.

The Drop is open-source and follows the [MIT license](http://opensource.org/licenses/MIT).