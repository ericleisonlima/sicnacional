<?php
/**
 * <e-code/>
 * Robson Daniel
 */
class TipoNutricaoRecord extends TRecord
{
  const TABLENAME = 'tiponutricao';
  const PRIMARYKEY = 'id';
  const IDPOLICY = 'serial';

  function __construct( $id = null )
  {
    parent::__construct( $id );
    parent::addAttribute( 'nome' );
  }
}

?>
