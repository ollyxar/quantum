<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['link'] ?>"><?php echo $breadcrumb['caption'] ?></a></li>
        <li>&rightarrow;</li>
    <?php } ?>
</ul>

<h2><?php echo $material['caption'] ?></h2>
<p><?php echo $material['date_added'] ?></p>
<div class="news_content">
    <?php echo $material['text'] ?>
</div>