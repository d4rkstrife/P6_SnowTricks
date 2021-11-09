<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;




class Paginator
{
    private RequestStack $requestStack;
    private int $page;
    private int $itemsPerPage;
    private int $maxItems;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->page = $this->getPage();
        $this->itemsPerPage = 2;
        $this->maxItems = 15;
    }

    public function numberOfItems(): int
    {
        return $this->page * $this->itemsPerPage;
    }
    public function getPage(): int
    {
        $request = $this->requestStack->getCurrentRequest()->get('page');
        if ($request) {
            return $request;
        } elseif (!$request) {
            return 1;
        }
    }
    public function numberOfPages(): int
    {
        return round($this->maxItems / $this->itemsPerPage, 0, PHP_ROUND_HALF_UP);
    }
}
