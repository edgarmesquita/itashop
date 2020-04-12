<?php
Prado::using("Application.Commom");

class BannerList extends TPage
{
	/**
	 * 
	 * This method is invoked by the framework when initializing the page
	 * @param mixed event parameter
	 */
	public function onLoadComplete($param)
	{
		parent::onLoadComplete($param);
		
		$this->BannerGrid->DataSource = $this->getData($this->pg->Value-1, $this->BannerGrid->PageSize);
		$this->BannerGrid->dataBind();
		
		$this->paging->Text = Commom::paging($this->BannerGrid->PageSize, $this->pg->Value, BannerRecord::finder()->count(), $this->pg->ClientID, true);
	}
	
	protected function getData($pageIndex, $pageSize)
    {
    	$criteria = new TActiveRecordCriteria;
    	$criteria->Limit = $pageSize;
    	$criteria->Offset = $pageIndex * $pageSize;
    	
    	$banners = BannerRecord::finder()->findAll($criteria);
    	
    	return $banners;
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
		$id = $this->BannerGrid->DataKeys[$item->ItemIndex];
		BannerRecord::finder()->deleteByPk($id);
		$this->Response->redirect($this->Service->constructUrl("admin.banners.BannerList"));
	}
	
	public function editButtonClicked($sender, $param)
	{
		$item = $param->Item;
		$id = $this->BannerGrid->DataKeys[$item->ItemIndex];
		$this->Response->redirect($this->Service->constructUrl("admin.banners.BannerForm", array("action"=>"edit", "id"=>$id)));
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