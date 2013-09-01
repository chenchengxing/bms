<?php
Yii::import('application.models.SparesSeeker');
class SparesController extends BmsBaseController 
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	public function actionQuerySparesTrace () {
		$traceId = $this->validateIntVal("traceId", 0);
		try{
			$seeker = new SparesSeeker();
			$data = $seeker->querySparesTrace($traceId);
			$this->renderJsonBms(true, 'OK', $data);
		} catch (Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}

	public function actionQueryReplacementDetail () {
		$stime = $this->validateStringVal("stime", "");
		$etime = $this->validateStringVal("etime", "");
		$line = $this->validateStringVal("line", "");
		$series = $this->validateStringVal("series", "");
		$dutyId = $this->validateIntVal("dutyId", 0);
		$perPage = $this->validateIntVal("perPage", 20);
		$curPage = $this->validateIntVal("curPage", 1);
		try {
			$seeker = new SparesSeeker();
			list($total, $datas) = $seeker->queryReplacementDetail($stime,$etime,$series,$line,$dutyId,$curPage,$perPage);
			$ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'data' => $datas,
                    );
			$this->renderJsonBms(true, 'OK', $ret);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}

	public function actionExportReplacementDetail () {
		$stime = $this->validateStringVal("stime", "");
		$etime = $this->validateStringVal("etime", "");
		$line = $this->validateStringVal("line", "");
		$series = $this->validateStringVal("series", "");
		$dutyId = $this->validateIntVal("dutyId", 0);
		try {
			$seeker = new SparesSeeker();
			list($total, $datas) = $seeker->queryReplacementDetail($stime,$etime,$series,$line,$dutyId,0,0);
			$content = "线别,车系,VIN,零部件编号,零部件名称,零部件条码,供应商,供应商代码,工厂代码,连带损,责任部门,换件故障,换件时间\n";
			foreach($datas as $data) {
				$content .= "{$data['assembly_line']},";
				$seriesName = SeriesName::getName($data['series']);
				$content .= "{$seriesName},";
				$content .= "{$data['vin']},";
				$content .= "{$data['component_code']},";
				$content .= "{$data['component_name']},";
				$content .= "{$data['bar_code']},";
				$content .= "{$data['provider_name']},";
				$content .= "{$data['provider_code']},";
				$content .= "{$data['factory_code']},";
				$isCollateral = $data['is_collateral'] == 1 ? "是" : "否";
				$content .= "{$isCollateral},";
				$content .= "{$data['duty_department_name']},";
				$fault = $data['fault_component_name'] . $data['fault_mode'];
				$content .= "{$fault},";
				$content .= "{$data['replace_time']},";
				$content .= "\n";
			}

			$export = new Export('换件明细_' .date('YmdHi'), $content);
            $export->toCSV();
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}
}