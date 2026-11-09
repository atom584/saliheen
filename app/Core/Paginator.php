<?php
declare(strict_types=1);

namespace App\Core;

final class Paginator
{
    private int $total;
    private int $perPage;
    private int $current;

    public function __construct(
        int $total,
        int $perPage,
        int $current,
        private string $basePath,
        private string $fragment = ''
    ) {
        $this->perPage = max(1, $perPage);
        $this->total = max(0, $total);
        $this->current = $this->total === 0 ? 1 : min(max(1, $current), $this->lastPage());
    }

    public static function fromRequest(int $total, int $perPage, string $basePath, string $fragment = ''): self
    {
        $page = isset($_GET['page']) && is_scalar($_GET['page']) ? (int) $_GET['page'] : 1;
        return new self($total, $perPage, $page, $basePath, $fragment);
    }

    public function total(): int
    {
        return $this->total;
    }

    public function perPage(): int
    {
        return $this->perPage;
    }

    public function current(): int
    {
        return $this->current;
    }

    public function lastPage(): int
    {
        return max(1, (int) ceil($this->total / $this->perPage));
    }

    public function offset(): int
    {
        return ($this->current - 1) * $this->perPage;
    }

    public function from(): int
    {
        return $this->total === 0 ? 0 : $this->offset() + 1;
    }

    public function to(): int
    {
        return min($this->total, $this->offset() + $this->perPage);
    }

    public function hasPrevious(): bool
    {
        return $this->current > 1;
    }

    public function hasNext(): bool
    {
        return $this->current < $this->lastPage();
    }

    /**
     * Page numbers around the current page, with null marking an ellipsis.
     *
     * @return array<int, int|null>
     */
    public function window(int $radius = 2): array
    {
        $last = $this->lastPage();
        if ($last <= 7) {
            return range(1, $last);
        }

        $pages = [1];
        $start = max(2, $this->current - $radius);
        $end = min($last - 1, $this->current + $radius);

        if ($start > 2) {
            $pages[] = null;
        }
        for ($page = $start; $page <= $end; $page++) {
            $pages[] = $page;
        }
        if ($end < $last - 1) {
            $pages[] = null;
        }
        $pages[] = $last;

        return $pages;
    }

    public function url(int $page): string
    {
        $query = $_GET;
        unset($query['page']);
        if ($page > 1) {
            $query['page'] = $page;
        }
        $query = array_filter($query, static fn ($value): bool => $value !== '' && $value !== null);
        $queryString = http_build_query($query);

        return url($this->basePath)
            . ($queryString !== '' ? '?' . $queryString : '')
            . ($this->fragment !== '' ? '#' . $this->fragment : '');
    }
}
