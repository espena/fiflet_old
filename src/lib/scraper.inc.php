<?php
  require_once( DIR_LIB . '/factory.inc.php' );
  require_once( DIR_LIB . '/configuration_file.inc.php' );
  define( 'URLFMT_CHRONOLOGICAL_LOOKUP', 'https://oep.no/search/result.html?contentSupplier=%s&searchType=chronological&dateType=publicationDate&hitsPerPage=%s&sortField=publicationdate&sortOrder=desc&Search=Search+in+records&start=%s&lang=no' );
  define( 'MAX_OFFSET', 100000 );
  class Scraper {
    private $mSuppliers;
    private $mSuppliersToMonitor;
    public function __construct() {
      $config = ConfigurationFile::parse();
      $this->mSuppliers = Factory::getSuppliers( FALSE );
      $this->mSuppliersToMonitor = explode( ',', $config[ 'fiflet' ][ 'suppliers_to_monitor' ] );
    }
    public function run() {
      libxml_use_internal_errors( true );
      $config = ConfigurationFile::parse();
      $db = Factory::getDatabase();
      $dNow = time();
      $dBrk = time() - ( intval( $config[ 'fiflet' ][ 'days_back' ] ) * 86400 );
      foreach( $this->mSuppliersToMonitor as $supplierId ) {
        $supplierId = intval( $supplierId );
        printf( "Scraping supplier ID %s: %s\n", $supplierId, $this->mSuppliers[ $supplierId ] );
        $pageSize = isset( $config[ 'fiflet' ][ 'pagesize' ] ) ? intval( $config[ 'fiflet' ][ 'pagesize' ] ) : 100;
        printf( "%s hits per request, starting at offset 0\n", $pageSize );
        for( $offset = 0; $offset < MAX_OFFSET; $offset += $pageSize ) {
          $url = sprintf( URLFMT_CHRONOLOGICAL_LOOKUP, $supplierId, $pageSize, $offset );
          $html = file_get_contents( $url );
          $domDoc = new DOMDocument();
          $domDoc->loadHTML( $html );
          $domXPath = new DOMXPath( $domDoc );
          $tableRows = $domXPath->query( '//*[@id="content"]/tbody/tr' );
          for( $i = 0; $i < $tableRows->length; $i++  ) {
            $tableRow = $tableRows->item( $i );
            $record = array(
              'sakstittel'      => trim( $tableRow->childNodes->item(  0 )->textContent ),
              'dokumenttittel'  => trim( $tableRow->childNodes->item(  2 )->textContent ),
              'saksnr'          => trim( $tableRow->childNodes->item(  4 )->textContent ),
              'doknr'           => trim( $tableRow->childNodes->item(  6 )->textContent ),
              'virksomhet'      => trim( $tableRow->childNodes->item(  8 )->textContent ),
              'dokdato'         => trim( $tableRow->childNodes->item( 10 )->textContent ),
              'jourdato'        => trim( $tableRow->childNodes->item( 12 )->textContent ),
              'pubdato'         => trim( $tableRow->childNodes->item( 14 )->textContent ),
              'annenpart'       => trim( $tableRow->childNodes->item( 16 )->textContent ),
              'unntaksgrunnlag' => trim( $tableRow->childNodes->item( 18 )->textContent ) );
            $dPub = strtotime( $record[ 'pubdato' ] );
            if( $dPub < $dBrk ) {
              printf( "Ending at %s documents\n", $offset + $i );
              $offset = MAX_OFFSET;
              break;
            }
            else {
              $db->insertRecord( $record );
            }
          }
        }
      }
    }
  }
?>
