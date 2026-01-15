<?php
/**
 * Pagination Partial
 *
 * Renders pagination controls preserving query parameters.
 *
 * @package MiniMovies\Views\Partials
 * @var array<string, mixed> $pagination Pagination data with keys: current_page, total_pages, per_page, total
 * @var array<string, mixed> $filters Current filter values to preserve in URLs (optional)
 */

$currentPage = $pagination['current_page'] ?? 1;
$totalPages = $pagination['total_pages'] ?? 1;
$filters = $filters ?? [];

if ($totalPages <= 1) {
    return;
}

$buildUrl = function (int $page) use ($filters): string {
    $params = array_filter($filters, fn($v) => $v !== '' && $v !== null);
    $params['page'] = $page;
    return '/movies?' . http_build_query($params);
};

$maxVisible = 5;
$start = max(1, $currentPage - floor($maxVisible / 2));
$end = min($totalPages, $start + $maxVisible - 1);

if ($end - $start + 1 < $maxVisible) {
    $start = max(1, $end - $maxVisible + 1);
}
?>

<nav aria-label="Movies pagination">
    <ul class="pagination justify-content-center mb-0">
        <!-- Previous -->
        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= $currentPage > 1 ? $buildUrl($currentPage - 1) : '#' ?>">
                <i class="bi bi-chevron-left"></i> Previous
            </a>
        </li>

        <!-- First page + ellipsis -->
        <?php if ($start > 1) : ?>
        <li class="page-item">
            <a class="page-link" href="<?= $buildUrl(1) ?>">1</a>
        </li>
            <?php if ($start > 2) : ?>
        <li class="page-item disabled">
            <span class="page-link">...</span>
        </li>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Page numbers -->
        <?php for ($i = $start; $i <= $end; $i++) : ?>
        <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
            <a class="page-link" href="<?= $buildUrl($i) ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>

        <!-- Last page + ellipsis -->
        <?php if ($end < $totalPages) : ?>
            <?php if ($end < $totalPages - 1) : ?>
        <li class="page-item disabled">
            <span class="page-link">...</span>
        </li>
            <?php endif; ?>
        <li class="page-item">
            <a class="page-link" href="<?= $buildUrl($totalPages) ?>"><?= $totalPages ?></a>
        </li>
        <?php endif; ?>

        <!-- Next -->
        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= $currentPage < $totalPages ? $buildUrl($currentPage + 1) : '#' ?>">
                Next <i class="bi bi-chevron-right"></i>
            </a>
        </li>
    </ul>
</nav>
