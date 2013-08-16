<?php
/**
 * Implements git command.
 */
class Git_Command extends WP_CLI_Command {

  /**
   * Prints a greeting.
   * 
   * ## OPTIONS
   * 
   * <name>
   * : The name of the person to greet.
   * 
   * ## EXAMPLES
   * 
   *     wp hello Newman
   *
   * @synopsis <name>
   */
  function init( $args, $assoc_args ) {
    list( $name ) = $args;

    // Print a success message
    WP_CLI::success( "Hello, $name!" );
  }
}

WP_CLI::add_command( 'git', 'Git_Command' );