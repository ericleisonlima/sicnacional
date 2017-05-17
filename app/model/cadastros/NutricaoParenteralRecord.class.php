<?php
/**
 * System_program Active Record
 * @author  <your-name-here>
 */
class NutricaoParenteralRecord extends TRecord
{
    const TABLENAME = 'nutricaoparenteral';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('paciente_id');
        parent::addAttribute('datainicio');
        parent::addAttribute('datafim');
        parent::addAttribute('tipoparenteral');
        parent::addAttribute('tipoparenteraloutros');
        parent::addAttribute('totalcalorias');
        parent::addAttribute('percentualdiario');
        parent::addAttribute('valumenpt');
        parent::addAttribute('tempoinfusao');
        parent::addAttribute('frequencia');
        parent::addAttribute('acessovenosolp');
        parent::addAttribute('acessovenosolpqual');
        parent::addAttribute('numerodeacessovenoso');
        parent::addAttribute('apresntouinfeccaoacessovenoso');
        parent::addAttribute('vezesinfeccaoacessovenosso');
    }

    private $paciente;
    
    function get_paciente_nome()
    {
        //instancia saldoRecord
        //carrega na memoria a empresa de codigo $this->empresa_id
        if (empty ($this->paciente)){
            $this->paciente = new PacienteRecord($this->paciente_id);
        }
        //retorna o objeto instanciado
        return $this->paciente->nome;
    }
}
