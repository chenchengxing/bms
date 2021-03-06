<?php
Yii::import('application.models.AR.UserSmsAR');
Yii::import('application.models.AR.PauseSmsAR');
Yii::import('application.models.AR.PauseSmsViewAR');
Yii::import('application.models.Sms.SmsService');

class PauseSmsCommand extends CConsoleCommand
{
	
	public function actionCheckAndSend () {
		$sql = "SELECT * FROM view_pause_sms WHERE closed=0";
		$unclosed = Yii::app()->db->createCommand($sql)->queryAll();
		if(!empty($unclosed)){
			foreach($unclosed as $pause) {
				if(0 == $pause['status']) {
					$this->sendClosed($pause);
				} else if (1 == $pause['status']) {
					$this->sendLevel($pause);
				}
			}
		}
	}

	public function sendClosed ($pause) {
		$level = $this->checkLevel($pause);
		$phoneNumbers = $this->getPhoneNumbers($pause, $level, true);
		$content = $this->makeContent($pause);
		if(!empty($phoneNumbers)){
			$SmsService = new SmsService();
			$SmsService->send($content, $phoneNumbers);
		}
		$underLevel = "level_" . $level;
		$pauseSms = PauseSmsAR::model()->findByPk($pause['id']);
		$pauseSms->closed = 1;
		$pauseSms->$underLevel = 1
		$pauseSms->update(array("closed",$underLevel));
	}

	public function sendLevel ($pause) {
		$level = $this->checkLevel($pause);
		for($i=0;$i<=$level;$i++) {
			$underLevel = "level_" . $i;
			if($pause[$underLevel] == 0) {
				$phoneNumbers = $this->getPhoneNumbers($pause, $i, false);
				$content = $this->makeContent($pause);
				if(!empty($phoneNumbers)){
					$SmsService = new SmsService();
					$SmsService->send($content, $phoneNumbers);
				}
				$pauseSms = PauseSmsAR::model()->findByPk($pause['id']);
				$pauseSms->$underLevel = 1;
				$pauseSms->update(array($underLevel));
			}
		}
	}

	public function checkLevel ($pause) {
		$end = $pause['recover_time'] == "0000-00-00 00:00:00" ? date("Y-m-d H:i:s") : $pause['recover_time'];
		$minutes = (strtotime($end) - strtotime($pause['pause_time']))/60;
		$level = 0;
		if(!empty($pause['duty_department'])) {
			switch ($minutes) {
				case ($minutes<10) : 
					$level = 1;
					break;
				case ($minutes<30) :
					$level = 2;
					break;
				case ($minutes<60) :
					$level = 3;
					break;
				default :
					$level = 4;
			}
		}
		return $level;
	}

	public function getPhoneNumbers($pause, $level, $underLevel = false) {
		$levelCon = $underLevel ? "`level`<=$level" : "`level`=$level";
		$sql = "SELECT DISTINCT(cellphone) FROM view_pause_receiver WHERE `section`='{$pause['section']}' AND $levelCon";
		if(!empty($level)) $sql .= " AND `duty_group`='{$pause['duty_group']}'";
		$numberArray = Yii::app()->db->createCommand($sql)->queryColumn();
		$numberText = join(",", $numberArray);
		return $numberText;
	}

	public function makeContent ($pause) {
		$end = $pause['recover_time'] == "0000-00-00 00:00:00" ? date("Y-m-d H:i:s") : $pause['recover_time'];
		$minutes = floor((strtotime($end) - strtotime($pause['pause_time']))/60);

		$textHead = "【总装长沙停线通知】\n";
		$textStart = "开始: " . substr($pause['pause_time'], 5, 11) . "\n";
		$textEnd = $pause['status'] == 1 ? "停线中\n" : substr($pause['recover_time'], 5, 11) . "\n";
		$textEnd = "结束: " . $textEnd;
		$textMinutes = "时长: " . $minutes . "分钟\n";
		$textSection = "工位: " . $pause['node_display_name'] . "\n";
		$textDuty = "责任: " . $pause['duty_department'] . "\n";
		$textCause = "原因: " . $pause['remark'] . "\n";
		$textFoot = "【AMS】";

		$content = "(" . $textHead . $textStart . $textEnd . $textMinutes . $textSection . $textDuty . $textCause . $textFoot .")";

		return $content;
	}
}
