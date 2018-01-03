<?php

include_once 'config.php';

class CategoryHandler{
	private $baseUrl;
	private $conType;
	private $headerArray;
	private $db=null;
	
	public function __construct() {
		$this->db=Database::getInstance();
		
	}
	
	public function addHeader($k, $v){
		array_push($this->headerArray, $k . ": " . $v);
	}
	
	public function getAllCategories($skip, $take,$order)
	{
		$query="select * from category";
		$query=$query ." order by category.createdDate ".$order;
		return $this->db->queryWithLimit($query,$skip,$take);
	
	}
	
	public function getById($id)
	{
		$query="select * from category where guCatId='".$id."'";
		$rawData=$this->db->query($query);
		if (isset ( $rawData )) {
		
			$query = "select * from course where guCatId ='" . $id . "'";
			$DetailData = $this->db->query ( $query );
		
			$rawData [0] ["CourseDetails"] = $DetailData;
		}
		return $rawData;
	}
	
	public function mapCategoryData($input){
	
	
		$category=new Category();
	
		$category->guCatId=GUID::getGUID();
		$category->code= $input["code"];
		$category->name=$input["name"];
		$category->desc=$input["desc"];
		$category->createdDate=date("Y-m-d H:i:s");
		
		return $category;
	}
	
	public function insertCateData($cateData)
	{
		return $query ="INSERT INTO category
						(guCatId,code,name,description,createdDate)
						VALUES
						(
						'".$cateData->guCatId."',
						'".$cateData->code."',
						'".$cateData->name."',
						'".$cateData->desc."',
						'".$cateData->createdDate."')";
	
		 
	}
	
	public function insertCategory($input) {
		$cateData=$this->mapCategoryData($input);
		$query = $this->insertCateData($cateData);
		$this->db->beginTransaction();
		try {
			$rawData = $this->db->insert ( $query );
			$rawData->guCatId=$cateData->guCatId;
			$rawData->message="Category inserted successfully.";
			$this->db->commit();
			return $rawData;
	
		} catch (Exception $e) {
			$this->db->rollback();
			return array("status"=>false,"error"=>"00001","message"=>"Category creation has been failed.");
	
		}
	}

}
?>