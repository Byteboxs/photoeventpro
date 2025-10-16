<?php

namespace app\services\ui\paginator;

class Paginator
{
    private $page;
    private $link;
    private $currentPage;
    private $totalPages;
    private $perPage;
    private $totalData;
    private $visiblePages = 5; // Número de páginas visibles en la paginación

    // public function __construct($page, $link, $currentPage, $totalPages, $perPage, $totalData)
    public function __construct($page, $link, $currentPage, $totalPages, $perPage, $totalData)
    {
        $this->page = $page;
        $this->link = $link;
        $this->currentPage = $currentPage;
        $this->totalPages = $totalPages;
        $this->perPage = $perPage;
        $this->totalData = $totalData;
    }
    private function generateLink($page)
    {
        // Puedes ajustar la forma en que se generan los enlaces según tu configuración
        // Ejemplo usando query string:
        return $this->link . $page;
        // Ejemplo usando rutas amigables:
        //return str_replace('{page}', $page, $this->link);
    }

    public function render()
    {
        if ($this->totalPages <= 1) {
            return ''; // No mostrar paginación si solo hay una página
        }

        $startPage = max(1, $this->currentPage - floor($this->visiblePages / 2));
        $endPage = min($this->totalPages, $startPage + $this->visiblePages - 1);

        $output = '<div class="row align-middle">';
        $output .= '<div class="col-md-4 align-middle">';
        $output .= 'Mostrando ' . ($this->currentPage * $this->perPage - $this->perPage + 1) . ' a ' . min($this->currentPage * $this->perPage, $this->totalData) . ' de ' . $this->totalData . ' entradas';
        $output .= '</div>';
        $output .= '<div class="col-md-8 align-middle">';
        $output .= '<nav aria-label="Page navigation">';
        $output .= '<ul class="pagination justify-content-end pagination-rounded pagination-outline-warning">';

        // Botón "Anterior"
        $prevLink = ($this->currentPage > 1) ? $this->generateLink($this->currentPage - 1) : 'javascript:void(0);';
        $output .= '<li class="page-item ' . ($this->currentPage <= 1 ? 'disabled' : 'prev') . '">';
        $output .= '<a class="page-link" href="' . $prevLink . '"><i class="tf-icon bx bx-chevrons-left bx-sm"></i></a>';
        $output .= '</li>';

        // Números de página
        for ($i = $startPage; $i <= $endPage; $i++) {
            $activeClass = ($i == $this->currentPage) ? 'active' : '';
            $output .= '<li class="page-item ' . $activeClass . '"><a class="page-link" href="' . $this->generateLink($i) . '">' . $i . '</a></li>';
        }

        // Botón "Siguiente"
        $nextLink = ($this->currentPage < $this->totalPages) ? $this->generateLink($this->currentPage + 1) : 'javascript:void(0);';
        $output .= '<li class="page-item ' . ($this->currentPage >= $this->totalPages ? 'disabled' : 'next') . '">';
        $output .= '<a class="page-link" href="' . $nextLink . '"><i class="tf-icon bx bx-chevrons-right bx-sm"></i></a>';
        $output .= '</li>';

        $output .= '</ul>';
        $output .= '</nav>';
        $output .= '</div>';
        $output .= '</div>';

        return $output;
    }
}
