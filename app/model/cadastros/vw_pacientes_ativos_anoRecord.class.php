<?php

class vw_pacientes_ativos_anoRecord extends TRecord
{
    const TABLENAME = 'vw_pacientes_ativos_ano';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    


}
