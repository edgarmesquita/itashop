<?php
class EmailForm extends TPage
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

                $this->vNome->Text = $item->nome;
                $this->vEmail->Text = $item->email;
                $this->vAtivo->Text = $item->ativo==1?'Ativo':'Inativo';
                break;
            case 'edit' :
                $item = $this->Post;
                $this->id = $this->Request['id'];

                $this->pnView->Visible = false;
                $this->pnForm->Visible = true;

                $this->fNome->Text = $item->nome;
                $this->fEmail->Text = $item->email;

                foreach($this->fAtivo->Items as $ativoItem)
                {
                    if($ativoItem->Value == $item->ativo)
                        $ativoItem->Selected = true;
                }
                break;
            default :
                $this->pnView->Visible = false;
                $this->pnForm->Visible = true;
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
        $record = EmailRecord::finder()->findByPk($id);
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
                $item = new EmailRecord();
            
            $item->nome = $this->fNome->Text;
            $item->email = $this->fEmail->Text;
            $item->ativo = $this->fAtivo->SelectedValue;
            $item->save();
            
            if(empty($item->emailId)) $url = $this->Service->constructUrl('admin.newsletter.EmailList');
            else $url = $this->Service->constructUrl('admin.newsletter.EmailForm', array('action' => 'view', 'id' => $item->emailId));
            
            $this->Response->redirect($url);
        }
    }
}
?>