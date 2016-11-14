<?php 

namespace Gedcomx\Tests\Functional;

use Gedcomx\Tests\ApiTestCase;
use Gedcomx\Tests\PersonBuilder;

class Dec6PendingModsTests extends ApiTestCase
{
    
    /**
     * @vcr Dec6PendingModsTests/testPersonWithRelationshipsRedirect.json
     */
    public function testPersonWithRelationshipsRedirect()
    {
        $client = $this->createAuthenticatedFamilySearchClient([
           'pendingModifications' => ['consolidate-redundant-resources']
        ]);
        
        $personState = $client->familytree()->addPerson(PersonBuilder::buildPerson(''))->get();
        $this->queueForDelete($personState);
        $personId = $personState->getPerson()->getId();
        
        $redirectState = $client->familytree()->readPersonWithRelationshipsById($personId);
        
        $this->assertGreaterThanOrEqual(0, strpos($redirectState->getResponse()->effectiveUri, '/platform/tree/persons/' . $personId));
        $this->assertNotEmpty($redirectState->getPerson(), "No person returned.");
    }
}
