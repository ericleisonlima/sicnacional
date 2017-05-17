<?php

//Composicao com paciente
class PesagemPacienteRecord extends TRecord
{
    const TABLENAME = 'pesagempaciente';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'max';

    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('peso');
        parent::addAttribute('datapesagem');
        parent::addAttribute('paciente_id');
    }
    
}