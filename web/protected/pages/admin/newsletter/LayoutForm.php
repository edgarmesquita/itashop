<?php
class LayoutForm extends TPage
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

        switch ( $this->Request['action'])
        {
            case 'view' :
                $item = $this->Post;
                $this->id = $this->Request['id'];

                $this->pnView->Visible = true;
                $this->pnForm->Visible = false;

                $this->vTitulo->Text = $item->titulo;
                $this->vHtml->Text = $item->html;
                $this->vAtivo->Text = $item->ativo==1?'Ativo':'Inativo';
                break;
            case 'edit' :
                $item = $this->Post;
                $this->id = $this->Request['id'];

                $this->pnView->Visible = false;
                $this->pnForm->Visible = true;

                $this->fTitulo->Text = $item->titulo;
                $this->fCorFundo->Text = $item->corFundo;
                $this->lblCorFundo->BackColor = $item->corFundo;
                
                $this->fHtml->Text = $item->html;

                foreach($this->fAtivo->Items as $ativoItem)
                {
                    if($ativoItem->Value == $item->ativo)
                        $ativoItem->Selected = true;
                }
                break;
            default :
                $this->pnView->Visible = false;
                $this->pnForm->Visible = true;

                $this->fCorFundo->Text = "#FFFFFF";
                $this->lblCorFundo->BackColor = "#FFFFFF";

                $this->fAtivo->Items[0]->Selected = true;
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
        $id = ( int ) $this->Request['id'];
        $record = LayoutRecord::finder()->findByPk($id);
        if ($record === null)
            throw new THttpException(500, 'Item não encontrado.');
        return $record;
    }

    public function salvarClick($sender, $param)
    {
        if ($this->IsValid) // when all validations succeed

        {
            if ($this->Request['action'] == 'edit')
                $item = $this->Post;
            else
                $item = new LayoutRecord();

            $item->titulo = $this->fTitulo->Text;
            $item->html = $this->fHtml->Text;
            $item->corFundo = $this->fCorFundo->Text;
            $item->ativo = $this->fAtivo->SelectedValue;
            $item->save();
            
            if(empty($item->layout)) $url = $this->Service->constructUrl('admin.newsletter.LayoutList');
            else $url = $this->Service->constructUrl('admin.newsletter.LayoutForm', array('action' => 'view', 'id' => $item->layout));
            $this->Response->redirect($url);
        }
    }
}
?>