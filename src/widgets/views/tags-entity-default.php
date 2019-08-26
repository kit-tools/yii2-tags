<?php
if (!empty($tags)) { ?>
    <div class="widget-tag-entity"><?php
    foreach ($tags as $tag) {
        echo $tag;
    } ?>
    </div><?php
}
