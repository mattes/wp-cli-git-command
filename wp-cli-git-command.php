<?php
/**
 * Implements Git Command.
 */
class Git_Command extends WP_CLI_Command {


  /**
   * Initialize Git Repository with pre-commit hook for MySQL dumping.
   * 
   * ## EXAMPLES
   * 
   *     wp git init
   *
   */
  function init( $args, $assoc_args ) {
    
    // recursively find .git directory in $path
    // return false if no .git directory is found or $path if found
    function find_git_directory($path) {
      // remove trailing slash
      $path = rtrim($path, DIRECTORY_SEPARATOR);

      // is this path readable?
      // or did we reach the root path?
      if(!is_readable($path) || $path == DIRECTORY_SEPARATOR  || $path == '') {
        // no git repository found ...
        return false;
      }

      // look for a .git directory in current $path
      if(is_dir($path . DIRECTORY_SEPARATOR . '.git')) {
        // cool, found one. 
        return $path;
      } else {
        // no .git directory found. look in the parent directory of $path
        return find_git_directory(dirname($path));
      }
    }

    function run_init_git($wp_base_path) {
      $wp_base_path = rtrim($wp_base_path, DIRECTORY_SEPARATOR);
      chdir($wp_base_path);

      // run git init in $wp_base_path
      $exit_code = WP_CLI::launch('git init', false);
      if($exit_code > 0) {
        WP_CLI::error("Failed to run 'git init' in '$wp_base_path'.");
      }

      // do not run 'git add .' because the user might want to
      // add files to .gitignore before 

      // create/update .gitignore
      $ignores = '.DS_Store';
      $result = file_put_contents($ignores, 
        $wp_base_path . DIRECTORY_SEPARATOR . ".gitignore", 
        FILE_APPEND);
      if($result === false) {
        WP_CLI::warning("Failed to write .gitignore in '$wp_base_path'.");
      }

      WP_CLI::success("Created new Git Repository.");
      chdir(ABSPATH);
    }

    function create_pre_commit_hook($wp_base_path) {
      $wp_base_path = rtrim($wp_base_path, DIRECTORY_SEPARATOR);
      $hooks_path = $wp_base_path . DIRECTORY_SEPARATOR 
          . '.git' . DIRECTORY_SEPARATOR
          . 'hooks' . DIRECTORY_SEPARATOR;
      

      $hook_filename = $hooks_path . DIRECTORY_SEPARATOR 
                     . "pre-commit-mysql-dump";

      $content = "";

      // delete existing hook, if existing
      if(file_exists($hook_filename)) {
        if(!unlink($hook_filename)) {
          WP_CLI::error("$hook_filename already exists, and I can't overwrite");
        }
      }

      // create the file in .git/hooks
      $result = file_put_contents($hook_filename, $content);
      if($result === false) {
        WP_CLI::error("Failed to create '$hook_filename'.");
      }

      WP_CLI::success("Pre-commit '" . basename($hook_filename) . "' created.");
      
      link_pre_commit_hook($wp_base_path);
    }

    function link_pre_commit_hook($wp_base_path) {
      $wp_base_path = rtrim($wp_base_path, DIRECTORY_SEPARATOR);
      
    }



    // ===========================================================

    // ABSPATH == this WordPress base directory

    // is there a .git repository already?
    $git_directory = find_git_directory(ABSPATH);

    if(!$git_directory) {
      // no .git directory found in ABSPATH
      // ==================================
      run_init_git(ABSPATH);
      create_pre_commit_hook(ABSPATH);

    } else {

      // okay, there is a .git directory somewhere ...
      // =============================================

      if(rtrim($git_directory, DIRECTORY_SEPARATOR)
         != rtrim(ABSPATH, DIRECTORY_SEPARATOR)) {
        // some parent directory of ABSPATH has a .git directory
        // -----------------------------------------------------
        run_init_git(ABSPATH);
        create_pre_commit_hook(ABSPATH);
      } else {
        // this ABSPATH has a .git directory
        $hook_path = $git_directory . DIRECTORY_SEPARATOR 
                   . ".git" . DIRECTORY_SEPARATOR 
                   . "hooks" . DIRECTORY_SEPARATOR
                   . "pre-commit";

        if(file_exists($hook_path)) {
          // this .git directory in ABSPATH already as a pre-commit
          // ------------------------------------------------------
          create_pre_commit_hook(ABSPATH);
          WP_CLI::warning("Pre-commit hook $hook_path exists " 
                        . "and was modified by me.");
        } else {
          // this .git directory in ABSPATH has no pre-commit
          // ------------------------------------------------
          create_pre_commit_hook(ABSPATH);
        }
      }
    }
  }

}

WP_CLI::add_command( 'git', 'Git_Command' );