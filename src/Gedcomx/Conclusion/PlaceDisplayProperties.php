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
 * A set of display properties for places for the convenience of quick display, such as for
     * a Web-based application. All display properties are provided in the default locale for the current
     * application context and are NOT considered canonical for the purposes of data exchange.
 */
class PlaceDisplayProperties extends \Gedcomx\Common\ExtensibleData
{

    /**
     * The displayable full name of the place.
     *
     * @var string
     */
    private $fullName;

    /**
     * The displayable name of the place.
     *
     * @var string
     */
    private $name;

    /**
     * The displayable type of the place.
     *
     * @var string
     */
    private $type;

    /**
     * Constructs a PlaceDisplayProperties from a (parsed) JSON hash
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
     * The displayable full name of the place.
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * The displayable full name of the place.
     *
     * @param string $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }
    /**
     * The displayable name of the place.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * The displayable name of the place.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * The displayable type of the place.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * The displayable type of the place.
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    /**
     * Returns the associative array for this PlaceDisplayProperties
     *
     * @return array
     */
    public function toArray()
    {
        $a = parent::toArray();
        if ($this->fullName) {
            $a["fullName"] = $this->fullName;
        }
        if ($this->name) {
            $a["name"] = $this->name;
        }
        if ($this->type) {
            $a["type"] = $this->type;
        }
        return $a;
    }


    /**
     * Initializes this PlaceDisplayProperties from an associative array
     *
     * @param array $o
     */
    public function initFromArray($o)
    {
        parent::initFromArray($o);
        if (isset($o['fullName'])) {
                $this->fullName = $o["fullName"];
        }
        if (isset($o['name'])) {
                $this->name = $o["name"];
        }
        if (isset($o['type'])) {
                $this->type = $o["type"];
        }
    }
}