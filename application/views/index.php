<?php echo $header; ?>
<div class="row">
	<!-- news -->
	<div class="col-sm-6 " >
		<div class="col-sm-12 well" style="height: 420px;overflow-y: scroll;">
			<p style="margin-top: -30px;padding: 0px;">	<h3> <i class="fa fa-newspaper-o"> ข่าวสาร</i></h3></p>
			<?php foreach ($getNews as $keyNews => $rowNews): ?>
				<a href="<?php echo base_url() . 'index.php/News/readNews/' . $rowNews['id_news']; ?>">
					<div class="alert alert-info alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

						<?php echo $rowNews['news_title']; ?>
						<div class="pull-right"><?php echo $rowNews['dateNews']; ?></div>
					</div>
				</a>
			<?php endforeach?>
		</div>
	</div>
	<!-- /.news -->

	<!-- ทุน -->
	<div class="col-sm-6 ">
		<div class="col-sm-12 well" style="height: 420px;overflow-y: scroll;">
			<p style="margin-top: -30px;padding: 0px;">	<h3><i class="fa fa-money"> แหล่งทุน</i></h3></p>
			<?php foreach ($getFund as $rowFund): ?>
				<a href="<?php echo base_url() . 'index.php/Fund/readFund/' . $rowFund['id_fund']; ?>">
					<div class=" alert alert-success alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<?php echo $rowFund['fund_title']; ?>
						<div class="pull-right"><?php echo date_format(date_create($rowFund['dt_update']), 'd/m/Y'); ?></div>
						<!-- <i class="fa fa-info-circle"></i>  <strong>Like SB Admin?</strong> Try out <a href="http://startbootstrap.com/template-overviews/sb-admin-2" class="alert-link">SB Admin 2</a> for additional features! -->
					</div>
				</a>
			<?php endforeach?>
		</div>
	</div>
	<!-- /.ทุน -->
	<!-- /. activity -->

	<div class="col-sm-12">
		<div class="col-sm-12" style="background-color: #fff;margin-bottom: 50px;">
			<p><h3><i class="fa fa-link" aria-hidden="true"></i> กิจกรรม</h3><hr></p>
			<div class="row">

				<?php foreach ($getAll_activity as $key => $activity): ?>
					<?php $pic_name = explode(',', $activity['ac_pict']);?>
					<div class="col-sm-3">
						<a href= "<?php echo base_url() . 'index.php/Activity/showActivity/' . $activity['ac_id']; ?>">
							<div class="img-responsive" >
								<!-- height: 200px; width: 100%; display: block; style="background-size: 120px 80px;"-->
								<img  class="img-rounded  " src="<?php echo base_url() . 'assets/files_upload/' . $pic_name[0]; ?>" alt="" data-holder-rendered="true" width="200" height="200px" >
								<!-- <h5 class="pull-left"><?php echo count($pic_name) . " รูป"; ?></h5><h5 class="pull-right"><?php echo date('d/m/' . (date('Y') + 543), strtotime($activity['dt_create'])); ?></h5> -->
								<div class="caption">
									<br>
									<h4><?php echo $activity['ac_title']; ?></h4>
									<!-- <p><?php// echo $activity['ac_detail']; ?></p> -->
								</div>
							</div>
						</a>
					</div>
				<?php endforeach;?>

			</div>
		</div>
	</div>
	<!-- ./ end activity -->
</div>
<?php echo $footer; ?>