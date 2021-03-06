<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>备车订单</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<link href="/bms/css/datetimepicker.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/other/OrderMaintain.css" rel="stylesheet">	
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.zh-CN.js"></script>
    <script type="text/javascript" src="/bms/rjs/lib/jsrender.min.js"></script>
	<script type="text/javascript" src="/bms/js/common.js"></script>
	<script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/execution/assembly/other/orderMaintain.js"></script>
	<script type="text/javascript" src="/bms/js/datePicker/WdatePicker.js"></script>
</head>
<body>
		
	<?php
		require_once(dirname(__FILE__)."/../../../common/head.php");
	?>
	<div class="offhead">
	   <?php
		// require_once(dirname(__FILE__)."/../../../common/left/assembly_plan_left.php");
		?>
     
        <div id="bodyright" class="offset2"><!-- 页体 -->
            <div>
            	<legend>发车计划
            		<span class="">
            			<a id="addOrder" href="#"><i class="fa fa-plus"></i>&nbsp;普通</a>
            			|
            			<a id="addSpecialOrder" href="#"><i class="fa fa-plus"></i>&nbsp;出口</a>
            			|
            			<a id="addInternalOrder" href="#"><i class="fa fa-plus"></i>&nbsp;内部</a>
            		</span>
            	</legend>
            </div>
            
   	   		<div><!-- 主体 -->

			    <div class="">
			    	<form id="form" class="well form-inline">
	                    <table>
	                        <tr>
	                            <td>备车日期&nbsp;<a href="#" id="refreshDate"><i class="fa fa-refresh"></i></a></td>
	                            <td>备板编号</td>
	                            <td>订单号</td>
	                            <td>经销商</td>
	                            <td>车系</td>
	                            <td></td>
	                        </tr>
	                        <tr>
	                            <td>
	                            	<div class="input-append">
								      	<!-- <input id="standbyDate"  type="text" class="input-small" placeholder="备车日期..."onClick="WdatePicker({el:'standbyDate',dateFmt:'yyyy-MM-dd'});"/> -->
								      	<input id="standbyDate"  type="text" class="input-small" placeholder="备车日期..."/>
							      		<a class="btn clearinput appendBtn"><i class="fa fa-times"></i></a>
							    	</div>
	                            </td>
	                        	<td>
	                        		<div class="input-append">
		                        		<input id="boardNumber" type="text" class="input-small" placeholder="备板号...">
							      		<a class="btn clearinput appendBtn"><i class="fa fa-times"></i></a>
							    	</div>
	                        	</td>
	                        	<td>
	                        		<div class="input-append">
		                        		<input id="orderNumber" type="text" class="input-medium" placeholder="订单号...">
							      		<a class="btn clearinput appendBtn"><i class="fa fa-times"></i></a>
							    	</div>
	                        	</td>
	                           <td>
		                           	<div class="input-append">
		                           		<input id="distributor" type="text" class="input-medium" placeholder="经销商...">
							      		<a class="btn clearinput appendBtn"><i class="fa fa-times"></i></a>
							    	</div>
	                           </td>
	                           <td>
	                           		<select name="" id="selectSeries" class="input-small selectSeries">
		                                <option value="">全车系</option>
		                                <!-- <option value="F0">F0</option>
		                                <option value="M6">M6</option>
		                                <option value="6B">思锐</option> -->
		                            </select>
		                            <script id="tmplSeriesSelect" type="text/x-jsrander">
	                                    <option value='{{:series}}'>{{:name}}</option>
	                                </script>
	                           </td>
	                            <td>
	                                <input type="button" class="btn btn-primary" id="btnQuery" value="查询" style="margin-left:2px;"></input>   
	                                <!-- <input id="btnAdd" type="button" class="btn btn-success" value="录入"></input> -->
	                                <label class="checkbox"><input type="checkbox" id="checkboxActive" value="1">激活</input></label>
		                            <label class="checkbox"><input type="checkbox" id="checkFreeze" value="0">冻结</input></label>
		                            <label class="checkbox"><input type="checkbox" id="checkClosed" value="2">完成</input></label>
	                            </td>
	                        </tr>
	                    </table>
	                </form>
	                
	                <table id="tableResult" class="table table-condensed table-hover" style="font-size:12px;">
	                    <thead>
	                        <tr>
	                            <!-- <th>#</th> -->
	                            <th id="thReorder">调整</th>
	                            <th id="thPriority">优先</th>
	                            <th id="thStatus">操作</th>
	                            <th id="thBoard">备板编号</th>
	                            <th id="thEdit"></th>
	                            <th id="thLane">车道</th>
	                            <th id="thOrderNumber">订单号</th>
	                            <th id="thDistributor">经销商</th>
	                            <th id="thSeries">车系</th>
	                            <th id="thCarType">车型/配置/耐寒性</th>
	                            <!-- <th id="thConfig">配置</th> -->
	                            <!-- <th id="thColdResistant">耐寒性</th> -->
	                            <th id="thColor">颜色</th>
	                            <th id="thAmount">数量</th>
	                            <th id="thHold">已备</th>
	                            <th id="thCount">出库</th>
	                            <th id="thToCount">统计</th>
	                            <!-- <th id="thOrderType">订单类型</th> -->
	                            <!-- <th id="thRemark">备注</th> -->
	                        </tr>
	                    </thead>
	                    <tbody>
	                        
	                    </tbody>
	                </table>
			    </div>
		  	</div><!-- end of 主体 -->
        </div><!-- end of 页体 -->
	</div><!-- offhead -->
<!-- new record -->
<div class="modal" id="newModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>录入订单</h3>
  	</div>
  	<div class="modal-body">
  		<form id="" class="form-horizontal">
  			<div class="control-group">
			    <label class="control-label" for="">*&nbsp;备板编号</label>
			    <div class="controls">
			      	<input type="text" id="newBoardNumber" class="input-small" placeholder="备板编号...">
			      	<input type="text" id="newStandbyDate" class="input-small" placeholder="备车日期..."  onClick="WdatePicker({el:'newStandbyDate',dateFmt:'yyyy-MM-dd'});">
			    </div>
			</div> 
			<div class="control-group">
			    <label class="control-label" for="">*&nbsp;获取订单</label>
			    <div class="controls">
			    	<div class="input-append">
				      	<input type="text" id="newOrderNumber" class="input-medium" placeholder="订单号...">
			      		<a class="btn appendBtn" id="newGetOrder"><i class="fa fa-search"></i></a>
			    	</div>
			    	<span class="help-inline" id="hint">请输入订单号取得订单明细</span>
			      	<div class="help-inline" id="orderInfo" style="display:none">
						<!-- <span class="label label-info" rel="tooltip" title="经销商" id="newDistributor" code=""></span> -->
			      		<a class="btn btn-link" id="newClearOrder"><i class="fa fa-times"></i></a>
					</div>
			    </div>
			</div> 	  
  			<div class="control-group">
                <label class="control-label" for="">&nbsp;承运商</label>
                <div class="controls">
                	<select id="newCarrier" class="input-small">
                        <option value="" selected></option>
                        <option value="安吉">安吉</option>
                        <option value="华秦">华秦</option>
                        <option value="銮通">銮通</option>
                        <option value="兴达">兴达</option>
                        <option value="远志达">远志达</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">&nbsp;城市</label>
                <div class="controls">
                    <input id="newCity" type="text" class="input-medium" disabled="disabled" placeholder="输入城市..."/>
                </div>
            </div>  
		</form>
		<legend></legend>
		<table id="tableNewOrder" class="table table-condensed table-hover" style="display:none">
			<thead>
				<tr>
					<th>选择</th>
					<th>经销商</th>
					<th>数量</th>
					<th>车系</th>
					<th>车型</th>
					<th>耐寒性</th>
					<th>颜色</th>
					<th>配置</th>
					<th>车道</th>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-success" id="btnAddMore">继续录入</button>
	    <button class="btn btn-primary" id="btnAddConfirm">确认录入</button>
  	</div>
</div>

<!-- new record -->
<div class="modal" id="specialModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>录入出口订单</h3>
  	</div>
  	<div class="modal-body">
  		<form id="" class="form-horizontal">
  			<div class="control-group">
			    <label class="control-label" for="">*&nbsp;备板编号</label>
			    <div class="controls">
			      	<input type="text" id="specialBoardNumber" class="input-small" placeholder="备板编号...">
			      	<input type="text" id="specialStandbyDate" class="input-small" placeholder="备车日期..."  onClick="WdatePicker({el:'newStandbyDate',dateFmt:'yyyy-MM-dd'});">
			    </div>
			</div> 
			<div class="control-group">
			    <label class="control-label" for="">*&nbsp;特殊订单</label>
			    <div class="controls">
			    	<div class="input-append">
				      	<input type="text" id="specialOrderNumber" class="input-medium" placeholder="订单号...">
			      		<a class="btn appendBtn" id="specialGetOrder"><i class="fa fa-search"></i></a>
			    	</div>
			    	<span class="help-inline" id="specialHint">请输入特殊订单号取得订单明细</span>
			      	<div class="help-inline" id="specialOrderInfo" style="display:none">
						<!-- <span class="label label-info" rel="tooltip" title="经销商" id="newDistributor" code=""></span> -->
			      		<a class="btn btn-link" id="specialClearOrder"><i class="fa fa-times"></i></a>
					</div>
			    </div>
			</div>
			<!-- <div class="control-group">
			    <label class="control-label" for="">*&nbsp;出口国家</label>
			    <div class="controls">
			      	<input type="text" id="country" class="input-small" placeholder="国家...">
			    </div>
			</div> -->	  
		</form>
		<legend></legend>
		<table id="tableSpecialOrder" class="table table-condensed table-hover" style="display:none">
			<thead>
				<tr>
					<th>选择</th>
					<!-- <th>分拆</th> -->
					<th>数量</th>
					<th>车系</th>
					<th>车型</th>
					<th>耐寒性</th>
					<th>颜色</th>
					<th>配置</th>
					<th>车道</th>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-success" id="btnAddMoreSpecial">继续录入</button>
	    <button class="btn btn-primary" id="btnAddConfirmSpecial">确认录入</button>
  	</div>
</div>

<!-- edit record -->
<div class="modal" id="editModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>编辑</h3>
    </div>
    <div class="modal-body">
        <form id="editForm" class="form-horizontal">
        	<div class="control-group">
                <label class="control-label" for="">参与待备统计</label>
                <div class="controls">
                    <input id="editToCount" type="checkbox">
                </div>
            </div>
        	<div class="control-group">
                <label class="control-label" for="editBoardNumber">*&nbsp;备板编号</label>
                <div class="controls">
                    <input id="editBoardNumber"  type="text" class="input-small" placeholder="备板编号..."/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">&nbsp;承运商</label>
                <div class="controls">
                	<select id="editCarrier" class="input-small">
                        <option value="" selected></option>
                        <option value="安吉">安吉</option>
                        <option value="华秦">华秦</option>
                        <option value="銮通">銮通</option>
                        <option value="兴达">兴达</option>
                        <option value="远志达">远志达</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editStandbyDate">*&nbsp;备车日期</label>
                <div class="controls">
                    <input id="editStandbyDate"  type="text" class="input-small" placeholder="备车日期..."onClick="WdatePicker({el:'editStandbyDate',dateFmt:'yyyy-MM-dd'});"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editStatus">&nbsp;状态</label>
                <div class="controls">
                	<select id="editStatus" class="input-small">
                		<option value="0" disabled>冻结</option>
                		<option value="1" disabled>激活</option>
                		<option value="2">关闭</option>
                	</select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editLane">&nbsp;车道</label>
                <div class="controls">
                    <select id="editLane"  name=""class="input-small">
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="">经销商</label>
                <div class="controls">
                    <input type="text" id="editDistributorName" class="input-large" placeholder="请输入经销商">
                </div>
            </div>
            <!-- <div class="control-group">
                <label class="control-label" for="editOrderNumber">*&nbsp;订单号</label>
                <div class="controls">
                    <input id="editOrderNumber" type="text" class="input-medium" placeholder="输入订单号..."/>
                </div>
            </div> -->
            <div class="control-group">
                <label class="control-label" for="editAmount">*&nbsp;数量</label>
                <div class="controls">
                    <input id="editAmount" type="text" class="input-small" placeholder="请输入数量..."/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editSeries">*&nbsp;车系</label>
                <div class="controls">
                    <select id="editSeries" class="input-small selectSeries">
                        <option value="" selected>请选择</option>
                        <!-- <option value="F0">F0</option>
                        <option value="M6">M6</option>
                        <option value="6B">思锐</option> -->
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editCarType">*&nbsp;车型</label>
                <div class="controls">
                    <select id="editCarType" name="" class="input-large">
                        <!-- <option value="">请选择</option> -->
                    </select> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editOrderConfig">*&nbsp;配置</label>
                <div class="controls">
                    <select id="editOrderConfig" name=""class="input-medium">
                        <!-- <option value="">请选择</option> -->
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="ditColor">*&nbsp;颜色</label>
                <div class="controls">
                    <select id="editColor" name=""class="input-small">
                        <!-- <option value="">请选择</option> -->
                    </select> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">耐寒型</label>
                <div class="controls">
                    <input id="editColdResistant" type="checkbox">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="editRemark">备注</label>
                <div class="controls">
                    <textarea id="editRemark" class="input-large"rows="2"></textarea>
                </div>
            </div>        
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <button class="btn btn-primary" id="btnEditConfirm">确认编辑</button>
    </div>
</div>

<!-- internal record -->
<div class="modal" id="internalModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>新增内部订单</h3>
    </div>
    <div class="modal-body">
        <form id="internalForm" class="form-horizontal">
        	<div class="control-group">
                <label class="control-label" for="">参与待备统计</label>
                <div class="controls">
                    <input id="internalToCount" type="checkbox">
                </div>
            </div>
        	<div class="control-group">
                <label class="control-label" for="internalBoardNumber">*&nbsp;备板编号</label>
                <div class="controls">
                    <input id="internalBoardNumber"  type="text" class="input-small" placeholder="备板编号..."/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="internalStandbyDate">*&nbsp;备车日期</label>
                <div class="controls">
                    <input id="internalStandbyDate"  type="text" class="input-small" placeholder="备车日期..."onClick="WdatePicker({el:'editStandbyDate',dateFmt:'yyyy-MM-dd'});"/>
                </div>
            </div>
            <!-- <div class="control-group">
                <label class="control-label" for="internalStatus">&nbsp;状态</label>
                <div class="controls">
                	<select id="internalStatus" class="input-small">
                		<option value="0">冻结</option>
                		<option value="1">激活</option>
                		<option value="2">关闭</option>
                	</select>
                </div>
            </div> -->
            <div class="control-group">
                <label class="control-label" for="internalLane">&nbsp;车道</label>
                <div class="controls">
                    <select id="internalLane"  name=""class="input-small">
                        
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="">需求部门</label>
                <div class="controls">
                    <input type="text" id="internalDistributorName" class="input-large" placeholder="请输入经销商">
                </div>
            </div>
            <!-- <div class="control-group">
                <label class="control-label" for="editOrderNumber">*&nbsp;订单号</label>
                <div class="controls">
                    <input id="editOrderNumber" type="text" class="input-medium" placeholder="输入订单号..."/>
                </div>
            </div> -->
            <div class="control-group">
                <label class="control-label" for="editAmount">*&nbsp;数量</label>
                <div class="controls">
                    <input id="internalAmount" type="text" class="input-small" placeholder="请输入数量..."/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="internalSeries">*&nbsp;车系</label>
                <div class="controls">
                    <select id="internalSeries" class="input-small selectSeries">
                        <option value="" selected>请选择</option>
                        <!-- <option value="F0">F0</option>
                        <option value="M6">M6</option>
                        <option value="6B">思锐</option> -->
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="internalCarType">*&nbsp;车型</label>
                <div class="controls">
                    <select id="internalCarType" name="" class="input-large">
                        <!-- <option value="">请选择</option> -->
                    </select> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="internalOrderConfig">*&nbsp;配置</label>
                <div class="controls">
                    <select id="internalOrderConfig" name=""class="input-medium">
                        <!-- <option value="">请选择</option> -->
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="ditColor">*&nbsp;颜色</label>
                <div class="controls">
                    <select id="internalColor" name=""class="input-small">
                        <!-- <option value="">请选择</option> -->
                    </select> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">耐寒型</label>
                <div class="controls">
                    <input id="internalColdResistant" type="checkbox">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="editRemark">备注</label>
                <div class="controls">
                    <textarea id="internalRemark" class="input-large"rows="2"></textarea>
                </div>
            </div>        
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <button class="btn btn-primary" id="btnInternalConfirm">确认编辑</button>
    </div>
</div>


<!-- split record -->
<div class="modal" id="splitModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>分拆</h3>
    </div>
    <div class="modal-body">
        <form id="splitForm" class="form-horizontal">
            <!-- <div class="control-group">
                <label class="control-label" for="splitStandbyDate">*&nbsp;备车日期</label>
                <div class="controls">
                    <input id="splitStandbyDate"  type="text" class="input-small" placeholder="备车日期..."onClick="WdatePicker({el:'editStandbyDate',dateFmt:'yyyy-MM-dd'});"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="splitStatus">&nbsp;状态</label>
                <div class="controls">
                	<select id="splitStatus" class="input-small">
                		<option value="0">冻结</option>
                		<option value="1">激活</option>
                		<option value="2">关闭</option>
                	</select>
                </div>
            </div> -->
            <div class="control-group">
                <label class="control-label" for="splitLane">&nbsp;车道</label>
                <div class="controls">
                    <select id="splitLane"  name=""class="input-small">
                        <option value="0" selected>未选择</option>
                        <?php 
                            for($i=1;$i<52;$i++){
                                $num = sprintf("%02d", $i);
                                if($i<51){
	                                $ret = "<option value=". $i .">$num</option>";
                                } else {
	                                $ret = "<option value=". $i .">加车道</option>";
                                }
                                echo $ret;
                            }

                            $j = 50;
                            for($i=126;$i<176;$i++){
                                $num = 'A' . sprintf("%02d", $j);
                                $j--;
	                            $ret = "<option value=". $i .">$num</option>";
                                echo $ret;
                            }
                        ?>
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="splitAmount">*&nbsp;分拆数量</label>
                <div class="controls">
                    <input id="splitAmount" type="text" class="input-small" placeholder="请输入数量..."/>
                </div>
            </div>      
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <button class="btn btn-primary" id="btnSplitConfirm">确认编辑</button>
    </div>
</div>

<!-- new record -->
<div class="modal" id="manualModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h4>手工配单</h4>
  	</div>
  	<div class="modal-body">
  		<form id="" class="form-horizontal">
  			<div class="control-group">
			    <label class="control-label" for="">*&nbsp;VIN</label>
			    <div class="controls">
				    <textarea id="manualVinText" class="input-xlarge"rows="3"></textarea>
				    <a class="btn" id="manualSearch" style="vertical-align: top;"><i class="fa fa-search"></i>查询</a>
				    <span class="help-inline" id="manualHint" style="vertical-align: top; margin-top:8px;">请以逗号、空格、换行、tab分隔VIN</span>
				</div>
			</div> 	  
		</form>
		<legend></legend>
		<table id="tableManual" class="table table-condensed table-hover">
			<thead>
				<tr>
					<th>选择</th>
					<th>VIN</th>
					<th>车系</th>
					<th>配置</th>
					<th>耐寒性</th>
					<th>颜色</th>
					<th>发动机号</th>
					<th>备注</th>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-success" id="btnAddMoreManual">继续录入</button>
	    <button class="btn btn-primary" id="btnAddConfirmManual">确认录入</button>
  	</div>
</div>
  	
</body>
</html>