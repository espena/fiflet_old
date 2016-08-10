DROP DATABASE IF EXISTS %%database%%;
CREATE DATABASE %%database%% DEFAULT CHARSET 'utf8';
USE %%database%%;
CREATE TABLE journal (
  id_journal BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  sakstittel TEXT,
  doktittel TEXT,
  saksaar CHAR( 4 ),
  saksnr INT UNSIGNED,
  doknr INT UNSIGNED,
  virksomhet VARCHAR( 255 ),
  dokdato DATE,
  jourdato DATE,
  pubdato DATE,
  annenpart TEXT,
  unntaksgrunnlag TEXT,
  UNIQUE INDEX uidx_dokidentitet ( virksomhet, saksaar, saksnr, doknr )
 );
CREATE PROCEDURE insertRecord( _sakstittel TEXT,
                              _doktittel TEXT,
                              _saksaar CHAR( 4 ),
                              _saksnr INT UNSIGNED,
                              _doknr INT UNSIGNED,
                              _virksomhet VARCHAR( 255 ),
                              _dokdato DATE,
                              _jourdato DATE,
                              _pubdato DATE,
                              _annenpart TEXT,
                              _unntaksgrunnlag TEXT )
BEGIN
   REPLACE INTO
       journal (
         sakstittel,
         doktittel,
         saksaar,
         saksnr,
         doknr,
         virksomhet,
         dokdato,
         jourdato,
         pubdato,
         annenpart,
         unntaksgrunnlag )
   VALUES (
     _sakstittel,
     _doktittel,
     _saksaar,
     _saksnr,
     _doknr,
     _virksomhet,
     _dokdato,
     _jourdato,
     _pubdato,
     _annenpart,
     _unntaksgrunnlag );
END;
