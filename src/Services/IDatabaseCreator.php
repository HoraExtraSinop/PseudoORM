<?php

namespace PseudoORM\Services;

interface IDatabaseCreator
{
    
    /**
     *
     * @param string $entity Class name
     * @param boolean $includDropStatement True to append drop statement to the begging of script
     */
    public function scriptCreation($entity, $includDropStatement = false);
}
