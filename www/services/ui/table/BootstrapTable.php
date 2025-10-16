<?php

namespace app\services\ui\table;


class BootstrapTable
{
    private TableConfig $config;
    private ?TableHeader $header = null;
    private TableBody $body;
    private ?TableFooter $footer = null;
    private bool $hasControlColumn = false;

    public function __construct(TableConfig $config, array $data)
    {
        $this->config = $config;
        $this->body = new TableBody($data);
        $this->checkForControlColumn($data);
    }

    private function checkForControlColumn(array $data): void
    {
        if (!empty($data)) {
            $firstRow = reset($data);
            $this->hasControlColumn = isset($firstRow['controls']);
        }
    }

    public function setHeader(TableHeader $header): self
    {
        $this->header = $header;
        return $this;
    }

    public function setFooter(TableFooter $footer): self
    {
        $this->footer = $footer;
        return $this;
    }

    public function render(): string
    {
        if (count($this->body->getData()) > 0) {
            $html = '<table' . $this->config->getAttributes() . '  class="' . $this->config->getTableClasses() . '" style="font-size: 0.8em">';

            if ($this->header) {
                $html .= $this->renderHeader();
            }

            $html .= $this->renderBody();

            if ($this->footer) {
                $html .= $this->renderFooter();
            }

            $html .= '</table>';

            return $html;
        } else {
            return '
            <div class="alert alert-warning" role="alert">
                <b><i class="fas fa-exclamation"></i></b> Actualmente no hay datos disponibles para mostrar en la tabla.
            </div>
            ';
        }
    }

    private function renderHeader(): string
    {
        $html = '<thead class="table-light" style=""><tr>';
        foreach ($this->header->getColumns() as $column) {
            $width = $column->getWidth() !== '' ? ' style="width:' . $column->getWidth() . ';"' : '';
            $html .= '<th' . $width . '>' . htmlspecialchars($column->getName()) . '</th>';
        }
        $html .= '</tr></thead>';
        return $html;
    }

    private function renderBody(): string
    {
        $html = '<tbody class="">';
        foreach ($this->body->getData() as $row) {
            $html .= '<tr>';
            foreach ($row as $key => $value) {
                if ($key !== 'controls') {
                    // $html .= '<td>' . htmlspecialchars($value) . '</td>';
                    $html .= '<td style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' . $value . '</td>';
                }
            }
            if ($this->hasControlColumn) {
                $html .= '<td> style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"' . ($row['controls'] ?? '') . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        return $html;
    }

    private function renderFooter(): string
    {
        $html = '<tfoot><tr>';
        foreach ($this->footer->getColumns() as $column) {
            $html .= '<th>' . htmlspecialchars($column) . '</th>';
        }
        if ($this->hasControlColumn) {
            $html .= '<th>Actions</th>';
        }
        $html .= '</tr></tfoot>';
        return $html;
    }
}
