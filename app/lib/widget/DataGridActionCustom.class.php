<?php

class DataGridActionCustom extends TDataGridAction
{
    
	private $fk;


    public function setFk($field)
    {
        $this->fk = $field;
    }

    public function getFk()
    {
        return $this->fk;
    }
}
