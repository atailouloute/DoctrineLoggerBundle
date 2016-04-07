<?php

namespace ATailouloute\DoctrineLoggerBundle\Formatter\Helper;

/**
 * @author Ahmed TAILOULOUTE <ahmed.tailouloute@gmail.com>
 */
class Row
{
    /**
     * @var array
     */
    protected $cells;

    /**
     * @var int
     */
    protected $numberOfColumns;

    /**
     * @var int
     */
    protected $numberOfLines;

    /**
     * @var array
     */
    protected $columnWidths;

    /**
     * @var string
     */
    protected $output;

    /**
     * @param array $row
     */
    public function __construct($row)
    {
        $this->cells = array();
        $this->numberOfColumns = count($row);
        $this->numberOfLines = 1;

        for ($index = 0; $index < $this->numberOfColumns; ++$index ) {
            $this->cells [] = explode(PHP_EOL, $row[$index]);
            $this->numberOfLines = max($this->numberOfLines, count(end($this->cells)));
        }
    }

    public function render()
    {
        for ($lineNumber = 0; $lineNumber < $this->numberOfLines; ++$lineNumber ) {
            for ($colNumber = 0; $colNumber < $this->numberOfColumns; ++$colNumber ) {
                $value = '';
                if ($lineNumber < count($this->cells[$colNumber])) {
                    $value = $this->cells[$colNumber][$lineNumber];
                }
                if (0 == $colNumber) {
                    $this->output .= Table::VERTICAL_BORDER_CHAR;
                }
                $this->output .= ' '.str_pad($value, $this->columnWidths[$colNumber]);
                $this->output .= ' '.Table::VERTICAL_BORDER_CHAR;
            }
            $this->output .= PHP_EOL;
        }

        return $this->output;
    }

    public function getMaxCellLine($colNumber)
    {
        $result = 0;
        foreach ($this->cells[$colNumber] as $value) {
            $result = max($result, strlen($value));
        }

        return $result;
    }

    public function getRowColumnWidths(array $previous = array())
    {
        $columnWidths = empty($previous) ? array_fill(0, $this->numberOfColumns, 0) : $previous;

        for ($colNumber = 0; $colNumber < $this->numberOfColumns; ++$colNumber ) {
            $columnWidths[$colNumber] = max($columnWidths[$colNumber], $this->getMaxCellLine($colNumber));
        }

        return $columnWidths;
    }

    /**
     * @return int
     */
    public function getNumberOfColumns()
    {
        return $this->numberOfColumns;
    }

    /**
     * @param array $columnWidths
     */
    public function setColumnWidths($columnWidths)
    {
        $this->columnWidths = $columnWidths;
    }
}
