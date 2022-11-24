<div class="post-item">
	<div class="professor-card__list-item">
		<a class="professor-card" href="<?php the_permalink(); ?>">
			<img src="<?php the_post_thumbnail_url('landscape'); ?>" alt="Professor thumbnail" class="professor-card__image">
			<span class="professor-card__name"><?php the_title(); ?></span>
		</a>
	</div>
</div>