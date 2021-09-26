<?php
use Cake\Utility\Text;
echo $this->element('NormalUser/header');
?>
<div class="header_bottom">
	<div class="header_bottom_left">
		<div class="section group">
		<div class="row">
			<?php foreach ($dataProds['cart'] as $key => $product) {?>
					<div class="listview_1_of_2 images_1_of_2">
						<div class="listimg listimg_2_of_1">
							<a href="/preview"> <img src="<?php echo $product['image']?>" alt="" /></a>
						</div>
						<div class="text list_2_of_1">
							<h2><?php echo $product['name']?></h2>
							<div>
								Số lượng: <?php echo isset($this->request->getSession()->read('cartData')['totalquantity']) ? $this->request->getSession()->read('cartData')['totalquantity'] : "0";?>
							</div>
							<a href="javascript:;" onclick="dellCart(<?php echo $key?>)" >Xóa</a>
							<!-- <a href="javascript:;" onclick="upCart(<?php echo $product?>)" >Tăng</a>
							<a href="javascript:;" onclick="downCart(<?php echo $product?>)" >Giảm</a> -->
						</div>
					</div>
			<?php } ?>
		</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="header_bottom_right_images">
		<!-- FlexSlider -->
		<section class="slider">
			<div class="flexslider">
				<ul class="slides">
					<?php foreach ($dataSlideImages as $slideImage) {
						# code...
					?>
					<li><img style="width: 100%;" src="<?php echo $slideImage['file']?>" alt="" /></li>
					<?php }?>
				</ul>
			</div>
		</section>
		<!-- FlexSlider -->
	</div>
	<div class="clear"></div>
</div>
</div>


</div>
<script>
	function addCart(product_id){
		// console.log(product_id)
		$.ajax({
			url: '/addCart',
			type: 'post',
			data: { productId : (product_id) },

			success: function (data) {
				console.log(JSON.parse(data));
				var data = JSON.parse(data);
				$('#id_quantity').html(data.totalquantity);
			},
			error :function (data, textStatus, jqXHR) {

				console.log("error");
			 }
		});
	}

	function dellCart(product_id){
		// console.log(product_id)
		$.ajax({
			url: '/dellCart',
			type: 'post',
			data: { productId : (product_id) },

			success: function (data) {
				console.log(JSON.parse(data));
				// var data = JSON.parse(data);
				// $('#id_quantity').html(data.totalquantity);
			},
			error :function (data, textStatus, jqXHR) {

				console.log("error");
			 }
		});
	}

</script>
<?php
echo $this->element('NormalUser/footer');
?>