<?php
Prado::using("Application.Commom");

class GrupoList extends TPage
{
    /**
     *
     * @param mixed event parameter
     */
    public function onLoadComplete($param)
    {
        parent::onLoadComplete($param);

        $this->GrupoGrid->DataSource = $this->getData($this->pg->Value-1, $this->GrupoGrid->PageSize);
        $this->GrupoGrid->dataBind();

        $this->paging->Text = Commom::paging($this->GrupoGrid->PageSize, $this->pg->Value, GrupoRecord::finder()->count(), $this->pg->ClientID, true);
    }

    protected function getData($pageIndex, $pageSize)
    {
        $criteria = new TActiveRecordCriteria;
        $criteria->Limit = $pageSize;
        $criteria->Offset = $pageIndex * $pageSize;

        $categorias = GrupoRecord::finder()->findAll($criteria);

        return $categorias;
    }

    /**
     * Deletes a specified record.
     * This method responds to the datagrid's OnDeleteCommand event.
     * @param TDataGrid the event sender
     * @param TDataGridCommandEventParameter the event parameter
     */
    public function deleteButtonClicked($sender, $param)
    {
        $item = $param->Item;
        $id = $this->GrupoGrid->DataKeys[$item->ItemIndex];
        try
        {
            GrupoRecord::finder()->deleteByPk($id);
        }
        catch(Exception $e)
        {
            $sender->getPage()->getClientScript()->registerEndScript("error", "alert('O grupo não pode ser excluído. Provavelmente existem usuários associados a este grupo.\nPrimeiro exclua ou altere todos os usuários que pertensam a este grupo.');");
        }
        $this->Response->redirect($this->Service->constructUrl("admin.grupos.GrupoList"));
    }

    public function editButtonClicked($sender, $param)
    {
        $item = $param->Item;
        $id = $this->GrupoGrid->DataKeys[$item->ItemIndex];
        $this->Response->redirect($this->Service->constructUrl("admin.grupos.GrupoForm", array("action"=>"edit", "id"=>$id)));
    }

    public function itemCreated($sender, $param)
    {
        $item = $param->Item;
        if ($item->ItemType === 'Item' || $item->ItemType === 'AlternatingItem')
        {
            $dataItem = $item->DataItem;
            if(isset($dataItem))
            {
                //$item->DataItem->nome = utf8_encode($item->DataItem->nome);
                //$item->DataItem->descricao = utf8_encode($item->DataItem->descricao);
            }
        }
    }
}
?>