<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("rekam_medis/add");
$can_edit = ACL::is_allowed("rekam_medis/edit");
$can_view = ACL::is_allowed("rekam_medis/view");
$can_delete = ACL::is_allowed("rekam_medis/delete");
?>
<?php
$comp_model = new SharedController;
$page_element_id = "view-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
//Page Data Information from Controller
$data = $this->view_data;
//$rec_id = $data['__tableprimarykey'];
$page_id = $this->route->page_id; //Page id from url
$view_title = $this->view_title;
$show_header = $this->show_header;
$show_edit_btn = $this->show_edit_btn;
$show_delete_btn = $this->show_delete_btn;
$show_export_btn = $this->show_export_btn;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="view"  data-display-type="table" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col ">
                    <h4 class="record-title">View  Rekam Medis</h4>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
    <div  class="">
        <div class="container">
            <div class="row ">
                <div class="col-md-12 comp-grid">
                    <?php $this :: display_page_errors(); ?>
                    <div  class="card   page-content">
                        <?php
                        $counter = 0;
                        if(!empty($data)){
                        $rec_id = (!empty($data['id_medis']) ? urlencode($data['id_medis']) : null);
                        $counter++;
                        ?>
                        <div id="page-report-body" class="">
                            <table class="table table-hover table-borderless table-striped">
                                <!-- Table Body Start -->
                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                    <tr  class="td-id_medis">
                                        <th class="title"><i class="fa fa-database "></i> Id Medis: </th>
                                        <td class="value"> <?php echo $data['id_medis']; ?></td>
                                    </tr>
                                    <tr  class="td-tanggal_periksa">
                                        <th class="title"><i class="fa fa-calendar "></i> Tanggal Periksa: </th>
                                        <td class="value">
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
                                    </tr>
                                    <tr  class="td-nama_pasien">
                                        <th class="title"><i class="fa fa-user "></i> Nama Pasien: </th>
                                        <td class="value">
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
                                    </tr>
                                    <tr  class="td-keluhan">
                                        <th class="title"><i class="fa fa-drupal "></i> Keluhan: </th>
                                        <td class="value">
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
                                    </tr>
                                    <tr  class="td-dokter">
                                        <th class="title"><i class="fa fa-user-md "></i> Dokter: </th>
                                        <td class="value">
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
                                    </tr>
                                    <tr  class="td-diagnosa">
                                        <th class="title"><i class="fa fa-ambulance "></i> Diagnosa: </th>
                                        <td class="value">
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
                                    </tr>
                                    <tr  class="td-obat">
                                        <th class="title"><i class="fa fa-medkit "></i> Obat: </th>
                                        <td class="value">
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
                                    </tr>
                                    <tr  class="td-ruang">
                                        <th class="title"><i class="fa fa-bed "></i> Ruang: </th>
                                        <td class="value">
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
                                    </tr>
                                </tbody>
                                <!-- Table Body End -->
                            </table>
                        </div>
                        <div class="p-3 d-flex">
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
                                                <?php if($can_edit){ ?>
                                                <a class="btn btn-sm btn-info"  href="<?php print_link("rekam_medis/edit/$rec_id"); ?>">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <?php } ?>
                                                <?php if($can_delete){ ?>
                                                <a class="btn btn-sm btn-danger record-delete-btn mx-1"  href="<?php print_link("rekam_medis/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Are you sure you want to delete this record?" data-display-style="modal">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                                <?php } ?>
                                            </div>
                                            <?php
                                            }
                                            else{
                                            ?>
                                            <!-- Empty Record Message -->
                                            <div class="text-muted p-3">
                                                <i class="fa fa-ban"></i> No Record Found
                                            </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
