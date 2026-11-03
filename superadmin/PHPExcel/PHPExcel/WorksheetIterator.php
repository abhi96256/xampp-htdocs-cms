<?php

/**
 * PHPExcel_WorksheetIterator
 *
 * Used to iterate worksheets in PHPExcel
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    1.8.0, 2014-03-02
 */
class PHPExcel_WorksheetIterator implements Iterator
{
    /**
     * Spreadsheet to iterate
     *
     * @var PHPExcel
     */
    private $_subject;

    /**
     * Current iterator position
     *
     * @var int
     */
    private $_position = 0;

    /**
     * Create a new worksheet iterator
     *
     * @param PHPExcel $subject
     */
    public function __construct(PHPExcel $subject = null)
    {
        // Set subject
        $this->_subject = $subject;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        unset($this->_subject);
    }

    /**
     * Return the current PHPExcel_Worksheet
     *
     * @return PHPExcel_Worksheet
     */
    public function current(): PHPExcel_Worksheet
    {
        return $this->_subject->getSheet($this->_position);
    }

    /**
     * Rewind iterator
     */
    public function rewind(): void
    {
        $this->_position = 0;
    }

    /**
     * Return the current key
     *
     * @return int
     */
    public function key(): int
    {
        return $this->_position;
    }

    /**
     * Move forward to the next worksheet
     */
    public function next(): void
    {
        ++$this->_position;
    }

    /**
     * Check if more PHPExcel_Worksheet instances are available
     *
     * @return bool
     */
    public function valid(): bool
    {
        return $this->_position < $this->_subject->getSheetCount();
    }
}
