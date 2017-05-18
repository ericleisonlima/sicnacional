<?php

class HistoricoMedicoPacienteRecord extends TRecord {
    
    const TABLENAME = "historico_medico_paciente";
    const PRIMARYKEY = "id";
    const IDPOLICY = "serial";

    private $medico;
    private $estabelecimento;
    private $paciente;


    function get_medico_nome()
    {
        //instancia saldoRecord
        //carrega na memoria a empresa de codigo $this->empresa_id
        if (empty ($this->medico)){
            $this->medico = new MedicoRecord($this->medico_id);
        }
        if (empty ($this->estabelecimento)){
            $this->estabelecimento = new EstabelecimentoRecord($this->estabelecimento_id);
        }
        //retorna o objeto instanciado
        return $this->medico->nome .' '. $this->estabelecimento->nome;
    }

    function get_paciente_nome()
    {
        //instancia saldoRecord
        //carrega na memoria a empresa de codigo $this->empresa_id
        if (empty ($this->paciente)){
            $this->paciente = new pacienteRecord($this->paciente_id);
        }
        //retorna o objeto instanciado
        return $this->paciente->nome;
    }

}