<?php 
class PRINT_DALFactory{
	public static  function createInstanceCollection($coll){
		try{
			$conn = new MongoClient("mongodb://jiefang:jiefangUser1187@114.215.197.166/jiefang");
			$db = $conn->selectDB("jiefang");
		}
		catch (MongoConnectionException $e)
		{
			echo '<p>Couldn\'t connect to mongodb, is the "mongo" process running?</p>';
			exit();
		}
		$collection=$db->selectCollection($coll);
		return $collection;
	}
}
?>