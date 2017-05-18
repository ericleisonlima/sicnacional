<?php

class MedicamentoRecord extends TRecord
{
    const TABLENAME = 'medico';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'max';


    private $tipomedicamento;
    
    function get_tipomedicamento_nome()
    {
        //instancia saldoRecord
        //carrega na memoria a empresa de codigo $this->empresa_id
        if (empty ($this->tipomedicamento)){
            $this->tipomedicamento = new tipomedicamentoRecord($this->tipomedicamento_id);
        }
        //retorna o objeto instanciado
        return $this->tipomedicamento->nome;
    }
}