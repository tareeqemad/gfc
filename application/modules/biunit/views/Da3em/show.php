
<nav style="padding-right: 12px" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('biunit/Da3em/index')?>">الرئيسية</a></li> <i style="margin: 0px 5px;padding-top:5px; " class="icon icon-chevron-left"></i>
        <li class="breadcrumb-item"><a href="<?= base_url('biunit/Da3em/index')?>"><?= $dashboard[0]['CATEGORY_TITLE']?></a></li> <i style="margin: 0px 5px;padding-top:5px; " class="icon icon-chevron-left"></i>
        <li class="breadcrumb-item active" aria-current="page"><?= $dashboard[0]['TITLE']?></li>
    </ol>
</nav>

    <div class="container-fluid">
        <iframe style="min-height: inherit;width: 100%;border: solid 1px rgba(0,0,0,0.23);box-shadow: 1px 1px 10px 10px rgba(0,0,0,0.05);-webkit-box-shadow: 1px 1px 10px 10px rgba(0,0,0,0.05);-moz-box-shadow: 1px 1px 10px 10px rgba(0,0,0,0.05);"
                src="<?= $dashboard[0]['LINK'].'?rs:embed=true' ?>"></iframe>
    </div>


