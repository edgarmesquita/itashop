<?php
class GrupoRecord extends TActiveRecord
{
    const TABLE = 'grupos';

    public $grupo;
    public $nome;

    /**
     * @return TActiveRecord active record finder instance
     */
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>