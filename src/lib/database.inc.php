<?php
  require_once( DIR_LIB . '/template.inc.php' );
  require_once( DIR_LIB . '/factory.inc.php' );
  class Database {
    private $mConfig;
    private $mDb;
    public function __construct( $config ) {
      $this->mConfig = $config;
      if( isset( $this->mConfig[ 'mysql' ] ) ) {
        $c = $this->mConfig[ 'mysql' ];
        try {
          $this->mDb =
            new MySQLi(
              $c[ 'host' ],
              $c[ 'user' ],
              $c[ 'password' ],
              '',
              isset( $c[ 'port' ] ) ? $c[ 'port' ] : '3306' );
          if( !$this->mDb->select_db( $c[ 'database' ] ) ) {
            $this->createDb();
          }
          $this->mDb->multi_query( "SET NAMES 'UTF8'" );
          $this->flushResults();
        }
        catch( Exception $e ) {
          Factory::getLogger()->error( $e->getMessage() );
          exit();
        }
      }
    }
    public function createDb() {
      $tpl = new Template( DIR_CNF . '/db/create.tpl.sql' );
      $this->mDb->multi_query( $tpl->render( $this->mConfig[ 'mysql' ] ) );
      $this->flushResults();
    }
    public function insertRecord( $record ) {
      $record[ 'saksnr' ] = explode( '/', $record[ 'saksnr' ] );
      $record[ 'dokdato' ] = $this->isoDate( $record[ 'dokdato' ] );
      $record[ 'jourdato' ] = $this->isoDate( $record[ 'jourdato' ] );
      $record[ 'pubdato' ] = $this->isoDate( $record[ 'pubdato' ] );
      $sql = sprintf( "CALL insertRecord( '%s', '%s', '%s', %s, %s, '%s', '%s', '%s', '%s', '%s', '%s' )",
                            $this->mDb->escape_string( $record[ 'sakstittel' ] ),
                            $this->mDb->escape_string( $record[ 'dokumenttittel' ] ),
                            $this->mDb->escape_string( $record[ 'saksnr' ][ 0 ] ),
                            $this->mDb->escape_string( $record[ 'saksnr' ][ 1 ] ),
                            $this->mDb->escape_string( $record[ 'doknr' ] ),
                            $this->mDb->escape_string( $record[ 'virksomhet' ] ),
                            $this->mDb->escape_string( $record[ 'dokdato' ] ),
                            $this->mDb->escape_string( $record[ 'jourdato' ] ),
                            $this->mDb->escape_string( $record[ 'pubdato' ] ),
                            $this->mDb->escape_string( $record[ 'annenpart' ] ),
                            $this->mDb->escape_string( $record[ 'unntaksgrunnlag' ] ) );
      $this->mDb->multi_query( $sql );
      $this->flushResults();
    }
    public function isoDate( $dd_mm_yyyy ) {
      return sprintf( '%s-%s-%s',
                substr( $dd_mm_yyyy, 6, 4 ),
                substr( $dd_mm_yyyy, 3, 2 ),
                substr( $dd_mm_yyyy, 0, 2 ) );
    }
    private function getFirstResult() {
      $res = null;
      if( $this->mDb->more_results() ) {
        $res = $this->mDb->store_result();
      }
      return $res;
    }
    private function flushResults() {
      while( $this->mDb->more_results() ) {
        $this->mDb->next_result();
        if( $res = $this->mDb->store_result() ) {
          if( $res->errorno ) {
            Factory::getLogger()->error( $res->error );
          }
          $res->free();
        }
      }
    }
  }
?>
