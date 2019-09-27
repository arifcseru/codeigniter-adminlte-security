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
	<div class="mdc-layout-grid">
	<main class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Entity Management
        <small>Add / Edit User</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Entity Info</h3>
                    </div><!-- /.box-header -->
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
    </section>
</div>

<script src="<?php echo base_url(); ?>assets/js/editUser.js" type="text/javascript"></script>