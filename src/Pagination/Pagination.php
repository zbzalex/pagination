<?php

namespace Pagination;

/**
 * Pagination class
 */
class Pagination
{
    private $page;
    private $itemCount;
    private $itemsPerPage;

    /** @var callable $generator */
    private $generator;

    public function __construct(
        $page,
        $itemCount,
        $itemsPerPage,
        $generator
    ) {
        $this->page = $page;
        $this->itemCount = $itemCount;
        $this->itemsPerPage = $itemsPerPage;
        $this->generator = $generator;
    }

    /**
     * @return int current page number
     */
    public function getPage()
    {
        return $this->page !== null && preg_match("/last/i", $this->page)
            ? $this->getTotalPages()
            : min($this->getTotalPages(), max(1, intval($this->page)));
    }

    /**
     * @return int total pages count
     */
    public function getTotalPages()
    {
        return max(1, ceil($this->itemCount / $this->itemsPerPage));
    }

    /**
     * @return int offset for slice
     */
    public function getOffset()
    {
        return ($this->getPage() - 1) * $this->itemsPerPage;
    }

    /**
     * @return int item count by page
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * @return array
     */
    public function getPagesArray()
    {
        $pages = [];

        for (
            $i = $this->getPage() - 3;
            $i <= min($this->getTotalPages(), $this->getPage() + 3);
            $i++
        ) {
            if ($i < 1) continue;

            // invoke generator for get page url
            $url = call_user_func($this->generator, $i);

            $pages[] = [
                'num'       => $i,
                'url'       => $url,
                'current'   => $this->getPage() == $i,
            ];
        }

        return $pages;
    }
}
