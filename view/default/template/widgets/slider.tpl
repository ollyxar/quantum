<ul class="bxslider">
    <?php foreach ($slides as $slide) { ?>
        <li><a href="<?php echo $slide['link'] ?>"><img src="<?php echo $slide['src'] ?>" /></a></li>
    <?php } ?>
</ul>
<script type="text/javascript">
    jQuery('.bxslider').bxSlider({
        adaptiveHeight: true,
        auto: true,
        mode: 'fade'
    });
</script>                    