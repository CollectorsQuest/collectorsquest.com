<?php

echo ice_cdn_image_tag('nw-grey.png', 'backend');
echo '&nbsp;';
echo link_to($Video->getTitle(), url_to_frontend('video_by_id', array('id' => $Video->getId(), 'slug' => $Video->getSlug())), array('target' => '_blank'));
