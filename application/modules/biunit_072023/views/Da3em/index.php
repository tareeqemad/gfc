

<div class="container px-4 " id="featured-3" style="">

    <a href="" class="btn btn-secondary disabled analysis_ord" style="float: left" ><i class="icon icon-hand-o-up" style="margin-left: 5px"></i> <span>طلب تحليل</span></a>
<ul class="nav nav-pills mb-3 p-0" id="pills-tab" role="tablist">

    <?php $count=0; $id=0;?>
    <?php foreach ($categories as $key=>$row ){ ?>
       <?php $count==0?$id=$row['ID']:'';   ?>
    <li class="nav-item" role="presentation">
        <?php if($count==0){ ?>
        <button class="nav-link active" id="<?= 'area'.$row['ID'].'-tab' ?>" data-bs-toggle="pill" data-bs-target="<?= '#area'.$row['ID'] ?>" type="button" role="tab" aria-controls="<?= 'area'.$row['ID'] ?>" aria-selected="<?php $count==0?'true':'false';?>"><?= $row['TITLE']?></button>
        <?php   }else{ ?>
        <button class="nav-link <?php $count?'':'active'; ?>" id="<?= 'area'.$row['ID'].'-tab' ?>" data-bs-toggle="pill" data-bs-target="<?= '#area'.$row['ID'] ?>" type="button" role="tab" aria-controls="<?= 'area'.$row['ID'] ?>" aria-selected="<?php $count==0?'true':'false';?>"><?= $row['TITLE']?></button>
        <?php   } ?>
    </li>
       <?php $count= $count +1; ?>
    <?php   } ?>

</ul>
<div class="tab-content" id="pills-tabContent">
    <?php $count=0; ?>
    <?php foreach ($categories as $key=>$row ){ ?>
    <?php if($row['ID']==$id){ ?>
    <div class="tab-pane fade show active" id="<?= 'area'.$row['ID'] ?>" role="tabpanel" aria-labelledby="<?= 'area'.$row['ID'].'-tab' ?>" tabindex="0">
    <?php   }else{ ?>
     <div class="tab-pane fade" id="<?= 'area'.$row['ID'] ?>" role="tabpanel" aria-labelledby="<?= 'area'.$row['ID'].'-tab' ?>" tabindex="0">
     <?php   } ?>
        <div class="card-body row">
        <?php foreach ($dashboards as $key2=>$row2 ){
               if($row2['CATEGORY_ID'] ==$row['ID']){ ?>
                   <figure class="figure col-2" style="width: 13%" >
                       <a href="<?=base_url('biunit/Da3em/show_dashboard?id='.$row2['ID'])?>"  >
                           <img src="<?=base_url('assets/da3em/img/icons/'. $row2['ICON']) ?>" style="" class="figure-img img-fluid rounded" alt="...">
                           <figcaption class="figure-caption" style=""><?= $row2['TITLE'] ?></figcaption>
                       </a>
                   </figure>
               <?php   } ?>
       <?php   } ?>

        </div>

    </div>
        <?php $count= $count +1; ?>
    <?php   } ?>
</div>



</div>


