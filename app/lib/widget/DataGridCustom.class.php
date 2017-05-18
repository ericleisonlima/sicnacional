<?php

class DataGridCustom extends TDataGrid
{

    private function prepareAction(TAction $action, $object){
    	$fieldfk = $action->getFk();
        if (isset($fieldfk)) {
            if (!isset($object->$fieldfk)) {
                throw new Exception(AdiantiCoreTranslator::translate('FK ^1 not exists', $field));
            }
            $fk = isset($object->$fieldfk) ? $object->$fieldfk : NULL;
            $action->setParameter('fk', $fk);
        }
    }
}
