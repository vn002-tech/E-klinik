<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("rekam_medis/add");
$can_edit = ACL::is_allowed("rekam_medis/edit");
$can_view = ACL::is_allowed("rekam_medis/view");
$can_delete = ACL::is_allowed("rekam_medis/delete");
?>
<?php
$comp_model = new SharedController;
$page_element_id = "list-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
//Page Data From Controller
$view_data = $this->view_data;
$records = $view_data->records;
$record_count = $view_data->record_count;
$total_records = $view_data->total_records;
$field_name = $this->route->field_name;
$field_value = $this->route->field_value;
$view_title = $this->view_title;
$show_header = $this->show_header;
$show_footer = $this->show_footer;
$show_pagination = $this->show_pagination;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="list"  data-display-type="table" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container-fluid">
            <div class="row ">
                <div class="col ">
                    <h4 class="record-title">Rekam Medis</h4>
                </div>
                <div class="col-sm-3 ">
                    <?php if($can_add){ ?>
                    <a  class="btn btn btn-primary my-1" href="<?php print_link("rekam_medis/add") ?>">
                        <i class="fa fa-plus"></i>                              
                        Tambah Data 
                    </a>
                    <?php } ?>
                </div>
                <div class="col-sm-4 ">
                    <form  class="search" action="<?php print_link('rekam_medis'); ?>" method="get">
                        <div class="input-group">
                            <input value="<?php echo get_value('search'); ?>" class="form-control" type="text" name="search"  placeholder="Search" />
                                <div class="input-group-append">
                                    <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12 comp-grid">
                        <div class="">
                            <!-- Page bread crumbs components-->
                            <?php
                            if(!empty($field_name) || !empty($_GET['search'])){
                            ?>
                            <hr class="sm d-block d-sm-none" />
                            <nav class="page-header-breadcrumbs mt-2" aria-label="breadcrumb">
                                <ul class="breadcrumb m-0 p-1">
                                    <?php
                                    if(!empty($field_name)){
                                    ?>
                                    <li class="breadcrumb-item">
                                        <a class="text-decoration-none" href="<?php print_link('rekam_medis'); ?>">
                                            <i class="fa fa-angle-left"></i>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <?php echo (get_value("tag") ? get_value("tag")  :  make_readable($field_name)); ?>
                                    </li>
                                    <li  class="breadcrumb-item active text-capitalize font-weight-bold">
                                        <?php echo (get_value("label") ? get_value("label")  :  make_readable(urldecode($field_value))); ?>
                                    </li>
                                    <?php 
                                    }   
                                    ?>
                                    <?php
                                    if(get_value("search")){
                                    ?>
                                    <li class="breadcrumb-item">
                                        <a class="text-decoration-none" href="<?php print_link('rekam_medis'); ?>">
                                            <i class="fa fa-angle-left"></i>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item text-capitalize">
                                        Search
                                    </li>
                                    <li  class="breadcrumb-item active text-capitalize font-weight-bold"><?php echo get_value("search"); ?></li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </nav>
                            <!--End of Page bread crumbs components-->
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
        <div  class="">
            <div class="container-fluid">
                <div class="row ">
                    <div class="col-md-12 comp-grid">
                        <?php $this :: display_page_errors(); ?>
                        <div  class="   page-content">
                            <div id="rekam_medis-list-records">
                                <div id="page-report-body" class="table-responsive">
                                    <table class="table  table-striped table-sm text-left">
                                        <thead class="table-header">
                                            <tr>
                                                <?php if($can_delete){ ?>
                                                <th class="td-checkbox">
                                                    <label class="custom-control custom-checkbox custom-control-inline">
                                                        <input class="toggle-check-all custom-control-input" type="checkbox" />
                                                        <span class="custom-control-label"></span>
                                                    </label>
                                                </th>
                                                <?php } ?>
                                                <th class="td-sno">#</th>
                                                <th  class="td-id_medis"><i class="fa fa-database "></i> Id Medis</th>
                                                <th  class="td-tanggal_periksa"><i class="fa fa-calendar "></i> Tanggal Periksa</th>
                                                <th  class="td-nama_pasien"><i class="fa fa-user "></i> Nama Pasien</th>
                                                <th  class="td-keluhan"><i class="fa fa-drupal "></i> Keluhan</th>
                                                <th  class="td-dokter"><i class="fa fa-user-md "></i> Dokter</th>
                                                <th  class="td-diagnosa"><i class="fa fa-ambulance "></i> Diagnosa</th>
                                                <th  class="td-obat"><i class="fa fa-medkit "></i> Obat</th>
                                                <th  class="td-ruang"><i class="fa fa-bed "></i> Ruang</th>
                                                <th class="td-btn"></th>
                                            </tr>
                                        </thead>
                                        <?php
                                        if(!empty($records)){
                                        ?>
                                        <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                            <!--record-->
                                            <?php
                                            $counter = 0;
                                            foreach($records as $data){
                                            $rec_id = (!empty($data['id_medis']) ? urlencode($data['id_medis']) : null);
                                            $counter++;
                                            ?>
                                            <tr>
                                                <?php if($can_delete){ ?>
                                                <th class=" td-checkbox">
                                                    <label class="custom-control custom-checkbox custom-control-inline">
                                                        <input class="optioncheck custom-control-input" name="optioncheck[]" value="<?php echo $data['id_medis'] ?>" type="checkbox" />
                                                            <span class="custom-control-label"></span>
                                                        </label>
                                                    </th>
                                                    <?php } ?>
                                                    <th class="td-sno"><?php echo $counter; ?></th>
                                                    <td class="td-id_medis"><a href="<?php print_link("rekam_medis/view/$data[id_medis]") ?>"><?php echo $data['id_medis']; ?></a></td>
                                                    <td class="td-tanggal_periksa">
                                                        <span <?php if($can_edit){ ?> data-flatpickr="{altFormat: 'd-m-y', enableTime: false, minDate: '', maxDate: ''}" 
                                                            data-value="<?php echo $data['tanggal_periksa']; ?>" 
                                                            data-pk="<?php echo $data['id_medis'] ?>" 
                                                            data-url="<?php print_link("rekam_medis/editfield/" . urlencode($data['id_medis'])); ?>" 
                                                            data-name="tanggal_periksa" 
                                                            data-title="Enter Tanggal Periksa" 
                                                            data-placement="left" 
                                                            data-toggle="click" 
                                                            data-type="flatdatetimepicker" 
                                                            data-mode="popover" 
                                                            data-showbuttons="left" 
                                                            class="is-editable" <?php } ?>>
                                                            <?php echo $data['tanggal_periksa']; ?> 
                                                        </span>
                                                    </td>
                                                    <td class="td-nama_pasien">
                                                        <span <?php if($can_edit){ ?> data-source='<?php print_link('api/json/rekam_medis_nama_pasien_option_list'); ?>' 
                                                            data-value="<?php echo $data['nama_pasien']; ?>" 
                                                            data-pk="<?php echo $data['id_medis'] ?>" 
                                                            data-url="<?php print_link("rekam_medis/editfield/" . urlencode($data['id_medis'])); ?>" 
                                                            data-name="nama_pasien" 
                                                            data-title="Enter Nama Pasien" 
                                                            data-placement="left" 
                                                            data-toggle="click" 
                                                            data-type="text" 
                                                            data-mode="popover" 
                                                            data-showbuttons="left" 
                                                            class="is-editable" <?php } ?>>
                                                            <?php echo $data['nama_pasien']; ?> 
                                                        </span>
                                                    </td>
                                                    <td class="td-keluhan">
                                                        <span <?php if($can_edit){ ?> data-pk="<?php echo $data['id_medis'] ?>" 
                                                            data-url="<?php print_link("rekam_medis/editfield/" . urlencode($data['id_medis'])); ?>" 
                                                            data-name="keluhan" 
                                                            data-title="Enter Keluhan" 
                                                            data-placement="left" 
                                                            data-toggle="click" 
                                                            data-type="textarea" 
                                                            data-mode="popover" 
                                                            data-showbuttons="left" 
                                                            class="is-editable" <?php } ?>>
                                                            <?php echo $data['keluhan']; ?> 
                                                        </span>
                                                    </td>
                                                    <td class="td-dokter">
                                                        <span <?php if($can_edit){ ?> data-source='<?php print_link('api/json/rekam_medis_dokter_option_list'); ?>' 
                                                            data-value="<?php echo $data['dokter']; ?>" 
                                                            data-pk="<?php echo $data['id_medis'] ?>" 
                                                            data-url="<?php print_link("rekam_medis/editfield/" . urlencode($data['id_medis'])); ?>" 
                                                            data-name="dokter" 
                                                            data-title="Enter Dokter" 
                                                            data-placement="left" 
                                                            data-toggle="click" 
                                                            data-type="text" 
                                                            data-mode="popover" 
                                                            data-showbuttons="left" 
                                                            class="is-editable" <?php } ?>>
                                                            <?php echo $data['dokter']; ?> 
                                                        </span>
                                                    </td>
                                                    <td class="td-diagnosa">
                                                        <span <?php if($can_edit){ ?> data-pk="<?php echo $data['id_medis'] ?>" 
                                                            data-url="<?php print_link("rekam_medis/editfield/" . urlencode($data['id_medis'])); ?>" 
                                                            data-name="diagnosa" 
                                                            data-title="Enter Diagnosa" 
                                                            data-placement="left" 
                                                            data-toggle="click" 
                                                            data-type="textarea" 
                                                            data-mode="popover" 
                                                            data-showbuttons="left" 
                                                            class="is-editable" <?php } ?>>
                                                            <?php echo $data['diagnosa']; ?> 
                                                        </span>
                                                    </td>
                                                    <td class="td-obat">
                                                        <span <?php if($can_edit){ ?> data-source='<?php print_link('api/json/rekam_medis_obat_option_list'); ?>' 
                                                            data-value="<?php echo $data['obat']; ?>" 
                                                            data-pk="<?php echo $data['id_medis'] ?>" 
                                                            data-url="<?php print_link("rekam_medis/editfield/" . urlencode($data['id_medis'])); ?>" 
                                                            data-name="obat" 
                                                            data-title="Select a value ..." 
                                                            data-placement="left" 
                                                            data-toggle="click" 
                                                            data-type="selectize" 
                                                            data-mode="popover" 
                                                            data-showbuttons="left" 
                                                            class="is-editable selectize" <?php } ?>>
                                                            <?php echo $data['obat']; ?> 
                                                        </span>
                                                    </td>
                                                    <td class="td-ruang">
                                                        <span <?php if($can_edit){ ?> data-source='<?php print_link('api/json/rekam_medis_ruang_option_list'); ?>' 
                                                            data-value="<?php echo $data['ruang']; ?>" 
                                                            data-pk="<?php echo $data['id_medis'] ?>" 
                                                            data-url="<?php print_link("rekam_medis/editfield/" . urlencode($data['id_medis'])); ?>" 
                                                            data-name="ruang" 
                                                            data-title="Select a value ..." 
                                                            data-placement="left" 
                                                            data-toggle="click" 
                                                            data-type="select" 
                                                            data-mode="popover" 
                                                            data-showbuttons="left" 
                                                            class="is-editable" <?php } ?>>
                                                            <?php echo $data['ruang']; ?> 
                                                        </span>
                                                    </td>
                                                    <th class="td-btn">
                                                        <?php if($can_view){ ?>
                                                        <a class="btn btn-sm btn-outline-success has-tooltip" title="View Record" href="<?php print_link("rekam_medis/view/$rec_id"); ?>">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <?php } ?>
                                                        <?php if($can_edit){ ?>
                                                        <a class="btn btn-sm btn-outline-info has-tooltip" title="Edit This Record" href="<?php print_link("rekam_medis/edit/$rec_id"); ?>">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <?php } ?>
                                                        <?php if($can_delete){ ?>
                                                        <a class="btn btn-sm btn-outline-danger has-tooltip record-delete-btn" title="Delete this record" href="<?php print_link("rekam_medis/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Are you sure you want to delete this record?" data-display-style="modal">
                                                            <i class="fa fa-trash-o"></i>
                                                        </a>
                                                        <?php } ?>
                                                    </th>
                                                </tr>
                                                <?php 
                                                }
                                                ?>
                                                <!--endrecord-->
                                            </tbody>
                                            <tbody class="search-data" id="search-data-<?php echo $page_element_id; ?>"></tbody>
                                            <?php
                                            }
                                            ?>
                                        </table>
                                        <?php 
                                        if(empty($records)){
                                        ?>
                                        <h4 class="bg-light text-center border-top text-muted  bounce  p-3">
                                            <i class="fa fa-ban"></i> No record found
                                        </h4>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                    if( $show_footer && !empty($records)){
                                    ?>
                                    <div class=" border-top mt-2">
                                        <div class="row justify-content-center">    
                                            <div class="col-md-auto justify-content-center">    
                                                <div class="p-3 d-flex justify-content-between">    
                                                    <?php if($can_delete){ ?>
                                                    <button data-prompt-msg="Are you sure you want to delete these records?" data-display-style="modal" data-url="<?php print_link("rekam_medis/delete/{sel_ids}/?csrf_token=$csrf_token&redirect=$current_page"); ?>" class="btn btn-sm btn-danger btn-delete-selected d-none">
                                                        <i class="fa fa-trash-o"></i> Selected
                                                    </button>
                                                    <?php } ?>
                                                    <div class="dropup export-btn-holder mx-1">
                                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-save"></i> Export
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <?php $export_print_link = $this->set_current_page_link(array('format' => 'print')); ?>
                                                            <a class="dropdown-item export-link-btn" data-format="print" href="<?php print_link($export_print_link); ?>" target="_blank">
                                                                <img src="<?php print_link('assets/images/print.png') ?>" class="mr-2" /> PRINT
                                                                </a>
                                                                <?php $export_pdf_link = $this->set_current_page_link(array('format' => 'pdf')); ?>
                                                                <a class="dropdown-item export-link-btn" data-format="pdf" href="<?php print_link($export_pdf_link); ?>" target="_blank">
                                                                    <img src="<?php print_link('assets/images/pdf.png') ?>" class="mr-2" /> PDF
                                                                    </a>
                                                                    <?php $export_word_link = $this->set_current_page_link(array('format' => 'word')); ?>
                                                                    <a class="dropdown-item export-link-btn" data-format="word" href="<?php print_link($export_word_link); ?>" target="_blank">
                                                                        <img src="<?php print_link('assets/images/doc.png') ?>" class="mr-2" /> WORD
                                                                        </a>
                                                                        <?php $export_csv_link = $this->set_current_page_link(array('format' => 'csv')); ?>
                                                                        <a class="dropdown-item export-link-btn" data-format="csv" href="<?php print_link($export_csv_link); ?>" target="_blank">
                                                                            <img src="<?php print_link('assets/images/csv.png') ?>" class="mr-2" /> CSV
                                                                            </a>
                                                                            <?php $export_excel_link = $this->set_current_page_link(array('format' => 'excel')); ?>
                                                                            <a class="dropdown-item export-link-btn" data-format="excel" href="<?php print_link($export_excel_link); ?>" target="_blank">
                                                                                <img src="<?php print_link('assets/images/xsl.png') ?>" class="mr-2" /> EXCEL
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col">   
                                                                    <?php
                                                                    if($show_pagination == true){
                                                                    $pager = new Pagination($total_records, $record_count);
                                                                    $pager->route = $this->route;
                                                                    $pager->show_page_count = true;
                                                                    $pager->show_record_count = true;
                                                                    $pager->show_page_limit =true;
                                                                    $pager->limit_count = $this->limit_count;
                                                                    $pager->show_page_number_list = true;
                                                                    $pager->pager_link_range=5;
                                                                    $pager->render();
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
