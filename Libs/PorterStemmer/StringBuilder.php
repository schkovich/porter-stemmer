<?php
/**
 *
 */
namespace PorterStemmer;

class StringBuilder
{
    private $capacity = 0;
    private $length = 0;
    private $replacement = '';
    private $string = null;
    private $temp_self = null;
    /**
     * @var \ArrayIterator
     */
    private $temp_string = null;

    /**
     * Class constructor
     *
     * @param string $string
     * @param integer $capacity
     */
    public function __construct($string = '', $capacity = 0)
    {
        $this->string = $string;
    }

    public function __toString()
    {
        return $this->string;
    }

    public function append($string)
    {
        $this->string .= $string;
    }

    public function AppendFormat()
    {
        //@TODO: yet to be implemented;
    }

    public function count()
    {
        return strlen($this->string);
    }


    public function EnsureCapacity()
    {
        //@TODO: check if there is any point to implement this method in PHP;
    }

    public function Equals(StringBuilder $instance)
    {
        if ((string)$instance === (string)$this) :
            return true;
        else :
            return false;
        endif;
    }

    public function GetHashCode()
    {
        //@TODO: Consider if there is meaningful PHP implementation;
    }

    //@TODO: Reconsider if implementation is meaningful
    public function getType()
    {
        return get_class();
    }

    public function Insert($index, $value, $start = 0, $length = null)
    {
        if ($index >= $this->count())
            throw new \Exception('Out of range!', 101);
        $this->string = (($index === 0) ? '' : substr($this->string, 0, $index)) .
            $value . substr($this->string, $index);
    }

    public function offsetGet($offset)
    {
        return substr($this->string, $offset, 1);
    }

    public function offsetSet($offset, $value)
    {
        if ($offset >= $this->count())
            throw new \Exception('Out of range!', 101);

        $this->string = substr($this->string, 0, $offset) .
            $value .
            substr($this->string, $offset + 1);
    }

    public function offsetUnset($offset)
    {
        $this->Remove($offset);
    }


    public function Remove($index, $length = 1)
    {
        if (($index + $length) > ($this->count())) :
            throw new \Exception('Out of range!', 101);
        endif;

        $this->string = (($index === 0) ? '' : substr($this->string, 0, $index)) .
            ((($index + $length) < $this->count()) ?
                substr($this->string, $index + $length) : '');
    }

    public function Replace($search, $replace, $start = 0, $length = null)
    {
        if ((0 === $start) && (null === $length) && (false !== strpos($this->string, $search))) :
            $this->string = substr($this->string, strpos($this->string, $search)) . $replace . substr($this->string, strpos($this->string, $search) + 1); elseif (false !== strpos($this->string, $search, $start)) :
            $this->string = substr($this->string, 0, $start) .
                str_replace($search, $replace, substr($this->string, $start, $length)) .
                substr($this->string, $start + $length);
        endif;

    }

    /**
     * Replace portion of the string
     *
     * @deprecated
     *
     * @param integer $index
     * @param string $value
     * @param integer $start
     * @param integer $length
     *
     * @access public
     * @return void
     */
    public function ReplaceChunk($index, $value, $start = 0, $length = null)
    {
        if (null !== $length) :
            $this->length = $length;
        endif;

        switch (true) :
            case (is_string($value)) :
                $this->temp_string = new \ArrayIterator(str_split($value));
                if (null === $length)
                    $this->length = strlen($value);
                break;
            case ($value instanceof \ArrayIterator) :
                $this->temp_string = $value;
                if (null === $length)
                    $this->length = $value->count();
                break;
            case (is_array($value)) :
                $this->temp_string = new \ArrayIterator($value);
                if (null === $length)
                    $this->length = count($value);
                break;
            default:
                throw new \Exception('Invalid data type', 101);
        endswitch;

        $this->temp_string->seek($start);
        for ($i = $start; $i < $this->length; $i++) :
            $this->offsetSet($index, $this->temp_string->current());
            $index++;
            $this->temp_string->next();
        endfor;
        $this->temp_string = null;
    }

    public function SubStr($start, $length)
    {
        return substr($this->string, $start, $length);
    }

}

?>
