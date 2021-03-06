$("document").ready(function() {
	initPage();
//------------------- ajax -----------------------
	//校验
	function ajaxValidate (argument){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: VQ3_SHOW_EXCEPTION,
		    data: {"vin": $('#vinText').val(),"currentNode":$("#currentNode").attr("value")},
		    success: function(response){
			    if(response.success){
			    	$("#divDetail").fadeIn(1000);
			    	$("#vinText").val(response.data.car.vin);	//added by wujun
			    	//disable vinText and open submit button
			    	$("#vinText").attr("disabled","disabled");
					// $("#btnSubmit").removeAttr("disabled");
					$("#driver").removeAttr("disabled");
					//show car infomation
			    	toggleVinHint(false);
			    	//render car info data,include serialNumber,series,type and color
		    		var car = response.data.car;
		    		$('#serialNumber').html(car.serial_number);
		    	 	$('#series').html(byd.SeriesName[car.series]);
			    	$('#color').html(car.color);
				    $('#type').html(car.type);
				    if(car.status && car.status !== "0")
				    	$('#statusInfo').html(car.status);
				    else
				    	$('#statusInfo').text("");
				    //遍历拿到的json，拼html,塞到table中
				    $("#tableConfirmation tbody").text("");//清除之前的东东
				    $.each(response.data.faults,function(index,comp){
						var indexTd = "<td>" + (index + 1) + "</td>";
						var checkTd = '<td><input type="checkbox" value=""></td>';
						var hiddenInputs = "<input name='componentId' type='hidden' value='" + comp.component_id + "' />" +
								"<input name='faultId' type='hidden' value='" + comp.fault_id + "' />"
						var nameTd = "<td>" + comp.component_name + comp.fault_mode + hiddenInputs + "</td>";
						
						var category = "<td class='resp-category'>" + comp.duty_department + "</td>";

						var displayNameTd = "<td>" + comp.display_name + "</td>";
						var createTimeTd = "<td>" + comp.create_time + "</td>";
						$("#tableConfirmation tbody").append("<tr>" + indexTd + checkTd + nameTd + category + 
							displayNameTd + createTimeTd +  "</tr>");
					});
			    }
			    else{
				    resetPage();
					fadeMessageAlert(response.message,"alert-error");
			    }

		    },
		    error:function(){alertError();}
       });
	}

	function translate (value) {
		if(value == "assembly")
			return "总装";
		else if(value == "paint")
			return "涂装";
		else if(value == "welding")
			return "焊装";
		else if(value == "总装")
			return "assembly";
		else if(value == "涂装")
			return "paint";
		else if(value == "焊装")
			return "welding";
	}

	//提交修复的零部件
	// 	param:  sendData->
	//				{vin:vin,fault:[{fixed:true,faultId:xxx,componentId:xxx},{}]}
	function ajaxSubmit (sendData){
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: VQ3_SUBMIT_EXCEPTION,
			data: sendData,
			success: function(response){
				resetPage();
				if(response.success){
				  	fadeMessageAlert(response.message,"alert-success");
				}
				else{
					fadeMessageAlert(response.message,"alert-error");
				}
			},
			error:function(){alertError();}
		});
	}

//-------------------END ajax -----------------------
//------------------- common functions -----------------------	
	//initialize this page
	/*
		1.add head class and resetPage
		2.resetPage();
		3.hide alert
	*/
	function initPage(){
		//add head class
		$("#headAssemblyLi").addClass("active");
		$("#leftNodeSelectLi").addClass("active");
		resetPage();
		$("#messageAlert").hide();
	}

	/*
		to resetPage:
		1.enable and empty vinText
		2.focus vinText
		3.show vin hint
		4.disable submit
	*/
	function resetPage () {
		//empty vinText
		$("#vinText").removeAttr("disabled");
		$("#vinText, #driver").attr("value","");
		//聚焦到vin输入框上
		$("#vinText").focus();
		//to show vin input hint
		toggleVinHint(true);
		//disable submit button
		$("#btnSubmit").attr("disabled","disabled");
		$("#btnSubmit, #driver").attr("disabled","disabled");
		$("#tableConfirmation tbody").text("");
	}

	//toggle 车辆信息和提示信息
	/*
		@param showVinHint Boolean
		if want to show hint,set to "true"
	*/
	function toggleVinHint (showVinHint) {
		if(showVinHint){
			$("#carInfo").hide();
			$("#vinHint").fadeIn(1000);

		}else{
			$("#vinHint").hide();
			$("#carInfo").fadeIn(1000);
		}
	}

	/*
		fade infomation(error or success)
		fadeout after 5s
		@param message
		@param alertClass 
			value: alert-error or alert-success
	*/
	function fadeMessageAlert(message,alertClass){
		$("#messageAlert").removeClass("alert-error alert-success").addClass(alertClass);
		$("#messageAlert").html(message);
		$("#messageAlert").show(500,function () {
			setTimeout(function() {
				$("#messageAlert").hide(1000);
			},5000);
		});
	}
//-------------------END common functions -----------------------

//------------------- event bindings -----------------------
	//输入回车，发ajax进行校验；成功则显示并更新车辆信息
	$('#vinText').bind('keydown', function(event) {
		//if vinText disable,stop propogation
		if($(this).attr("disabled") == "disabled")
			return false;
		if (event.keyCode == "13"){
			//remove blanks 
		    if(jQuery.trim($('#vinText').val()) != ""){
		        ajaxValidate();
	        }   
		    return false;
		}
	});

	//进入彩车身库事件，发ajax，根据响应做提示
	$("#btnSubmit").click(function() {
		var sendData = {};
		sendData.vin = $("#vinText").val();
		sendData.driver = $("#driver").val();
		sendData.fault = [];
		//遍历tr，将故障id，零部件id整到  data中来
		var trs = $("#tableConfirmation tbody tr");
		$.each(trs,function (index,value) {
			var obj = {};
			obj.fixed = false;
			if($(value).find("input[type='checkbox']").attr("checked") == "checked")
				obj.fixed = true;
			obj.faultId = $(value).find("input[type='hidden'][name='faultId']").val();
			obj.componentId = $(value).find("input[type='hidden'][name='componentId']").val();
			obj.category = $(value).find(".resp-category").html();
			sendData.fault.push(obj);
		});
		sendData.fault = JSON.stringify(sendData.fault);
		ajaxSubmit(sendData);
		return false;
	});

	//清空
	$("#reset").click(function() {
		resetPage();
		return false;
	});

	$('#driver').change(function(){
		if($('#driver').val() === ''){
			$('#btnSubmit').attr('disabled', 'disabled');
		} else {
			$('#btnSubmit').removeAttr('disabled');
		}
	})

	//全选，清空
	$("#btnPickAll").click(function () {
		var checkedBoxes = $("#tableConfirmation input").not(':checked');
		$.each(checkedBoxes,function  (index,value) {
			$(value).attr("checked","checked");
		})
	});
	$("#btnPickNone").click(function () {
		var checkedBoxes = $("#tableConfirmation input:checked");
		$.each(checkedBoxes,function  (index,value) {
			$(value).removeAttr("checked");
		})
	});
//-------------------END event bindings -----------------------
});
