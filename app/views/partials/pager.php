<?php
/** @var App\Core\Paginator $paginator */
$label = $label ?? 'Pagination';
?>
<?php if ($paginator->lastPage() > 1): ?>
  <nav class="pager" aria-label="<?= e($label) ?>">
    <?php if ($paginator->hasPrevious()): ?>
      <a class="pager-link" rel="prev" href="<?= e($paginator->url($paginator->current() - 1)) ?>">&larr; Previous</a>
    <?php else: ?>
      <span class="pager-link is-disabled" aria-disabled="true">&larr; Previous</span>
    <?php endif; ?>

    <ol class="pager-pages">
      <?php foreach ($paginator->window() as $page): ?>
        <?php if ($page === null): ?>
          <li class="pager-ellipsis" aria-hidden="true">&hellip;</li>
        <?php elseif ($page === $paginator->current()): ?>
          <li><span class="pager-page is-current" aria-current="page"><?= $page ?></span></li>
        <?php else: ?>
          <li><a class="pager-page" href="<?= e($paginator->url($page)) ?>"><?= $page ?></a></li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ol>

    <?php if ($paginator->hasNext()): ?>
      <a class="pager-link" rel="next" href="<?= e($paginator->url($paginator->current() + 1)) ?>">Next &rarr;</a>
    <?php else: ?>
      <span class="pager-link is-disabled" aria-disabled="true">Next &rarr;</span>
    <?php endif; ?>
  </nav>
  <p class="pager-status">Showing <?= $paginator->from() ?>&ndash;<?= $paginator->to() ?> of <?= $paginator->total() ?></p>
<?php endif; ?>
