<?php
/**
 *
 * 
 *
 * Generated by <a href="http://enunciate.codehaus.org">Enunciate</a>.
 *
 */

namespace Gedcomx\Conclusion;

/**
 * A concluded genealogical date.
 */
class DateInfo extends \Gedcomx\Common\ExtensibleData
{

    /**
     * The original text as supplied by the user.
     *
     * @var string
     */
    private $original;

    /**
     * The formal value.
     *
     * @var string
     */
    private $formal;

    /**
     * The list of normalized values for the date, provided for display purposes by the application. Normalized values
     * are not specified by GEDCOM X core, but as extension elements by GEDCOM X RS.
     *
     * @var \Gedcomx\Common\TextValue[]
     */
    private $normalizedExtensions;

    /**
     * The references to the record fields being used as evidence.
     *
     * @var \Gedcomx\Records\Field[]
     */
    private $fields;

    /**
     * Constructs a DateInfo from a (parsed) JSON hash
     *
     * @param array $o
     */
    public function __construct($o = null)
    {
        if ($o) {
            $this->initFromArray($o);
        }
    }

    /**
     * The original text as supplied by the user.
     *
     * @return string
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * The original text as supplied by the user.
     *
     * @param string $original
     */
    public function setOriginal($original)
    {
        $this->original = $original;
    }
    /**
     * The formal value.
     *
     * @return string
     */
    public function getFormal()
    {
        return $this->formal;
    }

    /**
     * The formal value.
     *
     * @param string $formal
     */
    public function setFormal($formal)
    {
        $this->formal = $formal;
    }
    /**
     * The list of normalized values for the date, provided for display purposes by the application. Normalized values
       * are not specified by GEDCOM X core, but as extension elements by GEDCOM X RS.
     *
     * @return \Gedcomx\Common\TextValue[]
     */
    public function getNormalizedExtensions()
    {
        return $this->normalizedExtensions;
    }

    /**
     * The list of normalized values for the date, provided for display purposes by the application. Normalized values
       * are not specified by GEDCOM X core, but as extension elements by GEDCOM X RS.
     *
     * @param \Gedcomx\Common\TextValue[] $normalizedExtensions
     */
    public function setNormalizedExtensions($normalizedExtensions)
    {
        $this->normalizedExtensions = $normalizedExtensions;
    }
    /**
     * The references to the record fields being used as evidence.
     *
     * @return \Gedcomx\Records\Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * The references to the record fields being used as evidence.
     *
     * @param \Gedcomx\Records\Field[] $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }
    /**
     * Returns the associative array for this DateInfo
     *
     * @return array
     */
    public function toArray()
    {
        $a = parent::toArray();
        if ($this->original) {
            $a["original"] = $this->original;
        }
        if ($this->formal) {
            $a["formal"] = $this->formal;
        }
        if ($this->normalizedExtensions) {
            $ab = array();
            foreach ($this->normalizedExtensions as $i => $x) {
                $ab[$i] = $x->toArray();
            }
            $a['normalized'] = $ab;
        }
        if ($this->fields) {
            $ab = array();
            foreach ($this->fields as $i => $x) {
                $ab[$i] = $x->toArray();
            }
            $a['fields'] = $ab;
        }
        return $a;
    }


    /**
     * Initializes this DateInfo from an associative array
     *
     * @param array $o
     */
    public function initFromArray($o)
    {
        parent::initFromArray($o);
        if (isset($o['original'])) {
                $this->original = $o["original"];
        }
        if (isset($o['formal'])) {
                $this->formal = $o["formal"];
        }
        $this->normalizedExtensions = array();
        if (isset($o['normalized'])) {
            foreach ($o['normalized'] as $i => $x) {
                    $this->normalizedExtensions[$i] = new \Gedcomx\Common\TextValue($x);
            }
        }
        $this->fields = array();
        if (isset($o['fields'])) {
            foreach ($o['fields'] as $i => $x) {
                    $this->fields[$i] = new \Gedcomx\Records\Field($x);
            }
        }
    }
}