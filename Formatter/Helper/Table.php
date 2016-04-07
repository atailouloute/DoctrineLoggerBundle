<?php

namespace ATailouloute\DoctrineLoggerBundle\Formatter\Helper;

use ATailouloute\DoctrineLoggerBundle\Exception\InvalidArgumentException;

/**
 * @author Ahmed TAILOULOUTE <ahmed.tailouloute@gmail.com>
 */
class Table
{
    const PADDING_CHAR = ' ';
    const HORIZONTAL_BORDER_CHAR = '-';
    const VERTICAL_BORDER_CHAR = '|';
    const CROSSING_CHAR = '+';

    /**
     * @var Row
     */
    private $header;

    /**
     * @var array
     */
    private $rows;

    /**
     * @var array
     */
    private $columnWidths;

    /**
     * @var int
     */
    private $numberOfColumns;

    /**
     * @var string
     */
    private $output;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->rows = array();
    }

    /**
     * @param array|Row $header
     *
     * @return Table
     */
    public function setHeader($header)
    {
        if (!is_array($header) && !$header instanceof Row) {
            throw new InvalidArgumentException('A row must be an array or an instance of Row');
        }

        $this->header = is_array($header) ? new Row(array_values($header)) : $header;
        $this->numberOfColumns = $this->header->getNumberOfColumns();

        return $this;
    }

    /**
     * @param array $row
     *
     * @return Table
     */
    public function addRow($row)
    {
        if (!is_array($row) && !$row instanceof Row) {
            throw new InvalidArgumentException('A row must be an array or an instance of Row');
        }

        if ((is_array($row) && $this->numberOfColumns != count($row)
            || ($row instanceof Row && $this->numberOfColumns != $row->getNumberOfColumns()))) {
            throw new InvalidArgumentException('A row must contain the same number of columns as the header');
        }

        $this->rows [] = is_array($row) ? new Row(array_values($row)) : $row;

        return $this;
    }

    /**
     * Render the table.
     */
    public function render()
    {
        $this->calculateColumnsWidths();
        $this->renderRowSeparator();
        $this->renderRow($this->header);
        $this->renderRowSeparator();

        foreach ($this->rows as $row) {
            $this->renderRow($row);
            $this->renderRowSeparator();
        }

        return $this->output;
    }

    /**
     * Calculate the maximum width for each column.
     */
    private function calculateColumnsWidths()
    {
        $this->columnWidths = $this->header->getRowColumnWidths();

        foreach ($this->rows as $row) {
            $this->columnWidths = $row->getRowColumnWidths($this->columnWidths);
        }

        $this->header->setColumnWidths($this->columnWidths);

        foreach ($this->rows as $row) {
            $row->setColumnWidths($this->columnWidths);
        }
    }

    /**
     * Render the row separator.
     */
    private function renderRowSeparator()
    {
        for ($key = 0; $key < $this->numberOfColumns; ++$key) {
            if ($key == 0) {
                $this->output .= self::CROSSING_CHAR;
            }
            $this->output .= self::HORIZONTAL_BORDER_CHAR;
            $this->output .= str_repeat(self::HORIZONTAL_BORDER_CHAR, $this->columnWidths[$key]);
            $this->output .= self::HORIZONTAL_BORDER_CHAR;
            $this->output .= self::CROSSING_CHAR;
        }

        $this->output .= PHP_EOL;
    }

    /**
     * Render a row/header.
     *
     * @param Row $row
     */
    private function renderRow(Row $row)
    {
        $this->output .= $row->render();
    }
}
