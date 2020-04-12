<?php

class ConfiguracaoRecord extends TActiveRecord
{
    const TABLE = 'configuracoes';

    public $configuracao;
    public $emailRetornoTexto;
    public $emailRetorno;
    public $metatagKeywords;
    public $metatagDescription;
    public $popup;
    public $popupLargura;
    public $popupAltura;
    public $popupX;
    public $popupY;
    public static $RELATIONS = array
        (
        'popup' => array(self::BELONGS_TO, 'BannerRecord', 'popup')
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