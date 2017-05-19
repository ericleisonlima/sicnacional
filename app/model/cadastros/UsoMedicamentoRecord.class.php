<?php
class UsoMedicamentoRecord extends TRecord
{
    const TABLENAME = 'usomedicamento';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max';
    
    private $paciente;
    private $medicamento;
    private $administracao;
    function get_paciente_nome(){

        if (empty ($this->paciente)){
            $this->paciente = new PacienteRecord($this->paciente_id);
        }
        
        return $this->paciente->nome;

    }
    function get_medicamento_nome(){

        if (empty ($this->medicamento)){
            $this->medicamento = new MedicamentoRecord($this->medicamento_id);
        }
        
        return $this->medicamento->nome;

    }
    function get_administracao_nome(){

        if (empty ($this->administracao)){
            $this->administracao = new TipoAdministracaoMedicamentoRecord($this->tipoadministracaomedicamento_id);
        }
        
        return $this->administracao->descricao;

    }
}
