wp-cli-git-command
==================

WordPress Git helpers, like pre-commit hooks for automatic MySQL database dumps.


## Installation
... as described here: https://github.com/wp-cli/wp-cli/wiki/Community-Packages#installing-community-packages-manually

```bash
# in your working directory containing the composer.json file
composer config repositories.wp-cli composer http://wp-cli.org/package-index/
composer require oxford-themes/wp-cli-git-command=dev-master
```

## Usage
```bash
# in your WordPress directory
wp git init

# do some changes ...

git commit -am "i updated xyz" # creates .db/mysql_dump.sql

# reset the database at any time
# since .db/mysql_dump.sql is checked into your version control, 
# you can now easily keep track of databases changes as well.
wp db import .db/mysql_dump.sql
```


## Developer Note
I locally develop this plugin by setting a symlink. YOU don't have to do this.

```
ln -s $(pwd)/wp-cli-git-command.php .../embed/local/opt/wp-cli/php/commands/wp-cli-git-command.php
```