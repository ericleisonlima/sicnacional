<?php

class NutricaoEnteralRecord extends TRecord
{
    const TABLENAME = 'nutricaoenteral';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}

    private $paciente;
    private $tiponutricao;
    private $administracaonutricao;

    function get_paciente_nome(){

        if (empty ($this->paciente)){
            $this->paciente = new PacienteRecord($this->paciente_id);
        }
        
        return $this->paciente->nome;

    }

    function get_administracao_nutricao_nome(){

        if (empty ($this->administracaonutricao)){
            $this->administracaonutricao = new AdministraNutricaoRecord($this->administracaonutricao_id);
        }
        
        return $this->administracaonutricao->nome;

    }
    function get_tipo_nutricao_nome(){

        if (empty ($this->tiponutricao)){
            $this->tiponutricao = new TipoNutricaoRecord($this->tiponutricao_id);
        }
        
        return $this->tiponutricao->nome;

    }


}
