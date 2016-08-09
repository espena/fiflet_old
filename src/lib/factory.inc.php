<?php

  require_once( DIR_LIB . '/app_base.inc.php' );
  require_once( DIR_LIB . '/app_cli.inc.php' );
  require_once( DIR_LIB . '/app_web.inc.php' );

  class Factory {
    private static $mApp;
    public static function getApp() {
      if( empty( self::$mApp ) ) {
        self::$mApp = new AppBase();
        if( PHP_SAPI == 'cli' ) {
          self::$mApp = new AppCli( self::$mApp );
        }
        else {
          self::$mApp = new AppWeb( self::$mApp );
        }
      }
      return self::$mApp;
    }
    public static function releaseApp( &$app ) {
      if( isset( $app ) && $app === self::$mApp ) {
        self::$mApp->doPostOperations();
        self::$mApp = null;
      }
      $app = null;
    }
  }
?>
