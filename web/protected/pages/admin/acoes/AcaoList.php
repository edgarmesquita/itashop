<?php
Prado::using("Application.Commom");

class AcaoList extends TPage
{
    /**
     *
     * @param mixed event parameter
     */
    public function onLoadComplete($param)
    {
        parent::onLoadComplete($param);


        $this->AcaoGrid->DataSource = $this->getData($this->pg->Value-1, $this->AcaoGrid->PageSize);
        $this->AcaoGrid->dataBind();

        $this->paging->Text = Commom::paging($this->AcaoGrid->PageSize, $this->pg->Value, AcaoRecord::finder()->count($this->getCriteria()), $this->pg->ClientID, true);
    }

    /**
     *
     * @return TActiveRecordCriteria
     */
    private function getCriteria()
    {
        $aplicacao = $this->ddlAplicacao->SelectedValue;
        if(empty($aplicacao)) $aplicacao = 1;

        $condition = "aplicacao = $aplicacao";
        
        $criteria = new TActiveRecordCriteria;
        $criteria->Condition = $condition;
        
        return $criteria;
    }

    protected function getData($pageIndex, $pageSize)
    {
        $criteria = $this->getCriteria();

        $criteria->Limit = $pageSize;
        $criteria->Offset = $pageIndex * $pageSize;

        $acoes = AcaoRecord::finder()->findAll($criteria);

        return $acoes;
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
        $id = $this->AcaoGrid->DataKeys[$item->ItemIndex];
        AcaoRecord::finder()->deleteByPk($id);
        $this->Response->redirect($this->Service->constructUrl("admin.acoes.AcaoList"));
    }

    public function editButtonClicked($sender, $param)
    {
        $item = $param->Item;
        $id = $this->AcaoGrid->DataKeys[$item->ItemIndex];
        $this->Response->redirect($this->Service->constructUrl("admin.acoes.AcaoForm", array("action"=>"edit", "id"=>$id)));
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