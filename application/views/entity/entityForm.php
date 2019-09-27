<?php
//$primaryKeyField = $entity->primaryKeyField;
//$field1 = $entity->field1;
//$field2 = $entity->field2;

$primaryKeyField = null;
$userId = 2;
$field1 = '';
$field2 = '';
?>

<div class="page-wrapper mdc-toolbar-fixed-adjust">
	<main class="content-wrapper">
	<div class="mdc-layout-grid">
    <!-- Content Header (Page header) -->
	
    
    <div class="mdc-layout-grid__inner">
    
        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6">
            <!-- left column -->
            <div class="mdc-card">
				<section class="mdc-card__primary">
                  <h1 class="mdc-card__title mdc-card__title--large">Entity Info </h1>
                </section>
              <!-- general form elements -->
                <section class="mdc-card__supporting-text">
                  <div class="mdc-layout-grid__inner">
                    <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-4-desktop">
                      <div class="template-demo">
                        <div id="demo-tf-box-wrapper">
                          <div id="tf-box-example" class="mdc-text-field mdc-text-field--box w-100">
                            <input required pattern=".{8,}" type="text" id="tf-box" class="mdc-text-field__input" aria-controls="name-validation-message">
                            <label for="tf-box" class="mdc-text-field__label">Field 1</label>
                            <div class="mdc-text-field__bottom-line"></div>
                          </div>
                          <p class="mdc-text-field-helper-text mdc-text-field-helper-text--validation-msg" id="name-validation-msg">
                            Must be at least 8 characters
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-4-desktop">
                      <div class="template-demo">
                        <div id="demo-tf-box-leading-wrapper">
                          <div id="tf-box-leading-example" class="mdc-text-field mdc-text-field--box mdc-text-field--with-leading-icon w-100">
                            <i class="material-icons mdc-text-field__icon" tabindex="0">event</i>
                            <input type="text" id="tf-box-leading" class="mdc-text-field__input">
                            <label for="tf-box-leading" class="mdc-text-field__label">Field 2</label>
                            <div class="mdc-text-field__bottom-line"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>
                
                
                <div class="box box-primary">
                    
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>entity/create" method="post" id="createEntity" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Field 1</label>
                                        <input type="text" class="form-control" id="field1" placeholder="Field 1" name="field1" value="<?php echo $field1; ?>" maxlength="128">
                                        <input type="hidden" value="<?php echo $primaryKeyField; ?>" name="primaryKeyField" id="primaryKeyField" />    
										<input type="hidden" value="<?php echo $userId; ?>" name="createdBy" id="createdBy" />    
										<input type="hidden" value="<?php echo $userId; ?>" name="updatedBy" id="updatedBy" />    
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="field2">Field 2</label>
                                        <input type="text" class="form-control" id="field2" placeholder="Field 2" name="field2" value="<?php echo $field2; ?>" maxlength="128">
                                    </div>
                                </div>
                            </div>
                              
                            </div>
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
							<a href="<?php echo base_url() ?>entity/" class="btn btn-warning" >Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>  
</div>
</div>
</main>		
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/editUser.js" type="text/javascript"></script>