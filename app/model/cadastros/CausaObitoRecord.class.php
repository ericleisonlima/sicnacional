<?php

class CausaObitoRecord extends TRecord
{
    const TABLENAME = "causa_obito";
    const PRIMARYKEY = "id";
    const IDPOLICY = "serial";

    public function __construct( $id = NULL )
    {
        parent::__construct( $id );

        parent::addAttribute( "id" );
        parent::addAttribute( "descricao" );
    }
}
