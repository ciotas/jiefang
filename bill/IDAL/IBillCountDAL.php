<?php 
interface IBillCountDAL{
	public function getBillCountData($uid);
	public function getConsumeRank($totalmoney);
}
?>