<?php

include_once 'config.php';

class CourseHandler{
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
	
	public function getAllCourses($skip, $take,$order)
	{
		$query="select * from course";
		$query=$query ." order by course.createdDate ".$order;
		return $this->db->queryWithLimit($query,$skip,$take);
	
	}
	
	public function getById($id)
	{
		$query="select * from course where guCourseId='".$id."'";
		$rawData = $this->db->query ( $query );
		if (isset ( $rawData )) {
				
			$query = "select * from chapter where guCourseId ='" . $rawData [0] ["guCourseId"] . "'";
			$DetailData = $this->db->query ( $query );
				
			$rawData [0] ["subModules"] = $DetailData;
			
			$query = "select * from quiz where guCourseId ='" . $rawData [0] ["guCourseId"] . "'";
			$QuizData = $this->db->query ( $query );
			
			$rawData [0] ["Quizes"] = $QuizData;
		}
		return $rawData;
	
	}
	
	public function mapCourseData($input){
		$course=new Course();
	
		$course->guCourseId=GUID::getGUID();
		$course->guCatId= $input["guCatId"];
		$course->code=$input["code"];
		$course->name=$input["name"];
		$course->type=$input["type"];
		$course->desc=$input["desc"];
		$course->filePath=$input["filePath"];
		$course->totModules=$input["noOfModules"];
		$course->imgPath=$input["imgPath"];
		$course->createdDate=date("Y-m-d H:i:s");
		
		return $course;
	}
	
	public function insertCourseData($courseData)
	{
		return $query ="INSERT INTO course
						(guCourseId,guCatId,code,name,type,description,filePath,createdDate,noOfModules,imagePath)
						VALUES
						(
						'".$courseData->guCourseId."',
						'".$courseData->guCatId."',
						'".$courseData->code."',
						'".$courseData->name."',
						'".$courseData->type."',
						'".$courseData->desc."',
						'".$courseData->filePath."',
						'".$courseData->createdDate."',
						'".$courseData->totModules."',
						'".$courseData->imgPath."')";
	
		 
	}
	
	public function insertChapterData($chapterData)
	{
		return $query ="INSERT INTO chapter
						(guCourseId,guChapterId,code,name,description,filePath,noOfModules)
						VALUES
						(
						'".$chapterData->guCourseId."',
						'".$chapterData->guChapterId."',
						'".$chapterData->code."',
						'".$chapterData->name."',
						'".$chapterData->desc."',
						'".$chapterData->filePath."',
						'".$chapterData->totModules."')";
	
			
	}
	
	public function insertQuizData($quizData)
	{
		return $query ="INSERT INTO Quiz
						(guCourseId,guQuizId,question,selectiveAnswers,description)
						VALUES
						(
						'".$quizData->guCourseId."',
						'".$quizData->guQuizId."',
						'".$quizData->question."',
						'".$quizData->selectiveAnswers."',
						'".$quizData->desc."')";
	
			
	}
	
	public function insertCourse($input) {
		$courseData=$this->mapCourseData($input);
		$query = $this->insertCourseData($courseData);
		$this->db->beginTransaction();
		try {
			$rawData = $this->db->insert ( $query );
			$rawData->guCourseId=$courseData->guCourseId;
			$rawData->message="Course inserted successfully.";
			$input["guCourseId"]=$rawData->guCourseId;
			if($courseData->type=="Multimedia"){
				foreach($input["subModules"] as $key=>$value)
				{
					$value["guCourseId"]=$input["guCourseId"];
					$chapterData=$this->_mapChapterData($value);
					$chapQuery=$this->insertChapterData($chapterData);
					$chapterData = $this->db->insert ( $chapQuery );
				}
			}
			else if($courseData->type=="Quiz")
			{
				foreach($input["quizzes"] as $key=>$value)
				{
					$value["guCourseId"]=$input["guCourseId"];
					$quizData=$this->_mapQuizData($value);
					$quizQuery=$this->insertQuizData($quizData);
					$quizData = $this->db->insert ( $quizQuery );
				}
			}
			$this->db->commit();
			return $rawData;
	
		} catch (Exception $e) {
			$this->db->rollback();
			return array("status"=>false,"error"=>"00001","message"=>"Course creation has been failed.");
	
		}
	}
	
	private function _mapChapterData($input){
		$chapter=new stdClass();
		$chapter->guChapterId=GUID::getGUID();
		$chapter->guCourseId=$input["guCourseId"];
		$chapter->code=$input["code"];
		$chapter->name=$input["name"];
		$chapter->desc=$input["desc"];
		$chapter->filePath=$input["filePath"];
		$chapter->totModules=$input["noOfModules"];
	
		return $chapter;
	}
	
	private function _mapQuizData($input){
		$quiz=new stdClass(); 
		$quiz->guQuizId=GUID::getGUID();
		$quiz->guCourseId=$input["guCourseId"];
		$quiz->question=$input["question"];
		$quiz->selectiveAnswers=json_encode($input["selectiveAnswers"]);
		$quiz->desc="";
		//$quiz->isMultiple=strtolower(($input["answerType"])=="single")?false:true;
		//$quiz->finalAnswers=json_encode($input["finalAnswers"]);
		return $quiz;
	}
	
	public function uploadFile($jsondata,$filename,$folder)
	{
		$folderLocation = MEDIA_PATH . "/media/" . $folder;
		if (!file_exists($folderLocation)) {
			mkdir($folderLocation, 0777, true);
		}
		if (json_encode(file_put_contents($folderLocation."/$filename", $jsondata)))
		{
			$fileUrl=MAIN_DOMAIN."/media/" . $folder."/".$filename;
			$resultData=array("status"=>true,"fileUrl"=>$fileUrl,"error"=>"00000","message"=>"file has been uploaded successfully.");
		}
		else{
			$resultData=array("status"=>false,"error"=>"00001","message"=>"Error uploading file".$error.".");
		}
		return $resultData;
	}
}
?>