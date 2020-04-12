<?php
Prado::using("Application.Commom");

class LayoutList extends TPage
{
    /**
     *
     * @param mixed event parameter
     */
    public function onLoadComplete($param)
    {
        parent::onLoadComplete($param);

        $this->LayoutGrid->DataSource = $this->getData($this->pg->Value-1, $this->LayoutGrid->PageSize);
        $this->LayoutGrid->dataBind();

        $this->paging->Text = Commom::paging($this->LayoutGrid->PageSize, $this->pg->Value, LayoutRecord::finder()->count(), $this->pg->ClientID, true);
    }

    protected function getData($pageIndex, $pageSize)
    {
        $criteria = new TActiveRecordCriteria;
        $criteria->Limit = $pageSize;
        $criteria->Offset = $pageIndex * $pageSize;

        $categorias = LayoutRecord::finder()->findAll($criteria);

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
        $id = $this->LayoutGrid->DataKeys[$item->ItemIndex];
        LayoutRecord::finder()->deleteByPk($id);
        $this->Response->redirect($this->Service->constructUrl("admin.newsletter.LayoutList"));
    }

    public function editButtonClicked($sender, $param)
    {
        $item = $param->Item;
        $id = $this->LayoutGrid->DataKeys[$item->ItemIndex];
        $this->Response->redirect($this->Service->constructUrl("admin.newsletter.LayoutForm", array("action"=>"edit", "id"=>$id)));
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