<?php
class ExamePacienteRecord extends TRecord{
    const TABLENAME = "examepaciente";
    const PRIMARYKEY = "id";
    const IDPOLICY = "serial";

    private $paciente;
    private $exame;

    function get_paciente_nome(){

        if (empty ($this->paciente)){
            $this->paciente = new PacienteRecord($this->paciente_id);
        }
        
        return $this->paciente->nome;

    }
    function get_exame_nome(){

        if (empty ($this->exame)){
            $this->exame = new TipoExameRecord($this->tipoexame_id);
        }
        
        return $this->exame->nome;

    }
}
