<?php
namespace PseudoORM\Entity;

class EntidadeBase
{
    /**
     * @Id
     * @Column(name='uid')
     */
    protected $uid;

    final public static function createFromForm($params)
    {
        foreach ($params as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = htmlspecialchars($value);
            }
        }
    }

    public function getClassShortName()
    {
        return (new \ReflectionClass($this))->getShortName();
    }  

    public function getClass()
    {
        return (new \ReflectionClass($this))->getName();
    } 
    
    
    public function setUID($uid)
    {
        $this->uid = $uid;
    }
    
    public function getUID()
    {
        return $this->uid;
    }
}
