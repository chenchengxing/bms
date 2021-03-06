require.config({
    "baseUrl": "/bms/rjs/lib",
    "paths":{
        "jquery": "./jquery-2.0.3.min",
        "bootstrap": "./bootstrap.min",
        "bootstrap-datetimepicker": "./bootstrap-datetimepicker.min",
        "bootstrap-datetimepicker.zh-CN": "./bootstrap-datetimepicker.zh-CN",
        "head": "../head",
        "service": "../service",
        "common": "../common",
        "dateTimeUtil": "../dateTimeUtil"
    },
    "shim": {
        "bootstrap": ["jquery"],
        "bootstrap-datetimepicker": ["jquery"]
    }
});

require(["dateTimeUtil","head","service","common","jquery","bootstrap","bootstrap-datetimepicker"], function(dateTimeUtil,head,service,common,$) {
    head.doInit();
    initPage();

    $("#startTime").datetimepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left",
        language: "zh-CN",
        minView: "2"
    });


    $("#btnQuery").click(function () {
        selected = $('#tabs .active a').attr('href');
        goQuery(selected);
    });

    $('#tabs').on('click', 'a', function () {
        selected = $(this).attr('href');
        goQuery(selected);
    });

    function goQuery (selected) {
        switch (selected) {
            case '#reportPanel' :
                queryReportDaily();
                break;
            case '#smsPanel' :
                querySmsText();
                break;
            case '#operationPanel' :
                queryOperationReport();
                break;
            case '#distributionNetworkPanel' :
                queryDistributionNetworkReport();
                break;
            default:
                queryReportDaily();
        }
    }

    function queryReportDaily () {
        $("#tableDaily>tbody").html("");
        $.ajax({
            url: service.QUERY_PLANNING_DIVISION_DAILY,
            dataType: "json",
            data: {
                "date": $("#startTime").val()
            },
            error: function () {common.alertError();},
            success: function (response) {
                if(response.success) {
                    $.each(response.data.countData, function (series, pdTypes) {
                        var num = 0;
                        $.each(pdTypes, function () {
                            num++;
                        });
                        var tmp = $("<tbody />");
                        for(var i=0;i<num;i++) {
                            $("<tr />").appendTo(tmp);
                        }
                        var firstTr = tmp.children("tr:eq(0)");
                        firstTr.addClass("thickBorder");
                        seriesTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(common.seriesName(series)).appendTo(firstTr);
                        index = 0;
                        $.each(pdTypes, function (pdType, values) {
                            var tr = tmp.children("tr:eq("+ index +")");
                            typeTd = $("<td />").html(pdType).appendTo(tr);
                            if(pdType == "合计") {
                                tr.addClass("info");
                                typeTd.addClass("alignRight");
                            }
                            $.each(values, function (point, timespans) {
                                $.each(timespans, function (timespan, counts) {
                                    $.each(counts, function (count, value) {
                                        $("<td />").addClass("alignRight").html(value).appendTo(tr);
                                    });
                                });
                            });
                            index++;
                        });
                        $("#tableDaily>tbody").append(tmp.children("tr"));
                    });

                    totalTr = $("<tr />").addClass("thickBorder").addClass("warning");
                    $("<td />").attr("colspan", "2").addClass("alignRight").html("长沙基地合计").appendTo(totalTr);
                    $.each(response.data.countTotal, function (point, timespans) {
                        $.each(timespans, function (timespan, counts) {
                            $.each(counts, function (count, value) {
                                $("<td />").addClass("alignRight").html(value).appendTo(totalTr);
                            });
                        });
                    });
                    $("#tableDaily>tbody").append(totalTr);

                    $("#tableDaily").show();
                } else {
                    alert(response.message);
                }
            }
        });
    }

    function querySmsText () {
        $("#tableSms>tbody").html("");
        $.ajax({
            url: service.QUERY_PLANNING_DIVISION_SMS_DAILY,
            dataType: "json",
            data: {
                "date": $("#startTime").val()
            },
            error: function () {common.alertError();},
            success: function (response) {
                if(response.success) {
                    var text = {};
                    $.each(response.data, function (time, seriesNames) {
                        var num = 0,
                            assemblyTotal = 0,
                            warehouseTotal = 0,
                            distributedTotal = 0,
                            distributedMonthTotal = 0,
                            warehouseMonthTotal = 0,
                            inventoryTotal = 0,
                            unDistributedTotal = 0,

                            seriesArr = [];
                            distributedMonthArr = [];
                            warehouseMonthArr = [];
                            inventoryArr = [];
                            unDistributedArr = [];
                        var tmp = $("<tbody />");

                        $.each(seriesNames, function (series, datas) {
                            textSeries = series;
                            tr = $("<tr />");
                            assembly = parseInt(datas['上线']) || 0;
                            warehouse = parseInt(datas['入库']) || 0;
                            distributed = parseInt(datas['出库']) || 0;
                            distributedMonth = parseInt(datas['已发']) || 0;
                            warehouseMonth = parseInt(datas['已入']) || 0;
                            inventory = parseInt(datas['库存']) || 0;
                            unDistributed = parseInt(datas['未发']) || 0;
                            $("<td />").html(series).appendTo(tr);
                            $("<td />").html(assembly).appendTo(tr);
                            $("<td />").html(warehouse).appendTo(tr);
                            $("<td />").html(distributed).appendTo(tr);
                            $("<td />").html(distributedMonth).appendTo(tr);
                            $("<td />").html(warehouseMonth).appendTo(tr);
                            $("<td />").html(inventory).appendTo(tr);
                            $("<td />").html(unDistributed).appendTo(tr);
                            tr.appendTo(tmp);
                            num++;

                            assemblyTotal += assembly;
                            warehouseTotal += warehouse;
                            distributedTotal += distributed;
                            distributedMonthTotal += distributedMonth;
                            warehouseMonthTotal += warehouseMonth;
                            inventoryTotal += inventory;
                            unDistributedTotal += unDistributed;

                            textSeries += " 上" + assembly + " 入" + warehouse + " 发" + distributed;
                            seriesArr.push(textSeries);

                            // textDistributedMonth = series + ":" + distributedMonth;
                            // textWarehouseMonth = series + ":" + warehouseMonth;
                            // textInventory = series + ":" + inventory;
                            // textUndistributed = series + ":" + unDistributed;
                            distributedMonthArr.push(series + ":" + distributedMonth);
                            warehouseMonthArr.push(series + ":" + warehouseMonth);
                            inventoryArr.push(series + ":" + inventory);
                            unDistributedArr.push(series + ":" + unDistributed);
                        })
                        firstTr = tmp.children("tr:eq(0)");
                        $("<td />").html(time).attr("rowspan", num).prependTo(firstTr);
                        $("#tableSms tbody").append(tmp.children("tr"));

                        seriesText = seriesArr.join("\n");
                        seriesText += "\n合计 上" + assemblyTotal + " 入" + warehouseTotal + " 发" + distributedTotal;

                        distributedMonthText = "\n已发" + distributedMonthTotal + " " + distributedMonthArr.join(" ");
                        warehouseMonthText = "\n已入" + warehouseMonthTotal + " " + warehouseMonthArr.join(" ");
                        inventoryText = "\n库存" + inventoryTotal + " " + inventoryArr.join(" ");
                        unDistributedText = "\n未发" + unDistributedTotal + " " + unDistributedArr.join(" ");

                        textAll ="【长沙基地生产统计】" + $("#startTime").val() + " " + time + "\n"
                                + seriesText
                                + "\n"
                                + distributedMonthText + warehouseMonthText + inventoryText + unDistributedText;
                        $("#" + time).val(textAll);
                    });
                    $("#tableSms").show();
                } else {
                    alert(response.message);
                }
            }
        });
    }

    function queryOperationReport () {
        $.ajax({
            url: service.QUERY_PLANNING_DIVISION_OPERATION,
            data: {
                'date': $('#startTime').val()
            },
            dataType: 'json',
            error: function () {common.alertError();},
            success: function (response) {
                if(response.success) {
                    $('#operationTable>tbody').remove();
                    // var detailDatas = response.data.detail.datas,
                    //     total = response.data.total;
                    // console.log(response);
                    $.each(response.data.detail.datas, function (series, pointDatas) {
                        var i=0,
                            $tbody = $('<tbody />');
                        $.each(pointDatas, function (point, datas) {
                            var $tr = $('<tr />');
                            $('<td />').html(point).appendTo($tr);
                            $('<td />').html(response.data.total[series][point]).appendTo($tr);
                            $.each(datas, function (index, value) {
                                $('<td />').html(value).appendTo($tr);
                            });
                            $tbody.append($tr);
                            i++;
                        });
                        $seriesTd = $('<td />').attr('rowspan', i).html(series);
                        $tbody.find('tr').eq(0).prepend($seriesTd);
                        $('#operationTable').append($tbody);
                    });
                } else {
                    alert(response.message);
                }
            }
        });
    }

    function queryDistributionNetworkReport () {
        $.ajax({
            url: service.QUERY_PLANNING_DIVISION_DISTRIBUTION_NETWORK,
            data: {
                'date': $('#startTime').val()
            },
            dataType: 'json',
            error: function () {common.alertError();},
            success: function (response) {
                if(response.success) {
                    $.each(response.data, function (series, datas) {
                        var $tbody = $('.distribution-network-' + series + '>tbody');
                        $tbody.html('');
                        $.each(datas, function (item, data) {
                            var $tr = $("<tr />");
                            $('<td />').html(item).appendTo($tr);
                            $.each(data, function (net, values) {
                                $.each(values, function (key, value) {
                                    $('<td />').html(value).appendTo($tr);
                                });
                            });
                            $tbody.append($tr);
                        });
                    });
                } else {
                    alert(response.message);
                }
            }
        });
    }

    function initPage () {
        $("#headGeneralInformationLi").addClass("active");
        $("#startTime").val(dateTimeUtil.getTime('lastWorkDate'));
        queryReportDaily('#reportPanel');
        // querySmsText();
    }
});