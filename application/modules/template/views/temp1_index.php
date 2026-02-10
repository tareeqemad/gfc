<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
                <div class="card-options">
                    <?php /*if( HaveAccess($delay_sum_url)): */ ?>
                    <a class="btn btn-secondary" href="<?/*= $delay_sum_url*/ ?>"><i class="fa fa-plus"></i>
                        جديد
                    </a>
                    <?php /*endif;*/ ?>
                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-2">
                                <label> المقر</label>
                                <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                                    <option value="">_______</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option value="<?= $row['NO'] ?>"> <?= $row['NAME'] ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="branch_no" id="dp_branch_no" value="<?= $this->user->branch ?>">
                        <?php } ?>

                        <div class="form-group col-md-2">
                            <label>الشهر</label>
                            <input type="text" placeholder="الشهر" name="month" id="txt_month" class="form-control"
                                   value="<?= date('Ym') ?>">
                        </div>
                    </div>

                    <div class="row">

                    </div>
                </form>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i class="fa fa-search"></i> إستعلام</button>
                    <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success"><i class="fa fa-file-excel-o"></i>إكسل</button>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-cyan-light"><i class="fa fa-eraser"></i>تفريغ الحقول</button>
                </div>
                <hr>
                <div id="container">

                </div>

            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade"  id="public_modal" tabindex="-1" role="dialog" >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">بيانات</h5>
                    <button  class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button  class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button  class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>






<!-- ROW-2 OPEN -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h3 class="card-title">Tabs Style-3</h3>
            </div>
            <div class="card-body p-6">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading border-bottom-0">
                        <div class="tabs-menu4 border-bottomo-sm">
                            <!-- Tabs -->
                            <nav class="nav d-sm-flex d-block">
                                <a class="nav-link border border-bottom-0 br-sm-5 me-2 active" data-bs-toggle="tab" href="#tab25">
                                    Tab 1
                                </a>
                                <a class="nav-link border border-bottom-0 br-sm-5 me-2" data-bs-toggle="tab" href="#tab26">
                                    Tab 2
                                </a>
                                <a class="nav-link border border-bottom-0 br-sm-5 me-2" data-bs-toggle="tab" href="#tab27">
                                    Tab 3
                                </a>
                                <a class="nav-link border border-bottom-lg-0 br-sm-5 me-2" data-bs-toggle="tab" href="#tab28">
                                    Tab 4
                                </a>
                            </nav>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body">
                        <div class="tab-content">
                            <div class="tab-pane active " id="tab25">
                                <p>Eirmod eirmod sit dolor lorem diam clita aliquyam at et. Sea eirmod sit kasd amet, sit invidunt clita magna magna elitr duo. Et labore kasd no ea consetetur ut consetetur duo voluptua..</p>
                                <p>Diam gubergren dolor sea eos duo. Nonumy consetetur amet diam amet consetetur sed eirmod dolore. At dolore est ut ipsum.Duo sit diam sit et erat gubergren accusam invidunt justo, ea justo no no nonumy eirmod duo, no stet vero stet dolor sea sadipscing, est sit ipsum accusam et no, nonumy eirmod.</p>
                            </div>
                            <div class="tab-pane" id="tab26">
                                <p>Sit labore ipsum sit lorem duo diam vero duo invidunt, dolor nonumy eirmod justo erat duo, dolore diam dolore sadipscing.Et est no amet elitr eos sit lorem aliquyam rebum, gubergren dolores sadipscing sit invidunt rebum dolores, at kasd ea dolores et. Ipsum sadipscing lorem sed voluptua justo. Dolor at elitr sit.</p>
                                <p>At et erat et labore stet, vero sea diam sea ipsum est sea consetetur rebum consetetur. Erat kasd diam lorem.Et est et takimata ipsum rebum amet. Et sed stet eirmod sanctus et ipsum eos. Eos dolor erat sit sea. Kasd justo rebum clita aliquyam. Sanctus lorem eos sea et ipsum sanctus.</p>
                            </div>
                            <div class="tab-pane" id="tab27">
                                <p>Sanctus sed aliquyam amet labore labore diam amet magna takimata dolore, amet sed elitr clita sanctus ipsum, ipsum et gubergren.Amet sed amet justo sea elitr at, amet ut sed consetetur ut dolor ut nonumy, dolores labore lorem et labore elitr clita dolor ipsum takimata, kasd duo eos duo ut at. Amet.</p>
                                <p>Lorem amet nonumy nonumy amet est sed gubergren at dolor, est no justo stet sea lorem diam dolore dolores voluptua, sea labore rebum sadipscing consetetur accusam, stet takimata erat eos diam, dolor.</p>
                            </div>
                            <div class="tab-pane" id="tab28">
                                <p>Erat ipsum kasd sadipscing ea dolor. Sit diam consetetur clita sed clita ut. Amet diam diam magna eos stet at.Sadipscing dolor consetetur et gubergren erat et. Magna clita ea sadipscing sit diam sit labore justo. Sea et magna vero sadipscing sit et dolores et elitr. Eos lorem nonumy sit est sed.</p>
                                <p>Diam aliquyam sit sanctus et invidunt elitr. Gubergren elitr vero kasd et elitr et diam, takimata est consetetur et sed.Consetetur sadipscing magna diam sadipscing tempor, no invidunt invidunt accusam ut voluptua no vero duo amet, et magna diam elitr et ea justo aliquyam takimata tempor. Eirmod sit et erat dolores invidunt.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ROW-2 CLOSED -->
