<?php

namespace PseudoORM\Services;

use PseudoORM\Services\IDatabaseCreator;

use Addendum\ReflectionAnnotatedClass;

class PostgreSQLDatabaseCreator implements IDatabaseCreator
{
    
    protected $tableName;
    
    /**
     * {@inheritDoc}
     */
    final public function scriptCreation($entity, $showDropStatment = false)
    {
        $classe = new ReflectionAnnotatedClass($entity);
        $this->tableName = strtolower($classe->getShortName());
        if (!$classe->hasAnnotation('Table') && $classe->getAnnotation('Table') != '') {
            $this->tableName = strtolower($classe->getAnnotation('Table')->value);
        }
        
        $tabela = $this->tableName;
        
        $fields = $this->generateFields($classe->getProperties());
    
        $script = '';
    
        if ($showDropStatment == true) {
            $script .= "DROP TABLE IF EXISTS ".SCHEMA.$tabela."; \n";
        }
        
        $script .= "CREATE TABLE ".SCHEMA.$tabela ." ( \n";
        $uid;
    
        // TODO extract to method
        foreach ($fields as $key => $value) {
            $fkMapStatement;
            if (isset($value['joinTable'])) {
                $fkMapStatement = "\tCONSTRAINT ".$tabela."_".$value['joinTable']."_fk FOREIGN KEY($key)\n";
                $fkMapStatement .= "\t\tREFERENCES ".SCHEMA.$value['joinTable']."($value[joinColumn]) MATCH SIMPLE\n";
                $fkMapStatement .= "\t\tON UPDATE NO ACTION ON DELETE NO ACTION,\n";
                $script .= "\t". $key . " integer, \n";
            } elseif ($key == 'uid') {
                $script .= "\t". $key . " serial NOT NULL, \n";
                $uid = $key;
            } else {
                // TODO Refactor to automatically detect the property's type
                $script .= "\t". $key . " " . ($value['type'] == 'integer' ? 'integer' : ($value['type'] == 'timestamp' ? 'timestamp' : 'character varying')) . ", \n";
            }
        }
        $script .= @$fkMapStatement;
        $script .= "\tCONSTRAINT ".$tabela."_pk PRIMARY KEY (".$uid.") \n";
        $script .= " );";
        
        return $script;
    }
    
    
    private function generateFields($propriedades)
    {
        foreach ($propriedades as $propriedade) {
            if ($propriedade->hasAnnotation('Column')) {
                $key = $propriedade->getAnnotation('Column')->name;
                $params = (array) $propriedade->getAnnotation('Column');
                foreach ($params as $chave => $valor) {
                    $fields[$key][$chave] = $valor;
                }
            }
            if ($propriedade->hasAnnotation('Join')) {
                $params = (array) $propriedade->getAnnotation('Join');
                foreach ($params as $chave => $valor) {
                    $fields[$key][$chave] = $valor;
                }
            }
        }
        return $fields;
    }
}
