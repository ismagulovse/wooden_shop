<?php if (!empty($breadcrumbs)): ?>
<nav class="breadcrumbs">
    <?php foreach ($breadcrumbs as $index => $crumb): ?>
        <?php if (!empty($crumb['url'])): ?>
            <a href="<?= e($crumb['url']) ?>"><?= e($crumb['title']) ?></a>
        <?php else: ?>
            <span><?= e($crumb['title']) ?></span>
        <?php endif; ?>
        <?php if ($index < count($breadcrumbs) - 1): ?>
            <span>/</span>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>
<?php endif; ?>


