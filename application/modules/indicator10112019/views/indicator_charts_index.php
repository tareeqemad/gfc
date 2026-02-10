<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 04/07/18
 * Time: 09:52 ص
 */
$MODULE_NAME= 'indicator';
$TB_NAME= 'indicate_charts';
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
                        <i class="icon icon-bar-chart"></i>تحصيل المقر حسب نوع الإشتراك
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

                        <div id="totally_subscribers_types" class="chart">






                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="col-md-12">
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


                            <div id="collection_values" class="chart" style="height: 690%">

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
</div>

    <div class="row">
        <div class="col-md-12">


            <div class="col-md-6">
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon icon-bar-chart"></i>    المؤشرات الفنية (المحولات - الصيانة - المشاريع)
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
            <div class="col-md-6">
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon icon-bar-chart"></i>    مؤشرات الأداء الوظيفي (الأذونات - التأخير الصباحي - الغياب بدون إذن - الإجازات)
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

                            <div id="manage_job_indicators_chart" class="chart">


                            </div>



                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>
<?php

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
var branch='{$this->user->branch}';

var branch_name=[];
if(branch==1)
{
branch_name=['غزة','الشمال','الوسطى','خانيونس','رفح'];
}
else if (branch==2)
{
branch_name=['غزة'];

}

else if (branch==3)
{

branch_name=['الشمال'];

}

else if (branch==4)
{

branch_name=['الوسطى'];

}

else if (branch==6)
{

branch_name=['خانيونس'];

}

else if (branch==7)
{

branch_name=['رفح'];
}

get_data('{$get_total_subscriber}',{indecatior_id:1,form_month:'',to_month:''},function(data){

         $.each(data[0],function(index, item)
            {

            if(item.INDECATOR_SER==1)

            {
            if(branch==1)
            {
            mich_sub.push(item.THE_VALUE);
            }
            else if(branch == item.BRANCH)
            {
             mich_sub.push(item.THE_VALUE);
            }
            }
            });
            $.each(data[1],function(index, item)
            {
            if(item.INDECATOR_SER==2)
            {
             if(branch==1)
            {
            pre_sub.push(item.THE_VALUE);
            }
              else if(branch == item.BRANCH)
            {
             pre_sub.push(item.THE_VALUE);
            }

            }

            });
            $.each(data[2],function(index, item)
            {
            if(item.INDECATOR_SER==3)
            {
            if(branch==1)
            {
            trade_sub.push(item.THE_VALUE);
            }
 else if(branch == item.BRANCH)
            {
            trade_sub.push(item.THE_VALUE);
            }
            }
            });
            $.each(data[3],function(index, item)
            {
            if(item.INDECATOR_SER==4)
            {
            if(branch==1)
            {
            home_sub.push(item.THE_VALUE);
            }
            else if(branch == item.BRANCH)
            {
           home_sub.push(item.THE_VALUE);
            }
            }
            });
            $.each(data[4],function(index, item)
            {
            if(item.INDECATOR_SER==5)
            {
            if(branch==1)
            {
            monthly_bills.push(item.THE_VALUE);
            }
            else if(branch == item.BRANCH)
            {
            monthly_bills.push(item.THE_VALUE);
            }
            }

            });
            $.each(data[5],function(index, item)
            {
            if(item.INDECATOR_SER==15)
            {
            if(branch==1)
            {
            reminder.push(item.THE_VALUE);
            }
}
 else if(branch == item.BRANCH)
            {
             reminder.push(item.THE_VALUE);
            }
            });
            $.each(data[6],function(index, item)
            {
            if(item.INDECATOR_SER==7)
            {
            if(branch==1)
            {
            complex_sub.push(item.THE_VALUE);
            }
             else if(branch == item.BRANCH)
            {
             complex_sub.push(item.THE_VALUE);
            }
}
            });
            $.each(data[7],function(index, item)
            {
            if(item.INDECATOR_SER==8)
            {
            if(branch==1)
            {
            onefase_sub.push(item.THE_VALUE);
            }
            else if(branch == item.BRANCH)
            {
               onefase_sub.push(item.THE_VALUE);
            }
}
            });
            $.each(data[8],function(index, item)
            {
            if(item.INDECATOR_SER==9)
            {
            if(branch==1)
            {
            threefase_sub.push(item.THE_VALUE);
            }
            else if(branch == item.BRANCH)
            {
                threefase_sub.push(item.THE_VALUE);
            }
}
            });
           /* $.each(data[9],function(index, item)
            {
            if(item.INDECATOR_SER==10)
            {
            actualcollection.push(item.THE_VALUE);
            }

            });*/
            $.each(data[10],function(index, item)
            {
            if(item.INDECATOR_SER==16)
            {
            if(branch==1)
            {
            goalcollection.push(item.THE_VALUE);
            }
            else if(branch == item.BRANCH)
            {
                goalcollection.push(item.THE_VALUE);
            }
}
            });
          /*  $.each(data[11],function(index, item)
            {
            realcollection.push(item.THE_VALUE);


            });*/
            $.each(data[11],function(index, item)
            {
            if(item.INDECATOR_SER==18)
            {
            if(branch==1)
            {
            zeroreads.push(item.THE_VALUE);
            }
            else if(branch == item.BRANCH)
            {
                    zeroreads.push(item.THE_VALUE);
            }
}
            });
            $.each(data[12],function(index, item)
            {
            if(item.INDECATOR_SER==19)
            {
            if(branch==1)
            {
            notconnectpay.push(item.THE_VALUE);
            }
             else if(branch == item.BRANCH)
            {
                 notconnectpay.push(item.THE_VALUE);
            }
}
            });
            $.each(data[13],function(index, item)
            {
            if(item.INDECATOR_SER==20)
            {
            if(branch==1)
            {
            micanicnotpay.push(item.THE_VALUE);
            }
              else if(branch == item.BRANCH)
            {
                micanicnotpay.push(item.THE_VALUE);
            }
            }

            });
            $.each(data[14],function(index, item)
            {
            if(item.INDECATOR_SER==21)
            {
            if(branch==1)
            {
            prepaidcnotpay.push(item.THE_VALUE);
            }
            else if(branch == item.BRANCH)
            {
                prepaidcnotpay.push(item.THE_VALUE);
            }
}
            });
            $.each(data[15],function(index, item)
            {
            if(item.INDECATOR_SER==10)
            {
            if(branch==1)
            {
            money.push(item.THE_VALUE);
             actualcollection.push(item.THE_VALUE);
            }
            else if(branch == item.BRANCH)
            {

                 money.push(item.THE_VALUE);
             actualcollection.push(item.THE_VALUE);

            }
}
            });
            $.each(data[16],function(index, item)
            {
            if(item.INDECATOR_SER==11)
            {

if(branch==1)
            {
            notmoney.push(item.THE_VALUE);
            }
            else if(branch == item.BRANCH)
            {
                   notmoney.push(item.THE_VALUE);
            }
}
            });
            $.each(data[17],function(index, item)
            {
            if(item.INDECATOR_SER==12)
            {
            if(branch==1)
            {
            inspectior.push(item.THE_VALUE);
            }
            else if(branch == item.BRANCH)
            {
                   inspectior.push(item.THE_VALUE);
            }
            }

            });
            $.each(data[18],function(index, item)
            {
            if(item.INDECATOR_SER==13)
            {
            if(branch==1)
            {
            services.push(item.THE_VALUE);
            }
             else if(branch == item.BRANCH)
            {
                 services.push(item.THE_VALUE);
            }
            }

            });
             $.each(data[19],function(index, item)
            {
            if(item.INDECATOR_SER==14)
            {
            if(branch==1)
            {
            other_sub.push(item.THE_VALUE);
            }
             else if(branch == item.BRANCH)
            {
                other_sub.push(item.THE_VALUE);
            }
}
            });
          /*  get_data('{$get_total_subscriber}',{indecatior_id:2,form_month:'',to_month:''},function(data){
         $.each(data,function(index, item)
            {
            pre_sub.push(item.THE_VALUE);

 });*/

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
           data: branch_name,
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
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 var totally_subscribers_types = document.getElementById("totally_subscribers_types");
var chart_totally_subscribers_types = echarts.init(totally_subscribers_types);



// option
option = {

    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'shadow'
        }
    },
    legend: {
        //data: ['المستهدف لاشتراكات التجارية', 'المحصل لاشتركات التجارية'],
        textStyle: {
            color: '#ccc'
        }
    },
    xAxis: {
        data: branch_name,

    },
    yAxis: {
        splitLine: {show: false},

    },
    series: [{
        name: 'المستهدف لاشتراكات التجارية',
        type: 'line',
          stack: 'trade_class_type',
        smooth: true,
        showAllSymbol: true,
        symbol: 'emptyCircle',
       //symbolSize: 15,
        data: [1000,2000,3000,4000,5000]
    }

    , {
        name: 'المحصل لاشتركات التجارية',
        type: 'bar',
        stack: 'trade_class_type',
        //barWidth: 10,
        itemStyle: {
            normal: {
          //     barBorderRadius: 5,

            }
        },
        data: [6000,7000,8000,9000,10000]
    }, {
        name: 'الاشتركات التجارية الغير محصلة ',
        type: 'bar',
        stack: 'trade_class_type',
        symbol: 'rect',
        itemStyle: {
            normal: {
               color: '#0f375f'
            }
        },
         //barWidth: 10,
        itemStyle: {
            normal: {
             //  barBorderRadius: 5,

            }
        },
        data: [1000,2000,3000,4000,5000]
    },
    {
      name: 'المستهدف لاشتراكات المنزلية',
        type: 'line',
        stack: 'home_class_type',
        smooth: true,
        showAllSymbol: true,
        symbol: 'emptyCircle',
       //symbolSize: 15,
        data: [6000,7000,8000,9000,10000]
    }

    , {
        name: 'المحصل لاشتركات المنزلية',
        type: 'bar',
        stack: 'home_class_type',
        //barWidth: 10,
        itemStyle: {
            normal: {
          //     barBorderRadius: 5,

            }
        },
        data: [1000,2000,3000,4000,5000]
    }, {
       name: 'الاشتركات المنزلية الغير محصلة ',
        type: 'bar',
        stack: 'home_class_type',
        symbol: 'rect',
        itemStyle: {
            normal: {
               color: '#0f375f'
            }
        },
         //barWidth: 10,
        itemStyle: {
            normal: {
             //  barBorderRadius: 5,

            }
        },
        data: [6000,7000,8000,9000,10000]
    },
    ,
    {
     name: 'المستهدف لاشتراكات المركبة',
        type: 'line',
        stack: 'complex_class_type',
        smooth: true,
        showAllSymbol: true,
        symbol: 'emptyCircle',
       //symbolSize: 15,
         data: [1000,2000,3000,4000,5000]
    }

    , {
       name: 'المحصل لاشتركات المركبة',
        type: 'bar',
        stack: 'complex_class_type',
        //barWidth: 10,
        itemStyle: {
            normal: {
          //     barBorderRadius: 5,

            }
        },
        data: [6000,7000,8000,9000,10000]
    }, {
        name: 'الاشتركات المركبة الغير محصلة ',
        type: 'bar',
        stack: 'complex_class_type',
        symbol: 'rect',
        itemStyle: {
            normal: {
               color: '#0f375f'
            }
        },
         //barWidth: 10,
        itemStyle: {
            normal: {
             //  barBorderRadius: 5,

            }
        },
         data: [1000,2000,3000,4000,5000]
    },
    ,
    {
     name: 'المستهدف لاشتراكات التصنيفات الاخرى',
        type: 'line',
        stack: 'other_class_type',
        smooth: true,
        showAllSymbol: true,
        symbol: 'emptyCircle',
       //symbolSize: 15,
        data: [1950,2285,3335,4845,5770]
    }

    , {
     name: 'المحصل لاشتركات التصنيفات الاخرى',
        type: 'bar',
        stack: 'other_class_type',
        //barWidth: 10,
        itemStyle: {
            normal: {
          //     barBorderRadius: 5,

            }
        },
         data: [1000,2000,3000,4000,5000]
    }, {
       name: 'اشتركات  التصنيفات الاخرى الغير محصلة ',
        type: 'bar',
        stack: 'other_class_type',
        symbol: 'rect',
        itemStyle: {
            normal: {
               color: '#0f375f'
            }
        },
         //barWidth: 10,
        itemStyle: {
            normal: {
             //  barBorderRadius: 5,

            }
        },
        data: [6000,7000,8000,9000,10000]
    }]
};

if (option && typeof option === "object") {
    chart_totally_subscribers_types.setOption(option, true);
}
 /*});
 });*/
 //////////////////////////////////////////////////////////
var collection_values = document.getElementById("collection_values");
var chart_collection_values = echarts.init(collection_values);
var app = {};
option = null;
app.title = 'قيمة التحصيلات';

option = {
    angleAxis: {
        type: 'category',
     data: branch_name,
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
        data: [10005500,2055000,30055500,4055000,5005005],//monthly_bills,
        coordinateSystem: 'polar',
        name: 'قيمة الفاتورة الشهرية',
        stack: 'a'
    },
     {
        type: 'bar',
        data: [10080000,2008000,3000800,40000880,5000800],//actualcollection,
        coordinateSystem: 'polar',
        name: 'الحقيقي',
        stack: 'a'
    },
     {
        type: 'bar',
        data: [10000000,2000000,3000000,4000000,5000000],//goalcollection,
        coordinateSystem: 'polar',
        name: 'المستهدف',
        stack: 'a'
    }

    /*,
    {
        type: 'bar',
        data: totally_bills,
        coordinateSystem: 'polar',
        name: 'قيمة الفاتورة الكلية',
        stack: 'a'
    }*/,
   ,],
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
            data: branch_name,
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
        data: branch_name,
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
          data: branch_name,
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

           data: [150, 232, 201, 154, 190],
            markPoint : {
                data : [
                    {type : 'max', name: ''},
                    {type : 'min', name: ''}
                ]
            }
        },
        {
           name:'الصيانة',
            type:'bar',

           data: [180, 201, 180, 120, 150],
            markPoint : {
                data : [
                    {type : 'max', name: ''},
                    {type : 'min', name: ''}
                ]
            }
        },
          {
           name:'المشاريع',
            type:'bar',

           data: [150, 112, 200, 140, 100],
            markPoint : {
                data : [
                    {type : 'max', name: ''},
                    {type : 'min', name: ''}
                ]
            }
        }
    ]
};


if (option && typeof option === "object") {
    chart_tech_indicators_chart.setOption(option, true);
}
///////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
var manage_job_indicators_chart = document.getElementById("manage_job_indicators_chart");
var chart_manage_job_indicators_chart = echarts.init(manage_job_indicators_chart);
var app = {};

option = {
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'shadow',
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

              data:['الأذونات الخاصة', 'التأخير الصباحي', 'الغياب بدون إذن', 'الإجازات']

    },
    xAxis: [
        {
            type: 'category',
         data: branch_name,
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
            name:'الأذونات الخاصة',
            type:'bar',

           data: [150, 232, 201, 154, 190],
            markPoint : {
                data : [
                    {type : 'max', name: ''},
                    {type : 'min', name: ''}
                ]
            }
        },
        {
           name:'التأخير الصباحي',
            type:'bar',

           data: [180, 201, 180, 120, 150],
            markPoint : {
                data : [
                    {type : 'max', name: ''},
                    {type : 'min', name: ''}
                ]
            }
        },
          {
           name:'الغياب بدون إذن',
            type:'bar',

           data: [150, 112, 200, 140, 100],
            markPoint : {
                data : [
                    {type : 'max', name: ''},
                    {type : 'min', name: ''}
                ]
            }
        },
          {
           name:'الإجازات',
            type:'bar',

           data: [150, 112, 200, 140, 100],
            markPoint : {
                data : [
                    {type : 'max', name: ''},
                    {type : 'min', name: ''}
                ]
            }
        }
    ]
};


if (option && typeof option === "object") {
    chart_manage_job_indicators_chart.setOption(option, true);
}
</script>


SCRIPT;


sec_scripts($script);


?>