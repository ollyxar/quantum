<div itemscope itemtype="http://data-vocabulary.org/Review-aggregate" style="display: none;">
    <span itemprop="itemreviewed">My site</span>
    <span itemprop="rating" itemscope itemtype="http://data-vocabulary.org/Rating">
      <span itemprop="average"><?php echo $rating ?></span>
      of <span itemprop="best">5</span>
    </span>
    based on <span itemprop="votes"><?php echo $count ?></span> reviews.
    <span itemprop="count"><?php echo $count ?></span> count.
</div>
<a class="h4" href="<?php echo $engine->url->link('route=reviews') ?>">Reviews</a>
<div class="rws">
    <?php foreach ($reviews as $review) { ?>
        <span><?php echo $review['name'] ?></span>
        <p><?php echo $review['post'] ?></p>
    <?php } ?>
</div>