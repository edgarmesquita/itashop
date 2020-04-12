<?php
Prado::using("Application.Commom");

class MasterAdmin extends TTemplateControl
{
    public $configurations;

    public function onLoad($param)
    {
        parent::onLoad($param);
        $this->configurations = ConfiguracaoRecord::finder()->withPopup()->findByPk(1);
        $this->lblNome->Text = "Bem vindo <strong>".$this->User->getName()."</strong>";

        if (! $this->Page->IsPostBack)
        {
            $criteria = new TActiveRecordCriteria;
            $criteria->Condition = "aplicacao = 2 AND menu = 1 AND acaoPai IS NULL";
            $criteria->OrdersBy['posicao'] = 'asc';

            $this->rptMenu->DataSource = AcaoRecord::finder()->withSubAcoes()->findAll($criteria);
            $this->rptMenu->dataBind();
        }
    }

    protected function repeaterDataBound($sender, $param)
    {
        $item = $param->Item;
        if($item->ItemType==='Item' || $item->ItemType==='AlternatingItem')
        {
            $item->ltMenu->Text = $this->writeMenu($item->DataItem);;
        }
    }

    private function writeMenu(&$acao, $level=0)
    {
        $html = '<li class="itemLevel'.$level;
        $page = Commom::getQueryStringValue($acao->url, "page");

        if(substr($this->Request['page'], -4, 4) == "Form")
            $page = str_replace("List", "Form", $page);

        if($this->Request['page'] == $page)
            $html .= ' selected';

        $html .= '"';

        $isFilhos = count($acao->subAcoes) > 0;

        $html .= '><a href="'.$acao->url.'"'.($isFilhos?' class="MenuBarItemSubmenu"':'').'><span>'.$acao->nome.'</span></a>';
        if($isFilhos)
        {
            $html .= '<ul>';
            foreach($acao->subAcoes  as &$subAcao)
                $html .= $this->writeMenu($subAcao, $level+1);
            $html .= '</ul>';
        }

        $html .= '</li>';
        return $html;
    }
    /**
     * Logs out a user.
     * This method responds to the "logout" button's OnClick event.
     * @param mixed event sender
     * @param mixed event parameter
     */
    public function logoutButtonClicked($sender,$param)
    {
        $this->Application->getModule('auth')->logout();
        $url=$this->Service->constructUrl($this->Service->DefaultPage);
        $this->Response->redirect($url);
    }
}
?>