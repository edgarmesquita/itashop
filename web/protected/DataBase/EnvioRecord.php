<?php
class EnvioRecord extends TActiveRecord
{
    const TABLE = 'envios';

    public $envio;
    public $campanha;
    public $email;
    public $enviado;
    
    public static $RELATIONS=array
    (
        'campanha' => array(self::BELONGS_TO, 'CampanhaRecord', 'campanha'),
        'email' => array(self::BELONGS_TO, 'EmailRecord', 'email')
    );

    public function table()
    {
        return self::TABLE;
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