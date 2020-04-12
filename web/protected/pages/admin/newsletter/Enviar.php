<?php
Prado::using("Application.Email");
Prado::using("System.Web.UI.ActiveControls.*");

class Enviar extends TPage
{
    public function onLoad($param)
    {
        parent::onLoad($param);

        $this->loadLayouts();
    }

    private function loadLayouts()
    {
        $criteria = new TActiveRecordCriteria;
        $criteria->Condition = 'ativo = 1';
        $criteria->OrdersBy['titulo'] = 'asc';

        $datasource = array("0" => "« Selecione »");
        $layouts = LayoutRecord::finder()->findAll($criteria);

        foreach($layouts as $l)
            $datasource[$l->layout] = $l->titulo;

        $this->ddlLayout->DataSource = $datasource;
        $this->ddlLayout->dataBind();
    }

    /**
     *
     * @param TActiveButton $sender
     * @param <type> $param
     */
    public function enviarClick($sender, $param)
    {
        if ($this->IsValid) // when all validations succeed
        {
            $layout = LayoutRecord::finder()->findByPk($this->ddlLayout->SelectedValue);

            $html = "<html><head><title>".$layout->titulo."</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"></head>";
            $html .= "<body bgcolor=\"".$layout->corFundo."\">";
            $html .= $layout->html;
            $html .= "</body></html>";
            
            $emails = EmailRecord::finder()->findAll();
            
            if(count($emails) > 0)
            {
                $campanha = new CampanhaRecord();
                $campanha->criadoEm = date('Y-m-d H:i:s');
                $campanha->criadoPor = $_SESSION['usuario']->usuario;
                $campanha->titulo = $this->fAssunto->Text;
                $campanha->layout = $this->ddlLayout->SelectedValue;
                $campanha->save();

                $this->hdfCampanha->Value = $campanha->campanha;
                //$sender->getActiveControl()->setCallbackParameter($campanha->campanha);

                foreach($emails as $email)
                {
                    $mail = new Email($email->email, "Clube Itashop", $this->Master->configurations->emailRetorno, $this->fAssunto->Text, $html);

                    $envio = new EnvioRecord();
                    $envio->campanha = $campanha->campanha;
                    $envio->email = $email->emailId;

                    if($mail->send())
                    {
                        $envio->enviado = 1;
                    }
                    else
                    {
                        $envio->enviado = 0;
                    }
                    $envio->save();
                }
            }
            else $this->getClientScript()->registerEndScript("error", "alert('Nenhum email encontrado.');");
        }
    }

    /**
     *
     * @param TActiveButton $sender
     * @param <type> $param
     */
    public function enviarCallback($sender, $param)
    {
        //$this->hdfCampanha->Value = $param->CallbackParameter;
    }
}
?>
