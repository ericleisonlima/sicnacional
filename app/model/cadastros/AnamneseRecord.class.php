<?php
/**
 * AnamneseRecord Active Record
 * @author  Ericleison Lima
 */
class AnamneseRecord extends TRecord
{
    const TABLENAME = 'anamnese';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
}
?>