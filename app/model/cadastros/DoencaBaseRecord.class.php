<?php

class DoencaBaseRecord extends TRecord
{
    const TABLENAME = 'doencabase';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'max';

    private $cid;

    public function get_cid_nome()
    {
    	if ( empty( $this->cid ) ){
    		$this->cid = new CidRecord( $this->cid_id );
    	}

    	return $this->cid->nome;
    }
}