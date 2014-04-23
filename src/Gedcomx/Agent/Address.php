<?php
/**
 *
 * 
 *
 * Generated by <a href="http://enunciate.codehaus.org">Enunciate</a>.
 *
 */

namespace Gedcomx\Agent;

/**
 * An address.
 */
class Address extends \Gedcomx\Common\ExtensibleData
{

    /**
     * The city.
     *
     * @var string
     */
    private $city;

    /**
     * The country.
     *
     * @var string
     */
    private $country;

    /**
     * The postal code.
     *
     * @var string
     */
    private $postalCode;

    /**
     * The state or province.
     *
     * @var string
     */
    private $stateOrProvince;

    /**
     * The street.
     *
     * @var string
     */
    private $street;

    /**
     * Additional street information.
     *
     * @var string
     */
    private $street2;

    /**
     * Additional street information.
     *
     * @var string
     */
    private $street3;

    /**
     * The value of the property.
     *
     * @var string
     */
    private $value;

    /**
     * Constructs a Address from a (parsed) JSON hash
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
     * The city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * The city.
     *
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }
    /**
     * The country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * The country.
     *
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }
    /**
     * The postal code.
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * The postal code.
     *
     * @param string $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }
    /**
     * The state or province.
     *
     * @return string
     */
    public function getStateOrProvince()
    {
        return $this->stateOrProvince;
    }

    /**
     * The state or province.
     *
     * @param string $stateOrProvince
     */
    public function setStateOrProvince($stateOrProvince)
    {
        $this->stateOrProvince = $stateOrProvince;
    }
    /**
     * The street.
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * The street.
     *
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }
    /**
     * Additional street information.
     *
     * @return string
     */
    public function getStreet2()
    {
        return $this->street2;
    }

    /**
     * Additional street information.
     *
     * @param string $street2
     */
    public function setStreet2($street2)
    {
        $this->street2 = $street2;
    }
    /**
     * Additional street information.
     *
     * @return string
     */
    public function getStreet3()
    {
        return $this->street3;
    }

    /**
     * Additional street information.
     *
     * @param string $street3
     */
    public function setStreet3($street3)
    {
        $this->street3 = $street3;
    }
    /**
     * The value of the property.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * The value of the property.
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    /**
     * Returns the associative array for this Address
     *
     * @return array
     */
    public function toArray()
    {
        $a = parent::toArray();
        if ($this->city) {
            $a["city"] = $this->city;
        }
        if ($this->country) {
            $a["country"] = $this->country;
        }
        if ($this->postalCode) {
            $a["postalCode"] = $this->postalCode;
        }
        if ($this->stateOrProvince) {
            $a["stateOrProvince"] = $this->stateOrProvince;
        }
        if ($this->street) {
            $a["street"] = $this->street;
        }
        if ($this->street2) {
            $a["street2"] = $this->street2;
        }
        if ($this->street3) {
            $a["street3"] = $this->street3;
        }
        if ($this->value) {
            $a["value"] = $this->value;
        }
        return $a;
    }


    /**
     * Initializes this Address from an associative array
     *
     * @param array $o
     */
    public function initFromArray($o)
    {
        parent::initFromArray($o);
        if (isset($o['city'])) {
                $this->city = $o["city"];
        }
        if (isset($o['country'])) {
                $this->country = $o["country"];
        }
        if (isset($o['postalCode'])) {
                $this->postalCode = $o["postalCode"];
        }
        if (isset($o['stateOrProvince'])) {
                $this->stateOrProvince = $o["stateOrProvince"];
        }
        if (isset($o['street'])) {
                $this->street = $o["street"];
        }
        if (isset($o['street2'])) {
                $this->street2 = $o["street2"];
        }
        if (isset($o['street3'])) {
                $this->street3 = $o["street3"];
        }
        if (isset($o['value'])) {
                $this->value = $o["value"];
        }
    }
}