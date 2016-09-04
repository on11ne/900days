<?php /* @var $this Controller */ ?>

<?php $this->beginContent('/layouts/main'); ?>

    <div class="col-lg-10">
        <div id="content">
            <?php echo $content; ?>
        </div><!-- content -->
    </div>

    <div class="col-lg-2 last pull-right">
        <div id="sidebar">
            <?php
            if($this->menu)
                $this->widget('booster.widgets.TbMenu', array(
                    'type'=>'list',
                    'items'=>array_merge(array(array('label'=>'Действия')),$this->menu),
                ));
            ?>
        </div><!-- sidebar -->
    </div>


<?php $this->endContent(); ?>