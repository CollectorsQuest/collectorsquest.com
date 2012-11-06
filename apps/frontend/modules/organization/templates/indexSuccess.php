<?php
/* @var $organization Organization */
/* @var $sf_user cqFrontendUser */
?>

<div class="row-fluid header-bar">
    <div class="span9">
        <h1 class="Chivo webfont" style="margin-left: 145px;" itemprop="name">
          <?= $organization->getName(); ?>
        </h1>
    </div>
</div>

<div class="row-fluid">
    <div class="span9">
        <div class="span9">
          <?php if (!$organization->isMember($sf_user->getCollector())): ?>
          <?= form_tag('@organization_join?id='.$organization->getId(), array('class' => 'form-horizontal')) ?>
            <button type="submit" class="btn">Join</button>
            </form>
          <?php endif; ?>
        </div>
    </div>
</div>
