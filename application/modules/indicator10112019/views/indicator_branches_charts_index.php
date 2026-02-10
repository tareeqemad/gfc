<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 16/08/18
 * Time: 12:34 م
 */

$MODULE_NAME= 'indicator';
$TB_NAME= 'indicate_branch_charts';
$get_total_subscriber =base_url("$MODULE_NAME/$TB_NAME/public_indecator_data_tb_get");
$get_total_bills =base_url("$MODULE_NAME/$TB_NAME/public_get_total_bills");
///////////////////////////////////////////////////////////////////////////
$get_real_bills =base_url("$MODULE_NAME/$TB_NAME/public_get_real_bills");//الحقيقي
$get_goal_bills =base_url("$MODULE_NAME/$TB_NAME/public_get_goal_bills");//المستهدف
$get_actual_bills =base_url("$MODULE_NAME/$TB_NAME/public_get_actual_bills");//الفعلي
////////////////////////////////////////////////////////////////////////////
$get_prepaid_committed =base_url("$MODULE_NAME/$TB_NAME/public_get_prepaid_committed");
$get_bills_committed =base_url("$MODULE_NAME/$TB_NAME/public_get_bills_committed");
///////////////////////////////////////////////////////////////////////////////
$get_prepaid_committed =base_url("$MODULE_NAME/$TB_NAME/public_get_prepaid_committed");
$get_bills_services =base_url("$MODULE_NAME/$TB_NAME/public_get_bills_services");
$get_bills_reminder =base_url("$MODULE_NAME/$TB_NAME/public_get_bills_reminder");
$get_bills_is_money =base_url("$MODULE_NAME/$TB_NAME/public_get_bills_is_money");
echo AntiForgeryToken();

$today =  new DateTime();
$yesterday = strtotime('08:30');

$first = date("Y-m-d", strtotime("first day of this month"));
$last = date("Y-m-d", strtotime("last day of -1 month"));
//$first = new DateTime('today');
$today2 = new DateTime('today');




?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>
<div class="row">
<?php
if($today2->format('Y-m-d')==$first)
{
    if($today->format('H:i') >= date('H:i',$yesterday))
    {

        ?>
        <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">
                <div class="col-md-12">
                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon icon-bar-chart"></i>اجمالي المشتركين
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse">
                                </a>
                                <a href="#portlet-config" data-toggle="modal" class="config">
                                </a>
                                <a href="javascript:;" class="reload">
                                </a>
                                <a href="javascript:;" class="remove">
                                </a>
                            </div>
                        </div>

                        <div class="portlet-body">


                            <div class="row">

                                <div id="totally_subscribers_bills_values" class="chart">






                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon icon-bar-chart"></i>معالجة الاشتركات الغير ملتزمة
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse">
                                </a>
                                <a href="#portlet-config" data-toggle="modal" class="config">
                                </a>
                                <a href="javascript:;" class="reload">
                                </a>
                                <a href="javascript:;" class="remove">
                                </a>
                            </div>
                        </div>

                        <div class="portlet-body">


                            <div class="row">



                                <div id="process_subscribers_paid" class="chart">





                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-12">
                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon icon-pie-chart"></i>قيمة التحصيلات
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse">
                                </a>
                                <a href="#portlet-config" data-toggle="modal" class="config">
                                </a>
                                <a href="javascript:;" class="reload">
                                </a>
                                <a href="javascript:;" class="remove">
                                </a>
                            </div>
                        </div>

                        <div class="portlet-body">


                            <div class="row">


                                <div id="collection_values" class="chart" style="height: 689%">

                                </div>



                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="col-md-6">
                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon icon-tasks"></i>    التحصيلات حسب النوع (النقدي - غير نقدي -تفتيش - الخدمات )
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse">
                                </a>
                                <a href="#portlet-config" data-toggle="modal" class="config">
                                </a>
                                <a href="javascript:;" class="reload">
                                </a>
                                <a href="javascript:;" class="remove">
                                </a>
                            </div>
                        </div>

                        <div class="portlet-body">


                            <div class="row">

                                <div id="process_collection_types" class="chart">


                                </div>



                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-md-6">
                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon icon-tasks"></i>    المؤشرات الفنية (المحولات - الصيانة - المشاريع)
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse">
                                </a>
                                <a href="#portlet-config" data-toggle="modal" class="config">
                                </a>
                                <a href="javascript:;" class="reload">
                                </a>
                                <a href="javascript:;" class="remove">
                                </a>
                            </div>
                        </div>

                        <div class="portlet-body">


                            <div class="row">

                                <div id="tech_indicators_chart" class="chart">


                                </div>



                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

    <?php
    }
    else

    {
        echo 'عذرا يمكنك متابعة المؤشرات خلال الساعة  الثامنة و النصف';
        die;
    }
}
else
{

    ?>
    <div class="row">

        <div class="row">
            <div class="col-md-12">

                <div class="col-md-6">
                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon icon-tasks"></i>    التحصيلات حسب النوع (النقدي - غير نقدي -تفتيش - الخدمات )
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse">
                                </a>
                                <a href="#portlet-config" data-toggle="modal" class="config">
                                </a>
                                <a href="javascript:;" class="reload">
                                </a>
                                <a href="javascript:;" class="remove">
                                </a>
                            </div>
                        </div>

                        <div class="portlet-body">


                            <div class="row">

                                <div id="process_collection_types" class="chart">


                                </div>



                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-md-6">
                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon icon-tasks"></i>    المؤشرات الفنية (المحولات - الصيانة - المشاريع)
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse">
                                </a>
                                <a href="#portlet-config" data-toggle="modal" class="config">
                                </a>
                                <a href="javascript:;" class="reload">
                                </a>
                                <a href="javascript:;" class="remove">
                                </a>
                            </div>
                        </div>

                        <div class="portlet-body">


                            <div class="row">

                                <div id="tech_indicators_chart" class="chart">


                                </div>



                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="col-md-6">
                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon icon-tasks"></i>    التحصيلات حسب النوع (النقدي - غير نقدي -تفتيش - الخدمات )
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse">
                                </a>
                                <a href="#portlet-config" data-toggle="modal" class="config">
                                </a>
                                <a href="javascript:;" class="reload">
                                </a>
                                <a href="javascript:;" class="remove">
                                </a>
                            </div>
                        </div>

                        <div class="portlet-body">


                            <div class="row">

                                <div id="process_collection_types" class="chart">


                                </div>



                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-md-6">
                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon icon-tasks"></i>    المؤشرات الفنية (المحولات - الصيانة - المشاريع)
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse">
                                </a>
                                <a href="#portlet-config" data-toggle="modal" class="config">
                                </a>
                                <a href="javascript:;" class="reload">
                                </a>
                                <a href="javascript:;" class="remove">
                                </a>
                            </div>
                        </div>

                        <div class="portlet-body">


                            <div class="row">

                                <div id="tech_indicators_chart" class="chart">


                                </div>



                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        </div>
<?php
}
$script =<<<SCRIPT
<script>
$('.collapse').collapse('toggle');
///////////////////////////////////////////////////
var mich_sub = [];
var pre_sub = [];
var trade_sub = [];
var complex_sub = [];
var home_sub = [];
var onefase_sub = [];
var threefase_sub = [];
var monthly_bills = [];
var totally_bills = [];
var realcollection = [];
var goalcollection = [];
var actualcollection = [];
var notconnectpay = [];
var zeroreads = [];
var micanicnotpay= [];
var prepaidcnotpay= [];
var services = [];
var inspectior = [];
var money    = [];
var notmoney = [];
var other_sub=[];
//var reminder=[];

get_data('{$get_total_subscriber}',{indecatior_id:1,form_month:'',to_month:''},function(data){
         $.each(data[0],function(index, item)
            {
            if(item.INDECATOR_SER==1)
            mich_sub.push(item.THE_VALUE);

            });
            $.each(data[1],function(index, item)
            {
            if(item.INDECATOR_SER==2)
            pre_sub.push(item.THE_VALUE);

            });
            $.each(data[2],function(index, item)
            {
            if(item.INDECATOR_SER==3)
            trade_sub.push(item.THE_VALUE);

            });
            $.each(data[3],function(index, item)
            {
            if(item.INDECATOR_SER==4)
            home_sub.push(item.THE_VALUE);

            });
            $.each(data[4],function(index, item)
            {
            if(item.INDECATOR_SER==5)
            monthly_bills.push(item.THE_VALUE);

            });
            $.each(data[5],function(index, item)
            {
            if(item.INDECATOR_SER==15)
            reminder.push(item.THE_VALUE);

            });
            $.each(data[6],function(index, item)
            {
            if(item.INDECATOR_SER==7)
            complex_sub.push(item.THE_VALUE);

            });
            $.each(data[7],function(index, item)
            {
            if(item.INDECATOR_SER==8)
            onefase_sub.push(item.THE_VALUE);

            });
            $.each(data[8],function(index, item)
            {
            if(item.INDECATOR_SER==9)
            threefase_sub.push(item.THE_VALUE);

            });
           /* $.each(data[9],function(index, item)
            {
            if(item.INDECATOR_SER==10)
            actualcollection.push(item.THE_VALUE);

            });*/
            $.each(data[10],function(index, item)
            {
            if(item.INDECATOR_SER==16)
            goalcollection.push(item.THE_VALUE);

            });
          /*  $.each(data[11],function(index, item)
            {
            realcollection.push(item.THE_VALUE);

            });*/
            $.each(data[11],function(index, item)
            {
            if(item.INDECATOR_SER==18)
            zeroreads.push(item.THE_VALUE);

            });
            $.each(data[12],function(index, item)
            {
            if(item.INDECATOR_SER==19)
            notconnectpay.push(item.THE_VALUE);

            });
            $.each(data[13],function(index, item)
            {
            if(item.INDECATOR_SER==20)
            micanicnotpay.push(item.THE_VALUE);

            });
            $.each(data[14],function(index, item)
            {
            if(item.INDECATOR_SER==21)
            prepaidcnotpay.push(item.THE_VALUE);

            });
            $.each(data[15],function(index, item)
            {
            if(item.INDECATOR_SER==10)
            {
            money.push(item.THE_VALUE);
             actualcollection.push(item.THE_VALUE);
            }

            });
            $.each(data[16],function(index, item)
            {
            if(item.INDECATOR_SER==11)
            {

            notmoney.push(item.THE_VALUE);
            }

            });
            $.each(data[17],function(index, item)
            {
            if(item.INDECATOR_SER==12)
            inspectior.push(item.THE_VALUE);

            });
            $.each(data[18],function(index, item)
            {
            if(item.INDECATOR_SER==13)
            services.push(item.THE_VALUE);

            });
             $.each(data[19],function(index, item)
            {
            if(item.INDECATOR_SER==14)
            other_sub.push(item.THE_VALUE);

            });
          /*  get_data('{$get_total_subscriber}',{indecatior_id:2,form_month:'',to_month:''},function(data){
         $.each(data,function(index, item)
            {
            pre_sub.push(item.THE_VALUE);

 });*/

//console.log(other_sub);

var totally_subscribers_bills_values = document.getElementById("totally_subscribers_bills_values");
var chart_totally_subscribers_bills_values = echarts.init(totally_subscribers_bills_values);
var app = {};
option = null;
app.title = 'اجمالي المشتركين و قيمة الفاتورة';

option = {
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'cross',
            crossStyle: {
                color: '#999'
            }
        }
    },
    toolbox: {
        feature: {


            //restore: {show: true},
            saveAsImage: {show: true}
        }
    },
    legend: {
              //data:['عدد المشتركين (ميكانيكي)','عدد المشتركين (مسبق دفع)','تجاري','منزلي','مركب','1 فاز','3 فاز']
              data:['عدد المشتركين (ميكانيكي)','عدد المشتركين (مسبق دفع)','تجاري','منزلي','مركب','تصنيفات أخرى','1 فاز','3 فاز']

    },
    xAxis: [
        {
            type: 'category',
           data: ['غزة','الشمال','الوسطى','خانيونس','رفح'],
            axisPointer: {
                type: 'shadow'
            }
        }
    ],
    yAxis: [
        {
            type: 'value',

        },
        {
            type: 'value',


        }
    ],
    series: [
        {
            name:'عدد المشتركين (ميكانيكي)',
            type:'bar',
            stack: 'type_paid',
            data:mich_sub
        },
        {
            name:'عدد المشتركين (مسبق دفع)',
            type:'bar',
              stack: 'type_paid',
            data:pre_sub
        },
          {
            name:'تجاري',
            type:'bar',
            stack: 'class_type',
            data:trade_sub
        },
        {
            name:'مركب',
            type:'bar',
              stack: 'class_type',
            data:complex_sub
        },
        {

            name:'تصنيفات أخرى',
            type:'bar',
            stack: 'class_type',
            data:other_sub
        }
        ,
        {
            name:'منزلي',
            type:'bar',
            stack: 'class_type',
            data:home_sub
        },
          {
            name:'1 فاز',
            type:'bar',
            stack: 'fas_type',
            data:onefase_sub
        },
        {
            name:'3 فاز',
            type:'bar',
              stack: 'fas_type',
            data:threefase_sub
        }/*,
        {
            name:'قيمة الفاتورة الشهرية',
            type:'line',
            yAxisIndex: 1,
            data:monthly_bills
        }
        ,
        {
            name:'قيمة الفاتورة الكلية',
            type:'line',
            yAxisIndex: 1,
            data:totally_bills
        }*/
    ]
};

if (option && typeof option === "object") {
    chart_totally_subscribers_bills_values.setOption(option, true);
}
 /*});
 });*/
/////////////
var collection_values = document.getElementById("collection_values");
var chart_collection_values = echarts.init(collection_values);
var app = {};
option = null;
app.title = 'قيمة التحصيلات';

option = {
    angleAxis: {
        type: 'category',
       data: ['غزة','الشمال','الوسطى','خانيونس','رفح'],
        z: 10
    } ,
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'cross',
            crossStyle: {
                color: '#999'
            }
        }
    },
    toolbox: {
        feature: {


           // restore: {show: true},
            saveAsImage: {show: true}
        }
    },
    radiusAxis: {
    },
    polar: {
    },
    series: [
     {
        type: 'bar',
        data: actualcollection,
        coordinateSystem: 'polar',
        name: 'الحقيقي',
        stack: 'a'
    },
     {
        type: 'bar',
        data: goalcollection,
        coordinateSystem: 'polar',
        name: 'المستهدف',
        stack: 'a'
    },/*,
    {
        type: 'bar',
        data: totally_bills,
        coordinateSystem: 'polar',
        name: 'قيمة الفاتورة الكلية',
        stack: 'a'
    }*/,
    {
        type: 'bar',
        data: monthly_bills,
        coordinateSystem: 'polar',
        name: 'قيمة الفاتورة الشهرية',
        stack: 'a'
    },],
    legend: {
        show: true,
         //data: ['قيمة الفاتورة الكلية','المستهدف', 'الحقيقي','قيمة الفاتورة الشهرية']
          data: ['المستهدف', 'الحقيقي','قيمة الفاتورة الشهرية']
    }
};
;
if (option && typeof option === "object") {
    chart_collection_values.setOption(option, true);
}
////////////////////////////////////////////////////////////////////
var process_subscribers_paid = document.getElementById("process_subscribers_paid");
var chart_process_subscribers_paid = echarts.init(process_subscribers_paid);
var app = {};
option = null;
app.title = 'معالجة الاشتركات الغير ملتزمة';

option = {
    tooltip : {
        trigger: 'axis',
        axisPointer : {
            type : 'shadow'
        }
    }
    ,
    toolbox: {
        feature: {


            //restore: {show: true},
            saveAsImage: {show: true}
        }
    },
    legend: {
      //  data:['مسبق دفع غير معالج( غير ملتزم )','فواتير معالجة(ملتزمة السداد','فواتير غير معالجة( غير ملتزمة السداد)','مسبق دفع معالج(ملتزم']
   data:['عدد الاشتركات الغير ملتزم بالسداد مكانيكي (لمدة 3 أشهر)','عدد الاشتركات الغير ملتزم بالشحن مسبق دفع(لمدة 3 أشهر)','القراءات الصفرية','اشتركات غير مرتبطة']
        },
    grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
    },
    xAxis : [
        {
            type : 'category',
             data: ['غزة','الشمال','الوسطى','خانيونس','رفح'],
        }
    ],
    yAxis : [
        {
            type : 'value'
        }
    ],
    series : [
        {

            name:'عدد الاشتركات الغير ملتزم بالسداد مكانيكي (لمدة 3 أشهر)',
            type:'bar',
            stack: 'notpaid',
            data:micanicnotpay

        },
        {

            name:'عدد الاشتركات الغير ملتزم بالشحن مسبق دفع(لمدة 3 أشهر)',
            type:'bar',
            stack: 'notpaid',
            data:prepaidcnotpay

        },
        {

             name:'اشتركات غير مرتبطة',
            type:'bar',
            data:notconnectpay

        },
        {
             name:'القراءات الصفرية',
            type:'bar',
             data:zeroreads

        }
    ]
};
;
if (option && typeof option === "object") {
    chart_process_subscribers_paid.setOption(option, true);
}
/////////////////////////////////////////////////////////////////////
var process_collection_types = document.getElementById("process_collection_types");
var chart_process_collection_types = echarts.init(process_collection_types);
var app = {};
option = null;
app.title = 'التحصيلات حسب النوع (نقدي - غير نقدي - تفتيش- الخدمات - التحصيل من المتأخرات)';

option = {
    tooltip : {
        trigger: 'axis',
        axisPointer : {
            type : 'shadow'
        }
    },
    toolbox: {
        feature: {


           // restore: {show: true},
            saveAsImage: {show: true}
        }
    },
    legend: {
       data: ['تفتيش','نقدي', 'غير نقدي','الخدمات و المعاملات',]
    // data: ['نقدي', 'غير نقدي','الخدمات و المعاملات','تفتيش',]
    },
    grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
    },
    xAxis:  {
        type: 'value'
    },
    yAxis: {
        type: 'category',
        data: ['غزة','الشمال','الوسطى','خانيونس','رفح'],
    },
    series: [
        {
            name: 'نقدي',
            type: 'bar',
            stack: 'collection',
            label: {
                normal: {
                    show: false,
                    position: 'insideRight'
                }
            },
            data: money
        },
        {
            name: 'غير نقدي',
            type: 'bar',
            stack: 'collection',
            label: {
                normal: {
                    show: false,
                    position: 'insideRight'
                }
            },
            data: notmoney
        },
        {
            name: 'تفتيش',
            type: 'bar',
            stack: 'collection',
            label: {
                normal: {
                    show: false,
                    position: 'insideRight'
                }
            },
            data: inspectior
        },
        {
            name: 'الخدمات و المعاملات',
            type: 'bar',
            stack: 'collection',
            label: {
                normal: {
                    show: false,
                    position: 'insideRight'
                }
            },
            data: services
        }/*,
        {
            name: 'التحصيل من المتأخرات',
            type: 'bar',
            stack: 'collection',
            label: {
                normal: {
                    show: false,
                    position: 'insideRight'
                }
            },
            data: reminder
        }*/
    ]
};;
if (option && typeof option === "object") {
    chart_process_collection_types.setOption(option, true);
}
 });
 /////////////////////////////////////////////////////////////////////////////////////////////////
 /////////////////////////////////////////////////////////////////////
var tech_indicators_chart = document.getElementById("tech_indicators_chart");
var chart_tech_indicators_chart = echarts.init(tech_indicators_chart);
var app = {};

option = {
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'cross',
            crossStyle: {
                color: '#999'
            }
        }
    },
    toolbox: {
        feature: {



            saveAsImage: {show: true}
        }
    },
    legend: {

              data:['المحولات','المشاريع','الصيانة']

    },
    xAxis: [
        {
            type: 'category',
           data: ['غزة','الشمال','الوسطى','خانيونس','رفح'],
            axisPointer: {
                type: 'shadow'
            }
        }
    ],
    yAxis: [
        {
            type: 'value',

        },
        {
            type: 'value',


        }
    ],
    series: [
        {
            name:'المحولات',
            type:'bar',

           data: [150, 232, 201, 154, 190]
        },
        {
           name:'الصيانة',
            type:'bar',

           data: [180, 201, 180, 120, 150]
        },
          {
           name:'المشاريع',
            type:'bar',

           data: [150, 112, 200, 140, 100]
        }
    ]
};


if (option && typeof option === "object") {
    chart_tech_indicators_chart.setOption(option, true);
}
</script>


SCRIPT;


sec_scripts($script);


?>