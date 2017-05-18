<?php
/*
 * @author Pedro Henrique
 * @date 06/05/2017
 */

class ClientesRecord extends TRecord
{
    const TABLENAME = "clientes";
    const PRIMARYKEY = "id";
    const IDPOLICY = "serial";

    public function __construct( $id = NULL )
    {
        parent::__construct( $id );

        parent::addAttribute( "nome" );
        parent::addAttribute( "cpf" );
        parent::addAttribute( "rg" );
        parent::addAttribute( "genero" );
        parent::addAttribute( "nascimento" );
        parent::addAttribute( "rua" );
        parent::addAttribute( "bairro" );
        parent::addAttribute( "cidade" );
        parent::addAttribute( "email" );
        parent::addAttribute( "dataalteracao" );
        parent::addAttribute( "usuarioalteracao" );
    }
}
