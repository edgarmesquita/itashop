<?php
Prado::using("Application.Commom");

class EmailList extends TPage
{
    /**
     *
     * @param mixed event parameter
     */
    public function onLoadComplete($param)
    {
        parent::onLoadComplete($param);

        $this->NewsletterGrid->DataSource = $this->getData($this->pg->Value-1, $this->NewsletterGrid->PageSize);
        $this->NewsletterGrid->dataBind();

        $this->paging->Text = Commom::paging($this->NewsletterGrid->PageSize, $this->pg->Value, EmailRecord::finder()->count(), $this->pg->ClientID, true);
    }

    protected function getData($pageIndex, $pageSize)
    {
        $criteria = new TActiveRecordCriteria;
        $criteria->Limit = $pageSize;
        $criteria->Offset = $pageIndex * $pageSize;

        $categorias = EmailRecord::finder()->findAll($criteria);

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
        $id = $this->NewsletterGrid->DataKeys[$item->ItemIndex];
        EmailRecord::finder()->deleteByPk($id);
        $this->Response->redirect($this->Service->constructUrl("admin.newsletter.EmailList"));
    }

    public function editButtonClicked($sender, $param)
    {
        $item = $param->Item;
        $id = $this->NewsletterGrid->DataKeys[$item->ItemIndex];
        $this->Response->redirect($this->Service->constructUrl("admin.newsletter.EmailForm", array("action"=>"edit", "id"=>$id)));
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