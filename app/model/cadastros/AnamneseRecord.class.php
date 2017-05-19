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
    function get_paciente_nome(){

        if (empty ($this->paciente)){
            $this->paciente = new PacienteRecord($this->paciente_id);
        }
        
        return $this->paciente->nome;

    }
}