<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;




class Paginator
{
    protected $requestStack;
    private $page;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->page = $this->getPage();
    }

    public function numberOfItems(): int
    {
        $itemsToReturn = 2;
        return $this->page * $itemsToReturn;
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
}
