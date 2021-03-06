$(document).ready(function(e) {
	initPage();
	
	$("#btnQuery").click(function() {
		ajaxQuery();	
	})
	
	$("#btnEditConfirm").click(function() {
		ajaxEdit();		
	})
	
	$("#tableResult").live("click", function(e) {
		var thisTr = $(e.target).closest("tr");
		if($(e.target).html()==="编辑") {
			var siblings = $(e.target).parent("td").siblings();
			$("#editDutyDepartment").val(siblings[3].innerHTML);
			$("#editRemark").val(siblings[4].innerHTML);
			$("#editModal").data("id", thisTr.data("id"));
			$("#editCauseType").val(siblings[1].innerHTML);
			$("#editPauseType").html(thisTr.data("pause_type") + " , " + siblings[2].innerHTML);
			$("#editPauseTime").html(siblings[6].innerHTML + " ~ " + siblings[7].innerHTML);
			$("#standardName").html("");
			
			$("#editModal").modal("show");	
		} else if($(e.target).html()==="删除") {
			if(confirm("是否删除该停线记录？（注意该操作不可恢复！）")) {
				ajaxDelete(thisTr.data("id"));
			}
		}
	})
	
	$(".prePage").click(function (){
		if(parseInt($(".curPage").attr("page")) > 1){
			$("#tableResult tbody").text("");
			ajaxQuery(parseInt($(".curPage").attr("page")) - 1);
		}
	})

	$(".nextPage").click(function (){
		if(parseInt($(".curPage").attr("page")) * 10 < parseInt($("#totalText").attr("total")) ){
			$("#tableResult tbody").text("");
			ajaxQuery(parseInt($(".curPage").attr("page")) + 1);
		}
	})

	$("#export").click(
		function () {
			ajaxExportPauseRecord();
			return false;
		}
	);

	$('#startTime, #endTime').datetimepicker({
		timeFormat: "HH:mm",
		changeMonth: true,
	    changeYear: true,
	    showOtherMonths: true,
	    selectOtherMonths: true,
	    duration: "fast",
	    buttonImageOnly: true,
	});
	
	function initPage() {
		$("#headEfficiencyLi").addClass("active");
		$("#leftPauseEditLi").addClass("active");
		
		$("#startTime").val(currentDate8());
		//$("#endTime").val(currentDate16());
		$("#endTime").val(currentTime());
		
		$("#tableResult").hide();
		$(".pagination").hide();
		
		ajaxQuery();
	}
	
	function ajaxQuery(targetPage) {
		$.ajax({
			type: "get",
			dataType: "json",
			url: QUERY_PAUSE_RECORD,
			data: {
				"startTime": $("#startTime").val(),
				"endTime": $("#endTime").val(),
				"causeType": $("#causeType").val(),
				"dutyDepartment": $("#dutyDepartment").val(),
				"section": $("#section").val(),	
				"perPage": 10,
				"curPage": targetPage || 1,
				"orderBy": 'ORDER BY pause_time DESC',
			},
			success: function(response) {
				if(response.success) {
					$("#tableResult>tbody").html("");
					$.each(response.data.data, function(index, value) {
						var tr = $("<tr />");
						$("<td />").html(value.id).appendTo(tr);
						//$("<td />").html(value.pause_type).appendTo(tr);
						$("<td />").html(value.cause_type).appendTo(tr);
						$("<td />").html(value.node_name).appendTo(tr);
						$("<td />").html(value.duty_department).appendTo(tr);
						$("<td />").html(value.remark).appendTo(tr);
						$("<td />").addClass("alignRight").html(value.howlong).appendTo(tr);
						$("<td />").html(value.pause_time.substr(0,16)).appendTo(tr);
						if(value.recover_time === "0000-00-00 00:00:00"){
							$("<td />").html("未恢复").appendTo(tr);
						}else{
							$("<td />").html(value.recover_time.substring(0,16)).appendTo(tr);
						}
						var editTd = $("<td />").html(" ¦ ");
						$("<button />").addClass("btn-link").html("编辑").prependTo(editTd);
						$("<button />").addClass("btn-link").html("删除").appendTo(editTd);
						editTd.appendTo(tr);
						
						tr.data("id",value.id);
						tr.data("pause_type", value.pause_type);
						
						$("#tableResult tbody").append(tr);
						
						if(response.data.pager.curPage == 1) {
		    			//$(".prePage").hide();
						$(".prePage a span").html("&times;");
					} else {
		    			//$(".prePage").show();
						$(".prePage a span").html("&lt;");
					}
		    		if(response.data.pager.curPage * 10 >= response.data.pager.total ) {
		    			//$(".nextPage").hide();
						$(".nextPage a span").html("&times;");
					} else {
		    			//$(".nextPage").show();
						$(".nextPage a span").html("&gt;");
					}
					$(".curPage").attr("page", response.data.pager.curPage);
					$(".curPage a span").html(response.data.pager.curPage);
					$("#totalText").attr("total", response.data.pager.total);
					$("#totalText").html("导出全部" + response.data.pager.total + "条记录");
					
					$("#tableResult").show();
					$(".pagination").show();
					});
				}else {
					alert(response.message);	
				}
			},
			error: function() {
				alertError();
			}	
		})
	}

	function ajaxExportPauseRecord () {
		window.open(EXPORT_PAUSE_RECORD +
			"?&startTime=" + $("#startTime").val() +
			"&endTime=" + $("#endTime").val() +
			"&causeType=" + $("#causeType").val() +
			"&dutyDepartment=" + $("#dutyDepartment").val() +
			"&section=" + $("#section").val()
		);
	}
	
	function ajaxEdit() {
		$.ajax({
			type: "get",
			dataType: "json",
			url: PAUSE_EDIT_SAVE,
			data: {
				"id": $("#editModal").data("id"),
				"causeType": $("#editCauseType").val(),
				"dutyDepartment": $("#editDutyDepartment").val() === '' ? '待定' : $("#editDutyDepartment").val(),
				"remark": $("#editRemark").val(),
			},
			success: function(response) {
				if(response.success){
					ajaxQuery();
					emptyEditModal();
					$("#editModal").modal("hide");	
				} else {
					alert(response.message);
				}
			},
			error: function() {
				alertError();
			}
		})
	}

	function ajaxDelete(pauseId) {
		$.ajax({
			type: "get",
			dataType: "json",
			url: PAUSE_DELETE,
			data: {
				"id": pauseId,
			},
			success: function(response) {
				if(response.success){
					ajaxQuery();
				} else {
					alert(response.message);
				}
			},
			error: function() {
				alertError();
			}
		})
	}
	
	function emptyEditModal() {
		$("#editModal").data("id", 0),
		$("#editDutyDepartment").val(""),
		$("#editRemark").val("")	
	}
	
	function currentTime (argument) {
		var now = new Date();
	        var year = now.getFullYear();       //年
	        var month = now.getMonth() + 1;     //月
	        var day = now.getDate();            //日
	        var hh = now.getHours();            //时
	        var mm = now.getMinutes();          //分
	       
	        var clock = year + '-';

	        if(month < 10) clock += '0';
	        clock += month + '-';

	        if(day < 10) clock += '0';
	        clock += day + ' ';
			
			if(hh < 10 ) clock += '0'
	        clock += hh + ':'; 
			
			if(mm < 10) clock += '0';
			clock += mm;

	        return(clock); 
	}
	
	function currentDate8 (argument) {
		var now = new Date();
	        var year = now.getFullYear();       //年
	        var month = now.getMonth() + 1;     //月
	        var day = now.getDate();            //日
	        var hh = now.getHours();            //时
	        var mm = now.getMinutes();          //分
	       
	        var clock = year + '-';

	        if(month < 10) clock += '0';
	        clock += month + '-';

	        if(day < 10) clock += '0';
	        clock += day + ' ';

	        clock += "08:00";

	        return(clock); 
	}

	function currentDate16 (argument) {
		var now = new Date();
	        var year = now.getFullYear();       //年
	        var month = now.getMonth() + 1;     //月
	        var day = now.getDate();            //日
	        var hh = now.getHours();            //时
	        var mm = now.getMinutes();          //分
	       
	        var clock = year + '-';

	        if(month < 10) clock += '0';
	        clock += month + '-';

	        if(day < 10) clock += '0';
	        clock += day + ' ';

	        clock += "16:00";

	        return(clock); 
	}

	$("#editDutyDepartment").typeahead({
	    source: function (input, process) {
	        $.get(GET_PAUSE_DUTY_DEPARTMENT_LIST, {"departmentName":input}, function (data) {
	        	if(data.data == '') {
	        		$("#standardName").html("<i class='fa fa-times'></i>");
	        	}
	        	return process(data.data);
	        },'json');
	    },
	    updater:function (item) {
			if(item != '') {
				$("#standardName").html("<i class='fa fa-check'></i>");
			}

			return item;
    	}
	});

	$("#dutyDepartment").typeahead({
	    source: function (input, process) {
	        $.get(GET_PAUSE_DUTY_DEPARTMENT_LIST, {"departmentName":input}, function (data) {
	        	return process(data.data);
	        },'json');
	    },
	});
});