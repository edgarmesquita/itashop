<?php
class CampanhaRecord extends TActiveRecord
{
    const TABLE = 'campanhas';

    public $campanha;
    public $titulo;
    public $layout;
    public $criadoPor;
    public $criadoEm;

    public static $RELATIONS = array
    (
        'layout' => array(self::BELONGS_TO, 'LayoutRecord', 'layout'),
        'criadoPor' => array(self::BELONGS_TO, 'UsuarioRecord', 'criadoPor')
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