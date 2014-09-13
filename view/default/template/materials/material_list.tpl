<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['link'] ?>"><?php echo $breadcrumb['caption'] ?></a></li>
        <li>&rightarrow;</li>
    <?php } ?>
</ul>


<h2><?php echo $category['caption'] ?></h2>
<div class="clearfix">
    <div class="text"><?php echo $category['text'] ?></div>
    <div class="row">
        <?php foreach ($categories as $category) { ?>
            <div class="category-block col-md-4 col-xs-6">
                <div class="news-cap-group">
                    <div class="news-cap">
                        <a href="<?php echo $category['link'] ?>"><?php echo $category['caption'] ?></a>
                    </div>
                </div>
                <a href="<?php echo $category['link'] ?>"><img src="<?php echo $category['preview'] ?>" alt="preview" /></a>
                <div class="description clearfix"><?php echo $category['description'] ?></div>
                <a class="btn btn-primary btn-block" href="<?php echo $category['link'] ?>"><?php echo $details_caption ?></a>
            </div>
        <?php } ?>
    </div>
</div>

<?php foreach ($materials as $material) { ?>
    <div class="news-item clearfix">
        <div class="news-cap-group">
            <div class="news-cap">
                <a href="<?php echo $material['link'] ?>"><?php echo $material['caption'] ?></a>
            </div>
            <div class="news-date">
                <?php echo $material['date_added'] ?>
            </div>
        </div>
        <a href="<?php echo $material['link'] ?>"><img src="<?php echo $material['preview'] ?>" alt="preview" /></a>
        <div class="description"><?php echo $material['description'] ?></div>
        <a class="btn btn-default pull-right" href="<?php echo $material['link'] ?>"><?php echo $details_caption ?></a>
    </div>
<?php } echo $pagination; ?>