<?php

class EstabelecimentoMedicoRecord extends TRecord {
    
    const TABLENAME = "estabelecimento_medico";
    const PRIMARYKEY = "id";
    const IDPOLICY = "serial";

    private $medico;
    private $estabelecimento;


    function get_medico_nome()
    {
        //instancia saldoRecord
        //carrega na memoria a empresa de codigo $this->empresa_id
        if (empty ($this->medico)){
            $this->medico = new MedicoRecord($this->medico_id);
        }
        //retorna o objeto instanciado
        return $this->medico->nome;
    }

    function get_estabelecimento_nome()
    {
        //instancia saldoRecord
        //carrega na memoria a empresa de codigo $this->empresa_id
        if (empty ($this->estabelecimento)){
            $this->estabelecimento = new EstabelecimentoRecord($this->estabelecimento_id);
        }
        //retorna o objeto instanciado
        return $this->estabelecimento->nome;
    }

}