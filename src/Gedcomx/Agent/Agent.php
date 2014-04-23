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
 * An agent, e.g. person, organization, or group. In genealogical research, an agent often
     * takes the role of a contributor.
 */
class Agent extends \Gedcomx\Links\HypermediaEnabledData
{

    /**
     * The accounts that belong to this person or organization.
     *
     * @var \Gedcomx\Agent\OnlineAccount[]
     */
    private $accounts;

    /**
     * The addresses that belong to this person or organization.
     *
     * @var \Gedcomx\Agent\Address[]
     */
    private $addresses;

    /**
     * The emails that belong to this person or organization.
     *
     * @var \Gedcomx\Common\ResourceReference[]
     */
    private $emails;

    /**
     * The homepage.
     *
     * @var \Gedcomx\Common\ResourceReference
     */
    private $homepage;

    /**
     * The list of identifiers for the agent.
     *
     * @var \Gedcomx\Conclusion\Identifier[]
     */
    private $identifiers;

    /**
     * The list of names for the agent.
     *
     * @var \Gedcomx\Common\TextValue[]
     */
    private $names;

    /**
     * The &lt;a href=&quot;http://openid.net/&quot;&gt;openid&lt;/a&gt; of the person or organization.
     *
     * @var \Gedcomx\Common\ResourceReference
     */
    private $openid;

    /**
     * The phones that belong to this person or organization.
     *
     * @var \Gedcomx\Common\ResourceReference[]
     */
    private $phones;

    /**
     * Constructs a Agent from a (parsed) JSON hash
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
     * The accounts that belong to this person or organization.
     *
     * @return \Gedcomx\Agent\OnlineAccount[]
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * The accounts that belong to this person or organization.
     *
     * @param \Gedcomx\Agent\OnlineAccount[] $accounts
     */
    public function setAccounts($accounts)
    {
        $this->accounts = $accounts;
    }
    /**
     * The addresses that belong to this person or organization.
     *
     * @return \Gedcomx\Agent\Address[]
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * The addresses that belong to this person or organization.
     *
     * @param \Gedcomx\Agent\Address[] $addresses
     */
    public function setAddresses($addresses)
    {
        $this->addresses = $addresses;
    }
    /**
     * The emails that belong to this person or organization.
     *
     * @return \Gedcomx\Common\ResourceReference[]
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * The emails that belong to this person or organization.
     *
     * @param \Gedcomx\Common\ResourceReference[] $emails
     */
    public function setEmails($emails)
    {
        $this->emails = $emails;
    }
    /**
     * The homepage.
     *
     * @return \Gedcomx\Common\ResourceReference
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * The homepage.
     *
     * @param \Gedcomx\Common\ResourceReference $homepage
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;
    }
    /**
     * The list of identifiers for the agent.
     *
     * @return \Gedcomx\Conclusion\Identifier[]
     */
    public function getIdentifiers()
    {
        return $this->identifiers;
    }

    /**
     * The list of identifiers for the agent.
     *
     * @param \Gedcomx\Conclusion\Identifier[] $identifiers
     */
    public function setIdentifiers($identifiers)
    {
        $this->identifiers = $identifiers;
    }
    /**
     * The list of names for the agent.
     *
     * @return \Gedcomx\Common\TextValue[]
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * The list of names for the agent.
     *
     * @param \Gedcomx\Common\TextValue[] $names
     */
    public function setNames($names)
    {
        $this->names = $names;
    }
    /**
     * The &lt;a href=&quot;http://openid.net/&quot;&gt;openid&lt;/a&gt; of the person or organization.
     *
     * @return \Gedcomx\Common\ResourceReference
     */
    public function getOpenid()
    {
        return $this->openid;
    }

    /**
     * The &lt;a href=&quot;http://openid.net/&quot;&gt;openid&lt;/a&gt; of the person or organization.
     *
     * @param \Gedcomx\Common\ResourceReference $openid
     */
    public function setOpenid($openid)
    {
        $this->openid = $openid;
    }
    /**
     * The phones that belong to this person or organization.
     *
     * @return \Gedcomx\Common\ResourceReference[]
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * The phones that belong to this person or organization.
     *
     * @param \Gedcomx\Common\ResourceReference[] $phones
     */
    public function setPhones($phones)
    {
        $this->phones = $phones;
    }
    /**
     * Returns the associative array for this Agent
     *
     * @return array
     */
    public function toArray()
    {
        $a = parent::toArray();
        if ($this->accounts) {
            $ab = array();
            foreach ($this->accounts as $i => $x) {
                $ab[$i] = $x->toArray();
            }
            $a['accounts'] = $ab;
        }
        if ($this->addresses) {
            $ab = array();
            foreach ($this->addresses as $i => $x) {
                $ab[$i] = $x->toArray();
            }
            $a['addresses'] = $ab;
        }
        if ($this->emails) {
            $ab = array();
            foreach ($this->emails as $i => $x) {
                $ab[$i] = $x->toArray();
            }
            $a['emails'] = $ab;
        }
        if ($this->homepage) {
            $a["homepage"] = $this->homepage->toArray();
        }
        if ($this->identifiers) {
            $ab = array();
            foreach ($this->identifiers as $i => $x) {
                $ab[$i] = array();
                foreach ($x as $j => $y) {
                    $ab[$i][$j] = $y->getValue();
                }
            }
            $a['identifiers'] = $ab;
        }
        if ($this->names) {
            $ab = array();
            foreach ($this->names as $i => $x) {
                $ab[$i] = $x->toArray();
            }
            $a['names'] = $ab;
        }
        if ($this->openid) {
            $a["openid"] = $this->openid->toArray();
        }
        if ($this->phones) {
            $ab = array();
            foreach ($this->phones as $i => $x) {
                $ab[$i] = $x->toArray();
            }
            $a['phones'] = $ab;
        }
        return $a;
    }


    /**
     * Initializes this Agent from an associative array
     *
     * @param array $o
     */
    public function initFromArray($o)
    {
        parent::initFromArray($o);
        $this->accounts = array();
        if (isset($o['accounts'])) {
            foreach ($o['accounts'] as $i => $x) {
                    $this->accounts[$i] = new \Gedcomx\Agent\OnlineAccount($x);
            }
        }
        $this->addresses = array();
        if (isset($o['addresses'])) {
            foreach ($o['addresses'] as $i => $x) {
                    $this->addresses[$i] = new \Gedcomx\Agent\Address($x);
            }
        }
        $this->emails = array();
        if (isset($o['emails'])) {
            foreach ($o['emails'] as $i => $x) {
                    $this->emails[$i] = new \Gedcomx\Common\ResourceReference($x);
            }
        }
        if (isset($o['homepage'])) {
                $this->homepage = new \Gedcomx\Common\ResourceReference($o["homepage"]);
        }
        $this->identifiers = array();
        if (isset($o['identifiers'])) {
            foreach ($o['identifiers'] as $i => $x) {
                if (is_array($x)) {
                    $this->identifiers[$i] = array();
                    foreach ($x as $j => $y) {
                        $this->identifiers[$i][$j] = new \Gedcomx\Conclusion\Identifier();
                        $this->identifiers[$i][$j]->setValue($y);
                    }
                }
                else {
                    $this->identifiers[$i] = new \Gedcomx\Conclusion\Identifier($x);
                }
            }
        }
        $this->names = array();
        if (isset($o['names'])) {
            foreach ($o['names'] as $i => $x) {
                    $this->names[$i] = new \Gedcomx\Common\TextValue($x);
            }
        }
        if (isset($o['openid'])) {
                $this->openid = new \Gedcomx\Common\ResourceReference($o["openid"]);
        }
        $this->phones = array();
        if (isset($o['phones'])) {
            foreach ($o['phones'] as $i => $x) {
                    $this->phones[$i] = new \Gedcomx\Common\ResourceReference($x);
            }
        }
    }
}