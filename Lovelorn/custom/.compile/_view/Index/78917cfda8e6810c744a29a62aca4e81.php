			<div class="con_r">
				<div class="mb10">
					<h4 class="conr_title">公司简介</h4>
					<div class="conr_gs" style="max-height:238px;overflow:hidden;_height:238px;"><?=$introduction;?></div>
				</div>
				<div class="mb10">
					<h4 class="conr_title">学员风采</h4>
					<div class="index_cp">
						<ul class="clearfix">
							<?php foreach($photos as $key => $value){?>
								<li><a href="<?=$value['thumbnail'];?>" target="_blank" title="<?=$value['name'];?>"><img src="<?=$value['thumbnail'];?>" alt="<?=$value['name'];?>" /><span><?=SUtilComponent::substr($value['name'], 10);?></span></a></li>
							<?php }?>
						</ul>
					</div>
				</div>
			</div>