<?php
class EstabelecimentoRecord extends TRecord
{
    const TABLENAME = 'estabelecimento';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    private $nome_municipio;




    public function get_municipio()
    {
    	  if (empty ($this->municipio)) {
            $this->nome_municipio = new MunicipioRecord($this->id);
        }
        return $this->nome_municipio->nome_municipio;
    }

}



?>