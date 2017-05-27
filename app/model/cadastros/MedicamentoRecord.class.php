<?php

class MedicamentoRecord extends TRecord
{
    const TABLENAME = 'medicamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'max';


    private $tipomedicamento;
    
    function get_tipomedicamento_nome()
    {
        //instancia saldoRecord
        //carrega na memoria a empresa de codigo $this->empresa_id
        if (empty ($this->tipomedicamento)){
            $this->tipomedicamento = new TipoMedicamentoRecord($this->tipomedicamento_id);
        }
        //retorna o objeto instanciado
        return $this->tipomedicamento->nome;
    }
}