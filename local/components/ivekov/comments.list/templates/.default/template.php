<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

?>

<section id="last-reviews" class="section section-skills post-comments post-content">
	<div class="animate-up animated">
		<div class="section-box" id="comments_list_wrap">
			<ol class="comment-list" id="comments_list">
				<? if ($arResult['ITEMS']):?>
				<? foreach ($arResult['ITEMS'] as $ID => $item):?>
				<li class="comment" id="<?=$this->GetEditAreaId($arCommentsF['ID']);?>">
					<article class="comment-body">
						<div class="comment-avatar"> <img src="/upload/no_photo.png" alt="avatar"> </div>
						<div class="comment-content">
							<div class="comment-meta"> <span class="name"><?=$item['NAME']?></span>
								<time class="date" datetime="2015-03-20T13:00:14+00:00">
									<?=$item["TIMESTAMP_X"]?>
								</time>
							</div>
							<div class="comment-message">
								<p>
									<?=$item["DETAIL_TEXT"]?>
								</p>
							</div>
						</div>
					</article>
				</li>
				<?
				endforeach;
				?>
				<? else:?>
				<h3>Нет ни одного комментария</h3>
				<? endif;?>
				<!-- .comment -->
			</ol>
			<!-- .comment-list -->
		</div>
	</div>
</section>