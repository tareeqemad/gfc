<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 15/04/18
 * Time: 10:57 ص
 */
$MODULE_NAME= 'planning';
$TB_NAME= 'planning_unit';
$timeline =base_url("$MODULE_NAME/$TB_NAME/public_get_timeline");
$Children=base_url("$MODULE_NAME/$TB_NAME/public_get_Children");
$tech=base_url("planning/planning/get/" );
$no_tech=$tech=base_url("planning/planning/get_tech_cost/" );


echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>


        </ul>

    </div>
</div>
<div class="form-body">

<div id="msg_container"></div>
<div id="container">
    <div id='calendar'></div>
</div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">


$(function() {
	var myEvents = [];
	var todayDate = moment().startOf('day');
	var YM = todayDate.format('YYYY-MM');
	var YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
	var TODAY = todayDate.format('YYYY-MM-DD');
	var TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');
	var year='{$year_paln}';
	var thisyear=year+'-01-01';
	var myResources = [];
	var myChildren = [];
    $.ajax({

        url: '{$timeline}',
        type: "post",
        success: function (e) {

            var data = $.parseJSON(e);
            $.each(data, function (key, value) {

            myResources.push({
                        id: value.SEQ,
                        OBJECTIVE: value.OBJECTIVE_NAME,
                        GOAL: value.GOAL_NAME,
                        GOAL_T: value.GOAL_T_NAME,
                        BRANCH_NAME: value.BRANCH_NAME,
                        PROJECT_NAME : value.PROJECT_NAME,
                         //WEIGHT:''*//*,
                         children:myChildren


                    });
                if (value.ACHIVE_COLOR=='1')
                {
if (value.CLASS=='1')
                {
                    myEvents.push({
                        id: value.SEQ,
                        resourceId: value.SEQ,
                        title: value.ACHIVE_TITLE_NAME,
                        start: value.D_FROM_MONTH,
                        end: value.D_TO_MONTH,
                        url:'{$tech}'+'/'+value.SEQ,
                        allDay: true
                    });

                    }
                    else
                    {
                     myEvents.push({
                        id: value.SEQ,
                        resourceId: value.SEQ,
                        title: value.ACHIVE_TITLE_NAME,
                        start: value.D_FROM_MONTH,
                        end: value.D_TO_MONTH,
                        url:'{$no_tech}'+'/'+value.SEQ,
                        allDay: true
                    });
                    }
                }
                else if (value.ACHIVE_COLOR=='2')
                {

                   if (value.CLASS=='1')
                {
                    myEvents.push({
                        id: value.SEQ,
                        resourceId: value.SEQ,
                        title: value.ACHIVE_TITLE_NAME,
                        start: value.D_FROM_MONTH,
                        end: value.D_TO_MONTH,
                        url:'{$tech}'+'/'+value.SEQ,
                         color: '#8775a7',
                        allDay: true
                    });

                    }
                    else
                    {
                     myEvents.push({
                        id: value.SEQ,
                        resourceId: value.SEQ,
                        title: value.ACHIVE_TITLE_NAME,
                        start: value.D_FROM_MONTH,
                        end: value.D_TO_MONTH,
                        url:'{$no_tech}'+'/'+value.SEQ,
                         color: '#8775a7',
                        allDay: true
                    });
                    }
                }


            });

            $('#calendar').fullCalendar({
  defaultDate: moment(thisyear),
  schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                isRTL:true,

                header: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'customYear'
                },
                editable: true,
                aspectRatio: 1.8,
                eventLimit: true, // allow "more" link when too many events
                navLinks: true,
                defaultView: 'customYear',
                views: {
                    customYear: {
                        type: 'timeline',
                        duration: { Years: 1 },
                        slotDuration: {months: 1}//,
                        //buttonText: 'Custom Week'
                    }

                },

               resourceAreaWidth: '60%',
                resourceColumns: [
                    {
                        group: true,
                        labelText: 'الغايات',
                        width: '70%',
                        field: 'OBJECTIVE'
                    },
                    {
                        group: true,
                        labelText: 'الاهداف الاستراتيجية',
                        width: '70%',
                        field: 'GOAL'
                    },
                    {
                        group: true,
                        labelText: 'الاهداف التشغيلية',
                        width: '50%',
                        field: 'GOAL_T'
                    },
                    {
                        group: true,
                        labelText: 'المقر',
                        width: '30%',
                        field: 'BRANCH_NAME'
                    }
                    ,
                    {
                        labelText: 'المشروع',
                        width: '70%',
                        field: 'PROJECT_NAME'
                    }/*,
                    {
                        labelText: 'الوزن',
                        width: '10%',
                        field: 'WEIGHT'
                    }*/
                ],
                refetchResourcesOnNavigate: true,

                resources:myResources,
                events: myEvents,


                eventRender: function (event, element, view) {
                    if (event.color) {
                        element.css('background-color', event.color)
                    }

                },


  eventClick: function(event) {
    if (event.url) {
      window.open(event.url);
      return false;
    }
  },

            });
        }
    });

    //$('#calendar').fullCalendar('gotoDate', '{$year_paln}');

    //alert(myEvents);
    //$('#calendar').fullCalendar({events: myEvents});

});


</script>

SCRIPT;

sec_scripts($scripts);

?>
