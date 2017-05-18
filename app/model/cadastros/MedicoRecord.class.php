<?php

class MedicoRecord extends TRecord
{
    const TABLENAME = 'medico';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'max';


    private $municipio;
    
    function get_municipio_nome()
    {
        //instancia saldoRecord
        //carrega na memoria a empresa de codigo $this->empresa_id
        if (empty ($this->municipio)){
            $this->municipio = new MunicipioRecord($this->municipio_id);
        }
        //retorna o objeto instanciado
        return $this->municipio->nome;
    }
}