<?php

class ClientesRecord extends TRecord
{
    const TABLENAME = "clientes";
    const PRIMARYKEY = "id";
    const IDPOLICY = "serial";

    public function __construct( $id = NULL )
    {
        parent::__construct( $id );

        parent::addAttribute( "apelido" );
        parent::addAttribute( "nome" );
        parent::addAttribute( "cpf" );
        parent::addAttribute( "rg" );
        parent::addAttribute( "genero" );
        parent::addAttribute( "nascimento" );
        parent::addAttribute( "rua" );
        parent::addAttribute( "numero" );
        parent::addAttribute( "bairro" );
        parent::addAttribute( "cidade" );
        parent::addAttribute( "email" );
        parent::addAttribute( "dataalteracao" );
        parent::addAttribute( "usuarioalteracao" );
    }
}
