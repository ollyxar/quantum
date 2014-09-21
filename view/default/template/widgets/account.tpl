<div class="clearfix"></div>
<div class="pull-right">
<?php for ($i = 0; $i < count($links); $i++) { ?>
    <a href="<?php echo $links[$i]['href'] ?>"><?php echo $links[$i]['caption'] ?></a>
    <?php if ($i < count($links) -1) echo ' | ' ?>
<?php } ?>
</div>