<?php
class EmailRecord extends TActiveRecord
{
    const TABLE = 'emails';
    
    public $emailId;
    public $nome;
    public $email;
    public $ativo;

    public function table()
    {
        return 'emails';
    }

    /**
     * @return TActiveRecord active record finder instance
     */
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>