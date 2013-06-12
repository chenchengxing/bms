<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>成品库查询</title>
    	<!-- Le styles -->
    	<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/query/WarehouseQuery.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">
        <!-- <link href="/bms/css/datetimepicker.css" rel="stylesheet">		 -->
        <link href="/bms/css/flick/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
        <link href="/bms/css/jquery-ui-timepicker-addon.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="/bms/js/jquery-ui-timepicker-addon.js"></script>
        <script type="text/javascript" src="/bms/js/jquery-ui-timepicker-addon.zh-CN.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/common.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <!-- <script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.min.js"></script> -->
        <!-- <script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.zh-CN.js"></script> -->
        <script type="text/javascript" src="/bms/js/head.js"></script>
    	<script type="text/javascript" src="/bms/js/execution/assembly/query/warehouseQuery.js"></script>
        <script type="text/javascript" src="/bms/js/datePicker/WdatePicker.js"></script>
        <script type="text/javascript" src="/bms/js/highcharts.src.js"></script>
        <script type="text/javascript" src="/bms/js/exporting.src.js"></script>
	</head>


	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
        <div class="offhead">
           <?php
            require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
            ?>
        	<!-- Main体 -->		
            <div id="bodyright" class="offset2">
                <div>
                    <legend>成品库查询
                        <span class="pull-right">
                            <!-- <a href="/bms/execution/orderMaintain"><i class="icon-link"></i>&nbsp;订单维护</a> -->
                        </span>
                    </legend>
                </div>
                <form class="well">
                    <div class="form-inline">
                        <div class="input-append input-prepend" style="clear: right">
                            <span class="add-on">日期</span>
                            <input id="startTime"  type="text" class="input-medium inputTime" placeholder="开始日期..."/>
                            <span class="add-on" style="padding:4px 0">-</span>
                            <input id="endTime"  type="text" class="input-medium inputTime" placeholder="结束日期..."/>
                        </div>
                        <div class="input-append input-prepend" style="clear: right">
                            <span class="add-on">节点</span>
                            <select name="" id="selectNode" class="input-small" style="width:94px">
                                <option value="CHECK_IN">入库</option>
                                <option value="OutStandby">备车</option>
                                <option value="CHECK_OUT">出库</option>
                                <option value="WAREHOUSE_RETURN">退库</option>
                            </select>
                        </div>
                        <div class="input-append input-prepend" style="clear: right">
                            <span class="add-on">区域</span>
                            <select name="" id="selectState" class="input-medium" style="width:130px">
                                <option value="assembly">总装(除PBS)</option>
                                <option value="WH">成品库</option>
                                <option value="WHin">成品库可备</option>
                                <option value="recycle">周转车</option>
                                <option value="VQ3">VQ3</option>
                                <option value="VQ2">VQ2</option>
                                <option value="VQ1">VQ1</option>
                                <option value="onLine">I线</option>
                                <option value="PBS">PBS</option>
                            </select>
                        </div>
                        <div class="input-append input-prepend" style="clear: right">
                            <select name="" id="selectSeries" class="input-small">
                                <option value="">全车系</option>
                                <option value="F0">F0</option>
                                <option value="M6">M6</option>
                                <option value="6B">思锐</option>
                            </select>
                        </div>

                    <div class="form-inline">
                        <div class="input-prepend input-append">
                            <span class="add-on">板号</span>
                            <input type="text" class="input-small" placeholder="备板号..." id="boardNumberText" style="width:70px"/>
                            <a class="btn clearinput appendBtn"><i class="icon-remove"></i></a>
                        </div>
                        <div class="input-prepend input-append">
                            <span class="add-on">单号</span>
                            <input type="text" class="input-medium" placeholder="订单号..." id="orderNumberText" />
                            <a class="btn clearinput appendBtn"><i class="icon-remove"></i></a>
                        </div>
                        <div class="input-prepend input-append">
                            <span class="add-on">商家</span>
                            <input type="text" class="input-medium" placeholder="经销商..." id="distributorText" style="width:165px"/>
                            <a class="btn clearinput appendBtn"><i class="icon-remove"></i></a>
                        </div>
                        <!-- <input type="button" class="btn btn-primary" id ="btnQuery" value ="查询"></input> -->
                        <div class="help-inline"  style="padding-left:0; margin-top:5px ">
                            <label class="checkbox"><input type="checkbox" id="checkboxActive" value="1">激活</input></label>
                            <label class="checkbox"><input type="checkbox" id="checkFreeze" value="0">冻结</input></label>
                            <label class="checkbox"><input type="checkbox" id="checkClosed" value="2">完成</input></label>
                        </div>
                    </div>
                    </div>
                </form>
                    <div>
                        <ul id="tabs" class="nav nav-pills">
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">车辆明细<b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#tabOrderCars" data-toggle="tab">订单车辆</a></li>
                                    <li><a href="#tabNodeCars" data-toggle="tab">节点统计</a></li>
                                    <li><a href="#tabBalanceCars" data-toggle="tab">结存明细</a></li>
                                </ul>
                            </li>
                            
                            <li><a href="#tabOrderDetail" data-toggle="tab">订单明细</a></li>
                            <li><a href="#tabPeriod" data-toggle="tab">发车周期</a></li>
                            <div id="paginationCars" class="pagination pagination-small pagination-right" style="display: none;">
                                <ul>
                                    <li id="exportCars"><a href=""><span id="totalCars"></span></a></li>
                                </ul>
                                <ul>
                                    <li id="firstCars"><a href="#">&lt;&lt;</a></li>
                                    <li id="preCars" class="prePage"><a href="#">&lt;</a></li>
                                    <li id="curCars" class="active curPage" page="1"><a href="#">1</a></li>
                                    <li id="nextCars" class="nextPage"><a href="#">&gt;</a></li>
                                    <li id="lastCars"><a href="#">&gt;&gt;</a></li>
                                </ul>
                            </div>
                        </ul>
                    </div>
                    <div id="tabContent" class="tab-content">
                        <div class="tab-pane" id="tabOrderCars">
                            <table id="tableOrderCars" class="table table-bordered detailTable">
                                <thead>
                                    <tr>
                                    	<th>车道</th>
                                        <th>订单号</th>
                                        <th>经销商</th>
                                        <th>流水号</th>
                                        <th>VIN</th>
                                        <th>车系</th>
                                        <th>配置</th>
                                        <th>耐寒性</th>
                                        <th>颜色</th>
                                        <th>发动机号</th>
                                        <th>出库时间</th>
                                        <th>原库道</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="tabNodeCars">
                            <table id="tableNodeCars" class="table table-bordered detailTable">
                                <thead>
                                    <tr>
                                        <th>流水号</th>
                                        <th>VIN号</th>
                                        <th>车系</th>
                                        <th>配置</th>
                                        <th>耐寒性</th>
                                        <th>颜色</th>
                                        <th>状态</th>
                                        <th>时间</th>
                                        <th>节点备注</th>
                                        <!-- <th>订单号</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane active" id="tabBalanceCars">
                            <table id="tableBalanceCars" class="table table-bordered detailTable">
                                <thead>
                                    <tr>
                                        <th>流水号</th>
                                        <th>VIN</th>
                                        <th>车系</th>
                                        <th>车型/配置</th>
                                        <th>耐寒性</th>
                                        <th>颜色</th>
                                        <th>状态</th>
                                        <th>库区</th>
                                        <th>下线时间</th>
                                        <th>入库时间</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="tabOrderDetail">
                            <table id="tableOrderDetail" class="table table-condensed table-bordered">
                                <thead>
                                    <th>备板编号</th>
                                	<th style="width:30px">车道</th>
                                    <th>订单号</th>
                                    <th style="width:200px">经销商</th>
                                    <th style="width:30px">车系</th>
                                    <th style="width:200px">车型/配置/耐寒性</th>
                                    <!-- <th style="width:40px">耐寒性</th> -->
                                    <th style="width:40px">颜色</th>
                                    <th colspan="2" style="text-align: center">数量</th>
                                    <th colspan="2" style="text-align: center">已备</th>
                                    <th colspan="2" style="text-align: center">出库</th>
                                    <th style="width:70px">指令激活</th>
                                    <th style="width:70px">备车完成</th>
                                    <th style="width:70px">出库完成</th>
                                    <th style="width:70px">车道释放</th>
                                    <th></th>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="tabPeriod">
                            <div id="periodContainer" style="min-width: 800px; height: 400px; margin: 0 auto"></div>
                            <table id="tablePeriod" class="table table-condensed table-bordered">
                                <thead />
                                <tbody />
                            </table>
                        </div>

                    </div>
                </div>
            </div><!-- END MAIN -->
        </div>

        <div class="modal" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4></h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed table-bordered table-hover" id="tableDetail">
                    <thead>
                        <tr>
                            <!-- <th style="width:20px"></th> -->
                            <th style="width:120px">VIN号</th>
                            <th style="width:150px">备车时间</th>
                            <th style="width:150px">出库时间</th>
                            <th style="width:50px">原库道</th>
                            <th style="width:30px">车系</th>
                            <th style="width:200px">车型/配置</th>
                            <th style="width:50px">耐寒性</th>
                            <th style="width:50px">颜色</th>
                            <th style="width:50px">发动机号</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
                <!-- <button class="btn btn-primary" id="detailPrintAll" disabled><i class="btnPrint icon-print"></i>&nbsp;打印全部</button> -->
            </div>
        </div>
    </body>
</html>
