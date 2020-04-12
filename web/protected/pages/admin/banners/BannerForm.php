<?php
class BannerForm extends TPage
{

    public $id;

    /**
     * 
     * This method is invoked by the framework when initializing the page
     * @param mixed event parameter
     */
    public function onInit($param)
    {
        parent::onInit($param);

        switch($this->Request['action'])
        {
            case 'view' :
                $item = $this->Post;
                $this->id = $this->Request['id'];

                $this->pnView->Visible = true;
                $this->pnForm->Visible = false;

                $this->vNome->Text = $item->nome;

                if($item->tipo == 3)
                {
                    $this->vTipo->Text = "HTML";
                    $this->pnHTML->Visible = true;
                    $this->pnArquivo->Visible = false;
                    $this->vHTML->Text = $item->html;
                }
                else
                {
                    if($item->tipo == 2)
                        $this->vTipo->Text = "Flash";
                    else
                        $this->vTipo->Text = "Imagem";

                    $this->pnHTML->Visible = false;
                    $this->pnArquivo->Visible = true;
                    $this->ArquivoId->Value = $item->arquivo->arquivo;
                    $this->vArquivo->Text = $item->arquivo->nome;
                }
                break;
            case 'edit' :
                $item = $this->Post;
                $this->id = $this->Request['id'];

                $this->pnView->Visible = false;
                $this->pnForm->Visible = true;

                $this->fNome->Text = $item->nome;

                foreach($this->fTipo->Items as $tipoItem)
                {
                    if($tipoItem->Value == $item->tipo)
                        $tipoItem->Selected = true;
                }

                if($item->tipo == 3)
                    $this->fHTML->Text = $item->html;
                else
                {
                    $this->ArquivoId->Value = $item->arquivo->arquivo;
                    $this->fArquivo->Text = $item->arquivo->nome;
                }
                break;
            default :
                $this->pnView->Visible = false;
                $this->pnForm->Visible = true;

                break;
        }
    }

    /**
     * Returns the post data to be editted.
     * @return PostRecord the post data to be editted.
     * @throws THttpException if the post data is not found.
     */
    protected function getPost()
    {
        $id = (int) $this->Request['id'];
        $record = BannerRecord::finder()->withArquivo()->findByPk($id);
        if($record === null)
            throw new THttpException(500, 'Item não encontrado.');
        return $record;
    }

    public function salvarClick($sender, $param)
    {
        if($this->IsValid) // when all validations succeed
        {
            if($this->Request['action'] == 'edit')
                $item = $this->Post;
            else
                $item = new BannerRecord();

            $item->nome = htmlentities($this->fNome->Text);
            $item->tipo = $this->fTipo->SelectedValue;

            if($item->tipo == 3)
                $item->html = $this->fHTML->SafeText;
            else
                $item->arquivo = $this->ArquivoId->Value;

            $item->save();

            if(empty($item->banner)) $url = $this->Service->constructUrl('admin.banners.BannerList');
            else $url = $this->Service->constructUrl('admin.banners.BannerForm', array('action' => 'view', 'id' => $item->banner));
            $this->Response->redirect($url);
        }
    }

}

?>