<div class="row">
    <div class="col-lg-3 col-md-4 col-sm-4">
        <div class="box box-info">
            <div class="box-body">
                <div class="text-center">
                    <h3><?php echo get_option('name'); ?></h3>
                    <em><?php echo get_option('slogan'); ?></em>
                    <br/>
                    <?php echo get_option('street'); ?>
                    <br/>
                    <?php echo get_option('city'); ?>
                    <?php echo get_option('state'); ?>,
                    <?php echo get_option('postal_code'); ?>
                    <?php echo get_option('country'); ?>
                </div>
            </div>
        </div>
        <div class="box box-success">
            <div class="box-body">
                <table class="table">
                    <tr>
                        <td><?php echo lang('Facility ID'); ?>:</td>
                        <td><?php echo get_option('facility_id'); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo lang('Tax ID'); ?>:</td>
                        <td><?php echo get_option('tax_id'); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo lang('email'); ?>:</td>
                        <td><?php echo get_option('email') ?></td>
                    </tr>
                    <tr>
                        <td><?php echo lang('phone'); ?>:</td>
                        <td><?php echo get_option('phone') ?></td>
                    </tr>
                    <tr>
                        <td><?php echo lang('fax'); ?>:</td>
                        <td><?php echo get_option('fax'); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="box box-warning">
            <div class="box-header with-border">
                <div class="box-title"><i class="fa fa-money"></i> <?php echo lang('invoices_due'); ?></div>
            </div>
            <div class="box-body">
                <?php echo lang('total'); ?>
                <span class="badge">
                    <?php echo $this->db->where('invoice_status', 2)->get('invoices')->num_rows(); ?>
                </span>
                <h2><?php echo get_option('currency_symbol') . $this->invoice->getTotalDue(); ?></h2>
            </div>
        </div>
        <div class="box box-primary">
            <ul class="nav nav-pills nav-stacked">
                <li class="active">
                    <?php echo anchor('reports/roster?daily&date='.date('Y-m-d'), '<i class="fa fa-file-pdf-o"></i> Children Roster', 'target="_blank"'); ?>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-lg-9 col-md-8 col-sm-8">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-xs-4">
                <!-- small box -->
                <div class="small-box bg-aqua cursor" onclick="location.href='<?php echo site_url('children'); ?>'">
                    <div class="inner">
                        <h3><?php echo $this->child->getCount(); ?></h3>
                        <p><?php echo lang('children'); ?></p>
                    </div>
                    <div class="icon"><i class="fa fa-users"></i></div>
                </div>
            </div>

            <div class="col-lg-2 col-md-2 col-xs-4">
                <div class="small-box bg-yellow cursor" onclick="location.href='<?php echo site_url('users'); ?>'">
                    <div class="inner">
                        <h3><?php echo $this->user->getCount(); ?></h3>
                        <p><?php echo lang('users'); ?></p>
                    </div>
                    <div class="icon"><i class="fa fa-user" aria-hidden="true"></i></div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-4">
                <div class="small-box bg-blue cursor" onclick="location.href='<?php echo site_url('users'); ?>'">
                    <div class="inner">
                        <h3><?php echo $this->user->getCount('parent'); ?></h3>
                        <p><?php echo lang('parents'); ?></p>
                    </div>
                    <div class="icon"><i class="fa fa-users" aria-hidden="true"></i></div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-4">
                <div class="small-box bg-teal cursor" onclick="location.href='<?php echo site_url('users'); ?>'">
                    <div class="inner">
                        <h3><?php echo $this->user->getCount('staff'); ?></h3>
                        <p><?php echo lang('staff'); ?></p>
                    </div>
                    <div class="icon"><i class="fa fa-users" aria-hidden="true"></i></div>
                </div>
            </div>

            <div class="col-lg-2 col-md-2 col-xs-4">
                <div class="small-box bg-green cursor" onclick="location.href='<?php echo site_url('children'); ?>'">
                    <div class="inner">
                        <h3><?php echo $this->db->select('id')->get('invoices')->num_rows(); ?></h3>
                        <p><?php echo lang('invoices'); ?></p>
                    </div>
                    <div class="icon"><i class="fa fa-credit-card" aria-hidden="true"></i></div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-4">
                <div class="small-box bg-gray cursor" onclick="location.href='<?php echo site_url('news'); ?>'">
                    <div class="inner">
                        <h3><?php echo $this->db->select('id')->get('news')->num_rows(); ?></h3>
                        <p><?php echo lang('News'); ?></p>
                    </div>
                    <div class="icon"><i class="fa fa-clipboard" aria-hidden="true"></i></div>
                </div>
            </div>
        </div>

        <div class="row hidden-xs">
            <section class="col-sm-12 connectedSortable">
                <?php $this->load->view('modules/calendar/widget'); ?>
            </section>
        </div>
    </div>
</div>
