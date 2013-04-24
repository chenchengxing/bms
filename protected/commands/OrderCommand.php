<?php
Yii::import('application.models.AR.OrderAR');
class OrderCommand extends CConsoleCommand
{
	public function run($args) {
		list($unfinishedOrders, $count) = $this->getUnfinishedOrders();
		$curDateOrders = $this->getCurDateOrders();
		$curDate = DateUtil::getCurDate();
		$lastDate = DateUtil::getLastDate();

		if($count > 0 && !empty($curDateOrders)) {
			foreach($curDateOrders as $order) {
				$order->priority += $count; 
				$order->save();
			}
		}
		$pri = 0;
		if(!empty($unfinishedOrders)){
			foreach($unfinishedOrders as $order) {
				$order->priority = $pri ++;
				$order->standby_date=$curDate;
				$order->save();

				$sameBoardOrders = $this->findSameBoardOrders($order->board_number);
				if(!empty($sameBoardOrders)){
					foreach($sameBoardOrders as $sameBoard){
						$sameBoard->priority = 9999;
						$sameBoard->standby_date=$curDate;
						$sameBoard->save();
					}
				}
			}
		}

	}

	private function getCurDateOrders() {
		$curDate = DateUtil::getCurDate();
		$orders = OrderAR::model()->findAll('standby_date=? ORDER BY priority ASC', array($curDate));
		return $orders;
	}

	private function getUnfinishedOrders(){
		$lastDate = DateUtil::getLastDate();
		$orders = OrderAR::model()->findAll('standby_date=? AND status=1 AND amount>count ORDER BY priority ASC', array($lastDate));
		$count = OrderAR::model()->count('standby_date=? AND status=1 AND amount>count ORDER BY priority ASC', array($lastDate));
		return array($orders, $count);
	}

	private function findSameBoardOrders($boardNumber){
		$orders = OrderAR::model()->findAll('board_number=? AND amount=count', array($boardNumber));
		return $orders;
	}
}
