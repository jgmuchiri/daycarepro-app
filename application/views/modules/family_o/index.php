<button class="btn btn-primary pull-right" onclick="window.location.href='<?php echo site_url('children/register'); ?>'">
    <span class="fa fa-plus"></span>
    <?php echo lang('register') . ' ' . lang('child'); ?>
</button>
<div class="row">
	<div class="col-md-12">
		<?php foreach ($children->result() as $row) : ?>
		<div class="col-sm-3 col-md-3 col-lg-3" style="width:320px">
			<div class="box box-solid box-success">
				<div class="box-header">
					<div class="box-title">
						<?php echo $row->lname . ', ' . $row->fname; ?>
					</div>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-sm-5 col-md-5 col-lg-5 image pull-left">
							<div>
								<?php
							if ($row->photo !== "") {
								echo '<img class="img-circle"
         src="' . base_url() . 'assets/img/users/children/' . $row->photo . '" style="width: 120px; height:120px"/>';
							} else {
								echo '<img class="img-circle"
         src="' . base_url() . 'assets/img/content/no-image.png" style="width: 120px; height:120px"/>';
							}
							?>
							</div>
						</div>
						<div class="col-sm-7 col-md-7 col-lg-7 pull-right">
							<span class="label label-info"><?php echo lang('birthday'); ?></span><br/>
							<?php echo $row->bday; ?>
							<br/>

							<div class="bg-warning">
                    <span
						class="badge"><?php echo $this->child->totalRecords('child_notes', $row->id); ?></span>
								<?php echo lang('notes'); ?>
							</div>
							<div class="bg-warning">
                    <span
						class="badge"><?php echo $this->child->totalrecords('child_meds', $row->id); ?></span>
								<?php echo lang('medications'); ?>
							</div>
							<div class="bg-warning">
                    <span
						class="badge"><?php echo $this->child->totalRecords('child_allergy', $row->id); ?></span>
								<?php echo lang('allergies'); ?>
							</div>

							<br/>
							<span class="label label-info"><?php echo lang('status'); ?></span>
							<?php echo lang($this->child->status($row->status)); ?>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<a href="<?php echo site_url('family/view_child/' . $row->child_id); ?>"
					   class="btn btn-info btn-flat btn-sm viewChild">
						<span class="fa fa-eye-open"></span> <?php echo lang('open'); ?>
					</a>
				</div>
			</div>
		</div>
		<?php endforeach ?>
	</div>
</div>