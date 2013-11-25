wp-cli-git-command
==================

WordPress Git helpers, like pre-commit hooks for automatic MySQL database dumps.


Installation
============

See https://github.com/wp-cli/wp-cli/wiki/Community-Packages#installing-community-packages-manually
for more detailed installation instructions.

```bash
# find wp-cli's composer.json file and change to that directory
# examples:
# cd ~/.wp-cli (default installation directory)
# cd /usr/local/opt/wp-cli (when installed with homebrew)

# add package index if not done yet
composer config repositories.wp-cli composer http://wp-cli.org/package-index/

# install wp-cli-git-command
composer require mattes/wp-cli-git-command=dev-master
```


Usage
=====

```bash
# in your WordPress directory
wp git init

# do some changes to files

git commit -am "i updated xyz" # creates .db/mysql_dump.sql

# reset the database at any time
# since .db/mysql_dump.sql is checked into your version control, 
# you can now easily keep track of databases changes as well.
wp db import .db/mysql_dump.sql
```

--------------

## Developer Note
I locally develop this plugin by setting a symlink. YOU don't have to do this.

```
ln -s $(pwd)/wp-cli-git-command.php /usr/local/opt/wp-cli/php/commands/wp-cli-git-command.php
```