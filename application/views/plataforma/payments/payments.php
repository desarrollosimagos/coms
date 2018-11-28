<!-- FooTable -->
<!--<link href="<?php echo assets_url('css/plugins/footable/footable.bootstrap.css');?>" rel="stylesheet">-->
<link href="<?php echo assets_url('css/plugins/footable/footable.core.css');?>" rel="stylesheet">
<style>
.views-number {
    font-size: 18px !important;
}
</style>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('page_payment_heading_title'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('page_payment_heading_home'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo $this->lang->line('page_payment_heading_subtitle'); ?></strong>
            </li>
        </ol>
    </div>
</div>

<!-- Campo oculto que almacena el url base del proyecto -->
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
<!-- Campos ocultos que almacenan el tipo de moneda de la cuenta del usuario logueado -->
<input type="hidden" id="iso_currency_user" value="<?php echo $this->session->userdata('logged_in')['coin_iso']; ?>">
<input type="hidden" id="symbol_currency_user" value="<?php echo $this->session->userdata('logged_in')['coin_symbol']; ?>">
<input type="hidden" id="decimals_currency_user" value="<?php echo $this->session->userdata('logged_in')['coin_decimals']; ?>">

<!-- Campos ocultos que almacenan los nombres del menú y el submenú de la vista actual -->
<input type="hidden" id="ident" value="<?php echo $ident; ?>">
<input type="hidden" id="ident_sub" value="<?php echo $ident_sub; ?>">

<div class="wrapper wrapper-content animated fadeInUp">
	
	<!-- Alerta para el mensaje de la api de openexchangerates -->
	<?php if($openexchangerates_message['type'] == 'error'){ ?>
	<div class="col-lg-12 alert alert-danger alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?php 
		echo $this->lang->line('openexchangerates_message_error');
		?>
	</div>
	<?php }else if($openexchangerates_message['type'] == 'message1'){ ?>
	<div class="col-lg-12 alert alert-success alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?php 
		echo $this->lang->line('openexchangerates_message');
		?>
	</div>
	<?php }else{ ?>
		<div class="col-lg-12 alert alert-success alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?php 
		echo $this->lang->line('openexchangerates_message2');
		?>
	</div>
	<?php } ?>
	
	<!-- Alerta para cuando el mensaje de la api de coinmarketcap es un error -->
	<?php if($coinmarketcap_message['type'] == 'error'){ ?>
	<div class="col-lg-12 alert alert-danger alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?php 
		echo $this->lang->line('coinmarketcap_message_error');
		?>
	</div>
	<?php } ?>
	
	<!-- Alerta para cuando el mensaje de la api de dolartoday es un error -->
	<?php if($coin_rate_message['type'] == 'error'){ ?>
	<div class="col-lg-12 alert alert-danger alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?php 
		if($coin_rate_message['message'] == '1' || $coin_rate_message['message'] == '2'){
			echo $this->lang->line('coin_rate_message');
		}
		?>
	</div>
	<?php } ?>
	
	
	<!-- Cuerpo de la sección de contratos pendientes de pago -->
	<div class="ibox">
		<div class="ibox-title">
			<h5><?php echo $this->lang->line('payment_contracts_title'); ?></h5>
			
			<div class="ibox-tools">
				<a class="collapse-link">
					<i class="fa fa-chevron-up"></i>
				</a>
				<a class="close-link">
					<i class="fa fa-times"></i>
				</a>
			</div>
		</div>
		<div class="ibox-content">
			
			<?php $filter_profile = array(1, 2, 3, 4); ?>
			<?php if(in_array($this->session->userdata('logged_in')['profile_id'], $filter_profile)){ ?> 
			<div class="col-sm-4 col-md-offset-8">
				<div class="input-group">
					<input type="text" placeholder="Search in table" class="input-sm form-control" id="filter_contracts">
					<span class="input-group-btn">
						<button type="button" class="btn btn-sm btn-primary"> Go!</button>
					</span>
				</div>
			</div>
			<?php } ?>

			<table id="tab_accounts"  data-page-size="10" data-filter=#filter_contracts class="footable table table-stripped toggle-arrow-tiny">
				<thead>
					<tr class='text-center'>
						<th></th>
						<th>ID</th>
						<th ><?php echo $this->lang->line('payment_contracts_user'); ?></th>
						<th data-hide="phone" ><?php echo $this->lang->line('payment_contracts_account'); ?></th>
						<th data-hide="phone" ><?php echo $this->lang->line('payment_contracts_amount'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; ?>
					<?php foreach ($contratos as $contrato) { ?>
						<tr class='text-center'>
							<td>
								<div class="i-checks">
									<label>
										<input class="checkbox" type="checkbox" name="real" id="contract_<?php echo $contrato->id; ?>">
									</label>
								</div>
							</td>
							<td>
								<?php echo $contrato->id; ?>
							</td>
							<td>
								<?php echo $contrato->username; ?>
							</td>
							<td>
								<?php echo $contrato->name; ?>
							</td>
							<td>
								<?php $monto = number_format($contrato->amount, $contrato->coin_decimals, '.', ''); ?>
								<?php echo $monto." ".$contrato->coin_avr ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td class='text-right' colspan='5'>
							<strong><?php echo $this->lang->line('payment_contracts_total'); ?>:</strong>&nbsp;500
						</td>
					</tr>
				</tfoot>
			</table>
			
			<!-- Botones de acción -->
			<?php $filter_profile = array(1, 2, 3, 4); ?>
			<?php if(in_array($this->session->userdata('logged_in')['profile_id'], $filter_profile)){ ?>
			<div class="col-sm-4">
				<button type="button" class="btn btn-sm btn-primary"><?php echo $this->lang->line('payment_contracts_pay_button'); ?></button>
				<button type="button" class="btn btn-sm btn-primary"><?php echo $this->lang->line('payment_contracts_recalculate_button'); ?></button>
			</div>
			<br>
			<?php } ?>
			<!-- Cierre de los botones de acción -->
			
		</div>
		
	</div>
	<!-- Cierre del cuerpo de la sección de contratos pendientes de pago -->
	
	<!-- Cuerpo de la sección de pagos realizados (transacciones pendientes) -->
	<div class="ibox">
		<div class="ibox-title">
			<h5><?php echo $this->lang->line('payment_payment_title'); ?></h5>
			
			<div class="ibox-tools">
				<a class="collapse-link">
					<i class="fa fa-chevron-up"></i>
				</a>
				<a class="close-link">
					<i class="fa fa-times"></i>
				</a>
			</div>
		</div>
		<div class="ibox-content">
			
			<?php $filter_profile = array(1, 2, 3, 4); ?>
			<?php if(in_array($this->session->userdata('logged_in')['profile_id'], $filter_profile)){ ?> 
			<div class="col-sm-4 col-md-offset-8">
				<div class="input-group">
					<input type="text" placeholder="Search in table" class="input-sm form-control" id="filter_payments">
					<span class="input-group-btn">
						<button type="button" class="btn btn-sm btn-primary"> Go!</button>
					</span>
				</div>
			</div>
			<?php } ?>

			<table id="tab_accounts"  data-page-size="10" data-filter=#filter_payments class="footable table table-stripped toggle-arrow-tiny">
				<thead>
					<tr>
						<th>Id</th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('payment_payment_date'); ?></th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('payment_payment_account'); ?></th>
						<th data-hide="phone,tablet" ><?php echo $this->lang->line('payment_payment_observation'); ?></th>
						<th ><?php echo $this->lang->line('payment_payment_amount'); ?></th>
						<th ><?php echo $this->lang->line('payment_payment_status'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; ?>
					<?php foreach ($transacciones as $transaccion) { ?>
						<tr style="text-align: center">
							<td>
								<?php echo $i; ?>
							</td>
							<td>
								<?php echo $transaccion->date; ?>
							</td>
							<td>
								<?php echo $transaccion->number; ?>
							</td>
							<td>
								<?php echo $transaccion->observation; ?>
							</td>
							<td>
								<?php $monto = number_format($transaccion->capital_disponible_total, $transaccion->coin_decimals, '.', ''); ?>
								<?php echo $monto." ".$transaccion->coin_avr ?>
							</td>
							<td>
								<?php
								if($transaccion->status == "approved"){
									echo "<i class='fa fa-check text-navy'></i>";
								}else if($transaccion->status == "waiting"){
									echo "<i class='fa fa-check text-warning'></i>";
								}else if($transaccion->status == "denied"){
									echo "<i class='fa fa-times text-danger'></i>";
								}else{
									echo "";
								}
								?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td class='text-center' colspan='6'>
							<ul class='pagination'></ul>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	<!-- Cierre del cuerpo de la sección de pagos realizados (transacciones pendientes) -->
	
</div>

<!-- FooTable -->
<!--<script src="<?php echo assets_url('js/plugins/footable/footable.js');?>"></script>-->
<script src="<?php echo assets_url('js/plugins/footable/footable.all.min.js');?>"></script>

<script>
/*jQuery(function($){
	$('.table-transactions').footable({
		"columns": $.ajax({
			type: "get",
			dataType: "json",
			url: '<?php echo base_url() ?>dashboard/transactions_json_columns',
			async: true
		})
		.done(function(coin) {
			if(coin.error){
				console.log(coin.error);
			} else {
				console.log("Títulos cargados");
			}				
		}).fail(function() {
			console.log("error ajax");
		}),
		"rows": $.ajax({
			type: "get",
			dataType: "json",
			url: '<?php echo base_url() ?>dashboard/transactions_json_rows',
			async: true
		})
		.done(function(coin) {
			if(coin.error){
				console.log(coin.error);
			} else {
				console.log("Transacciones cargadas");
			}				
		}).fail(function() {
			console.log("error ajax");
		})
	});
});*/
</script>

<!-- ChartJS-->
<script src="<?php echo assets_url('js/plugins/chartJs/Chart.min.js');?>"></script>

<!-- Page-Level Scripts -->
<script src="<?php echo assets_url(); ?>script/payments.js"></script>
