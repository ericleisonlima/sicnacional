<?php

class PacienteRecord extends TRecord
{
    const TABLENAME = 'paciente';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}

    private $causa_obito;
    public function get_municipio()
    {
          if (empty ($this->municipio)) {
            $this->nome_municipio = new MunicipioRecord($this->id);
        }
        return $this->nome_municipio->nome_municipio;
    }

    function get_causa_obito_nome(){

        if (empty ($this->causa_obito)){
            $this->causa_obito = new CausaObitoRecord($this->causa_obito_id);
        }
        
        return $this->causa_obito->descricao;

    }

}
    


