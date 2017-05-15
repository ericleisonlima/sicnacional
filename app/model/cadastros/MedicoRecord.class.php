

<?php
class MedicoRecord extends TRecord
{
    const TABLENAME = 'medico';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
}
?>