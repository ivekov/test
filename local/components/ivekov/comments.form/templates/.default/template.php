<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<section id="add-review" class="section section-skills post-comments post-content">
	<div class="post-comments post-content">
		<h2 class="section-title">Оставить комментарий</h2>
		<div class="section-box">
			<div id="comment-reply" class="comment-reply">
				<form class="comment-reply-form">
					<input type="hidden" value="<?=$arParams["ELEMENT_ID"]?>" name="rs-comment-element" class="rs-comment-company" />
					<div class="first_wave">
						<div class="input-field">
							<input type="text" name="rs-comment-name" class="rs-comment-name" /> <span class="line"></span>
							<label>Название *</label>
						</div>
						<div class="input-field">
							<textarea rows="4" name="rs-comment-message" class="rs-comment-message"></textarea> <span class="line"></span>
							<label>Комментарий *</label>
						</div>
						<div class="text-right">
							<span class="btn-outer btn-primary-outer ripple">
								<input type="submit" value="Отправить" class="btn btn-lg btn-primary comment-send" style="color:white;">
							</span>
						</div>
					</div>
				</form>
			</div>
			<!-- .comment-reply -->
		</div>
		<!-- .section-box -->
	</div>
	<!-- .post-comments -->
</section>