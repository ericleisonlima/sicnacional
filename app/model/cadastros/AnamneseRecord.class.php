<?php
/**
 * AnamneseRecord Active Record
 * @author  Ericleison Lima
 */
class AnamneseRecord extends TRecord
{
    const TABLENAME = 'anamnese';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
     private $paciente; 
     private $estabelecimento;

    function get_paciente_nome(){

        if (empty ($this->paciente)){
            $this->paciente = new PacienteRecord($this->paciente_id);
        }
        
        return $this->paciente->nome;

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