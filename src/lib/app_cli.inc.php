<?php

  require_once( DIR_LIB . '/i_application.inc.php' );

  class AppCli implements IApplication {
    private $mApp;
    public function __construct( $app ) {
      $this->mApp = $app;
    }
    public function doPreOperations() {
      $this->mApp->doPreOperations();
    }
    public function tpl( $idt ) {
      switch( $idt ) {
        case 'main':
          $this->mApp->tpl( 'cli_main' );
          break;
        default:
          $this->mApp->tpl( $idt );
      }
    }
    public function doPostOperations() {
      $this->mApp->doPostOperations();
    }
  }

?>
