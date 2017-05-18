<?php

class PacienteRecord extends TRecord
{
    const TABLENAME = 'paciente';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}

    public function get_municipio()
    {
    	  if (empty ($this->municipio)) {
            $this->nome_municipio = new MunicipioRecord($this->id);
        }
        return $this->nome_municipio->nome_municipio;
    }

}
    


