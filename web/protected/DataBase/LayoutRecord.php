<?php
class LayoutRecord extends TActiveRecord
{
    const TABLE = 'layouts';

    public $layout;
    public $titulo;
    public $corFundo;
    public $html;
    public $ativo;
    
    /**
     * @return TActiveRecord active record finder instance
     */
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>