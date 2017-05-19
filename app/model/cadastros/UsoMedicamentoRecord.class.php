<?php
class UsoMedicamentoRecord extends TRecord
{
    const TABLENAME = 'usomedicamento';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max';
    
    private $paciente;
    function get_paciente_nome(){

        if (empty ($this->paciente)){
            $this->paciente = new PacienteRecord($this->paciente_id);
        }
        
        return $this->paciente->nome;

    }
}
