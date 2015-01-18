<?php

class PaginationModel {

    public $total;

    public $currentPage = 1;
    public $maxPage = 1;
    public $perPage;
    public $offsetStart = 0;

    public function __construct () {}

    public function build($perPage = 2, $total) {
        $this->perPage = $perPage;
        $this->total = $total;

        $this->maxPage = ceil($total / $perPage);
        $this->offsetStart = 0;
    }

    public function setCurrentPage ($currentPage) {

        if ($currentPage > $this->maxPage) {
            $currentPage = $this->maxPage;
        }

        $this->currentPage = $currentPage;
        $this->offsetStart = ($currentPage * $this->perPage) - $this->perPage;
    }


}