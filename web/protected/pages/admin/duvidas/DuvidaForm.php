<?php

class DuvidaForm extends TPage
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

                $this->vPergunta->Text = $item->pergunta;
                $this->vResposta->Text = $item->resposta;
                $this->vPosicao->Text = $item->posicao;
                break;
            case 'edit' :
                $item = $this->Post;
                $this->id = $this->Request['id'];

                $this->pnView->Visible = false;
                $this->pnForm->Visible = true;

                $this->fPergunta->Text = $item->pergunta;
                $this->fResposta->Text = $item->resposta;
                $this->fPosicao->Text = $item->posicao;

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
        $record = FaqRecord::finder()->findByPk($id);
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
                $item = new FaqRecord();

            $item->pergunta = htmlentities(utf8_decode($this->fPergunta->Text));
            $item->resposta = htmlentities(utf8_decode($this->fResposta->Text));
            $item->posicao = $this->fPosicao->Text;

            $item->save();

            if(empty($item->faq)) $url = $this->Service->constructUrl('admin.duvidas.DuvidaList');
            else $url = $this->Service->constructUrl('admin.duvidas.DuvidaForm', array('action' => 'view', 'id' => $item->faq));
            $this->Response->redirect($url);
        }
    }

}

?>