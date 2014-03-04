wp-cli-git-command
==================

WordPress Git helpers, like pre-commit hooks for automatic MySQL database dumps.


Installation
============

 * https://github.com/wp-cli/wp-cli/wiki/Community-Packages#wiki-installing-a-package-without-composer
 * or https://github.com/wp-cli/wp-cli/wiki/Community-Packages#wiki-installing-a-package


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