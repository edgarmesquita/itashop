<?php
class FaqRecord extends TActiveRecord
{
    const TABLE = 'faq';

    public $faq;
    public $pergunta;
    public $resposta;
    public $posicao;

    /**
     * @return TActiveRecord active record finder instance
     */
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }

}
?>