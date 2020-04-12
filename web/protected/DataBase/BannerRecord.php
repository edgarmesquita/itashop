<?php

class BannerRecord extends TActiveRecord
{
    const TABLE = 'banners';

    public $banner;
    public $nome;
    public $tipo;
    public $arquivo;
    public $html;
    public static $RELATIONS = array
        (
        'arquivo' => array(self::BELONGS_TO, 'ArquivoRecord', 'arquivo')
    );

    /**
     * @return TActiveRecord active record finder instance
     */
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }

}

?>