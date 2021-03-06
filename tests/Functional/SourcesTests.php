<?php

namespace Gedcomx\Tests\Functional;

use Gedcomx\Common\Attribution;
use Gedcomx\Common\Note;
use Gedcomx\Common\ResourceReference;
use Gedcomx\Common\TextValue;
use Gedcomx\Extensions\FamilySearch\FamilySearchPlatform;
use Gedcomx\Extensions\FamilySearch\Platform\Tree\ChildAndParentsRelationship;
use Gedcomx\Extensions\FamilySearch\Rs\Client\FamilyTree\ChildAndParentsRelationshipState;
use Gedcomx\Extensions\FamilySearch\Rs\Client\FamilyTree\FamilyTreeCollectionState;
use Gedcomx\Extensions\FamilySearch\Rs\Client\FamilyTree\FamilyTreeRelationshipState;
use Gedcomx\Extensions\FamilySearch\Rs\Client\FamilyTree\FamilyTreeStateFactory;
use Gedcomx\Extensions\FamilySearch\Rs\Client\Rel;
use Gedcomx\Gedcomx;
use Gedcomx\Rs\Client\PersonState;
use Gedcomx\Rs\Client\RelationshipState;
use Gedcomx\Rs\Client\SourceDescriptionState;
use Gedcomx\Rs\Client\StateFactory;
use Gedcomx\Rs\Client\Util\DataSource;
use Gedcomx\Rs\Client\Util\HttpStatus;
use Gedcomx\Source\SourceCitation;
use Gedcomx\Source\SourceDescription;
use Gedcomx\Tests\ApiTestCase;
use Gedcomx\Tests\ArtifactBuilder;
use Gedcomx\Tests\SourceBuilder;
use Gedcomx\Extensions\FamilySearch\Rs\Client\FamilySearchSourceDescriptionState;
use Gedcomx\Extensions\FamilySearch\Rs\Client\FamilyTree\FamilyTreePersonState;
use Gedcomx\Source\SourceReference;
use GuzzleHttp\Request;
use Gedcomx\Tests\TestBuilder;

class SourcesTests extends ApiTestCase
{
    
    /**
     * @vcr SourcesTests/testCreatePersonSourceReference.json
     * @link https://familysearch.org/developers/docs/api/tree/Create_Person_Source_Reference_usecase
     */
    public function testCreatePersonSourceReference()
    {
        $factory = new StateFactory();
        $this->collectionState($factory);

        /** @var PersonState $personState */
        $personState = $this->createPerson();
        $this->assertEquals(HttpStatus::CREATED, $personState->getStatus() );
        $personState = $personState->get();
        $this->assertEquals(HttpStatus::OK, $personState->getStatus() );
        $sourceState = $this->createSource();
        $this->assertEquals(HttpStatus::CREATED, $sourceState->getStatus() );

        $reference = new SourceReference();
        $reference->setDescriptionRef($sourceState->getSelfUri());
        $reference->setAttribution( new Attribution( array("changeMessage" => TestBuilder::faker()->sentence(6))));
        /** @var \Gedcomx\Rs\Client\PersonState $newState */
        $newState = $personState->addSourceReferenceObj($reference);
        $this->assertEquals(HttpStatus::CREATED, $newState->getStatus() );

        $personState = $personState->get();
        $this->assertEquals(HttpStatus::OK, $personState->getStatus() );
        $this->assertNotNull($personState->getEntity());
        $this->assertNotEmpty($personState->getEntity()->getSourceDescriptions());
    }

    /**
     * @vcr SourcesTests/testCreateSourceDescription.json
     * @link https://familysearch.org/developers/docs/api/sources/Create_Source_Description_usecase
     */
    public function testCreateSourceDescription()
    {
        $this->collectionState(new StateFactory());
        /** @var SourceDescription $source */
        $source = SourceBuilder::newSource();
        $link = $this->collectionState()->getLink(Rel::SOURCE_DESCRIPTIONS);
        $this->assertNotNull($link, "SOURCE_DESCRIPTION rel not found on this collection.");

        $sourceState = $this->collectionState()->addSourceDescription($source);
        $this->assertEquals(
            HttpStatus::CREATED,
            $sourceState->getStatus(),
            $this->buildFailMessage(__METHOD__ . "(CREATE)", $sourceState)
        );

        /** @var SourceDescriptionState $sourceState */
        $sourceState = $sourceState->get();
        $this->assertNotNull($sourceState->getEntity(), "Entity is null.");
        $this->assertNotNull($sourceState->getSourceDescription(), "SourceDescription should not be empty.");
    }

    /**
     * @vcr SourcesTests/testCreateChildAndParentsRelationshipSourceReferences.json
     * @link https://familysearch.org/developers/docs/api/tree/Create_Child-and-Parents_Relationship_Source_Reference_usecase
     */
    public function testCreateChildAndParentsRelationshipSourceReferences()
    {
        $factory = new FamilyTreeStateFactory();
        /** @var FamilyTreeCollectionState $collection */
        $this->collectionState($factory);

        /** @var ChildAndParentsRelationshipState $relation */
        $relation = $this->createRelationship();
        $relation = $relation->get();
        $this->assertEquals(
            HttpStatus::OK,
            $relation->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $relation)
        );
        $sourceState = $this->createSource();
        $this->assertEquals(
            HttpStatus::CREATED,
            $sourceState->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $sourceState)
        );
        $this->queueForDelete($sourceState);

        $reference = new SourceReference();
        $reference->setDescriptionRef($sourceState->getSelfUri());
        $reference->setAttribution( new Attribution( array(
                                                         "changeMessage" => TestBuilder::faker()->sentence(6)
                                                     )));
        $newState = $relation->addSourceReference($reference);
        $this->assertEquals(
            HttpStatus::CREATED,
            $newState->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $newState)
        );

        $relation = $relation->get();
        $relation->loadSourceReferences();
        $this->assertNotEmpty($relation->getRelationship()->getSources());
    }

    /**
     * @vcr SourcesTests/testCreateCoupleRelationshipSourceReference.json
     * @link https://familysearch.org/developers/docs/api/tree/Create_Couple_Relationship_Source_Reference_usecase
     * @link https://familysearch.org/developers/docs/api/tree/Read_Couple_Relationship_Sources_usecase
     * @link https://familysearch.org/developers/docs/api/tree/Read_Couple_Relationship_Source_References_usecase
     */
    public function testCreateCoupleRelationshipSourceReference()
    {
        $factory = new FamilyTreeStateFactory();
        $this->collectionState($factory);

        $person1 = $this->createPerson('male');
        $this->assertEquals(
            HttpStatus::CREATED,
            $person1->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $person1)
        );
        $person1 = $person1->get();
        $this->assertEquals(
            HttpStatus::OK,
            $person1->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $person1)
        );
        $person2 = $this->createPerson('female');
        $this->assertEquals(
            HttpStatus::CREATED,
            $person2->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $person2)
        );
        $person2 = $person2->get();
        $this->assertEquals(
            HttpStatus::OK,
            $person2->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $person2)
        );

        /* Create Relationship */
        /** @var FamilyTreeRelationshipState $relation */
        $relation = $this->collectionState()->addSpouseRelationship($person1, $person2);
        $this->assertEquals(
            HttpStatus::CREATED,
            $relation->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $relation)
        );
        $this->queueForDelete($relation);

        $relation = $relation->get();
        $this->assertEquals(
            HttpStatus::OK,
            $relation->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $relation)
        );

        /* Create source */
        $sourceState = $this->createSource();
        $this->assertEquals(
            HttpStatus::CREATED,
            $sourceState->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $sourceState)
        );
        $this->queueForDelete($sourceState);

        $reference = new SourceReference();
        $reference->setDescriptionRef($sourceState->getSelfUri());
        $reference->setAttribution( new Attribution( array(
                                                         "changeMessage" => TestBuilder::faker()->sentence(6)
                                                     )));

        /* CREATE the source reference on the relationship */
        $sourceRef = $relation->addSourceReference($reference);
        $this->assertEquals(
            HttpStatus::CREATED,
            $sourceRef->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $sourceRef)
        );
        $relation = $relation->get();
        $relation->loadSourceReferences();
        $this->assertNotEmpty($relation->getRelationship()->getSources());
    }

    /**
     * @link https://familysearch.org/developers/docs/api/sources/Create_User-Uploaded_Source_usecase
     */
    public function testCreateUserUploadedSource()
    {
        $this->markTestSkipped("Memories tests are slow and unreliable.");
        
        $this->collectionState(new FamilyTreeStateFactory());
        /** @var FamilyTreePersonState $person */
        $person = $this->createPerson();
        $this->assertEquals(
            HttpStatus::CREATED,
            $person->getStatus(),
            $this->buildFailMessage(__METHOD__.'(create person)', $person)
        );

        $person = $person->get();
        $this->assertEquals(
            HttpStatus::OK,
            $person->getStatus(),
            $this->buildFailMessage(__METHOD__.'(read person)', $person)
        );

        $ds = new DataSource();
        $ds->setTitle("Sample Memory");
        $ds->setFile(ArtifactBuilder::makeTextFile());
        $a1 = $person->addArtifact($ds);
        $this->assertEquals(
            HttpStatus::CREATED,
            $a1->getStatus(),
            $this->buildFailMessage(__METHOD__.'(add artifact)', $a1)
        );
        $this->queueForDelete($a1);
        $this->assertEquals(HttpStatus::CREATED, $a1->getStatus());

        $artifacts = $person->readArtifacts();
        $this->assertEquals(HttpStatus::OK, $artifacts->getStatus());

        /** @var \Gedcomx\Rs\Client\SourceDescriptionState $artifact */
        $artifact = $person->readArtifacts();
        $this->assertEquals(
            HttpStatus::OK,
            $artifact->getStatus(),
            $this->buildFailMessage(__METHOD__.'(read artifact)', $artifact)
        );
        $artifact = $artifact->getSourceDescription();

        $memoryUri = $artifact->getLink("memory")->getHref();
        $source = SourceBuilder::newSource();
        $source->setAbout($memoryUri);
        $state = $this->collectionState()->addSourceDescription($source);
        $this->queueForDelete($state);

        $this->assertNotNull($state->ifSuccessful());
        $this->assertEquals(
            HttpStatus::CREATED,
            $state->getStatus(),
            $this->buildFailMessage(__METHOD__.'(source description)', $state)
        );
    }

    /**
     * @vcr SourcesTests/testReadPersonSourceReferences.json
     * @link https://familysearch.org/developers/docs/api/tree/Read_Person_Source_References_usecase
     */
    public function testReadPersonSourceReferences(){
        $factory = new StateFactory();
        $this->collectionState($factory);

        //  Set up the data we need
        /** @var PersonState $testSubject */
        $testSubject = $this->createPerson();
        $this->assertAttributeEquals(HttpStatus::CREATED, "statusCode", $testSubject->getResponse() );
        $testSubject = $testSubject->get();
        $this->assertAttributeEquals(HttpStatus::OK, "statusCode", $testSubject->getResponse() );

        $source = SourceBuilder::hitchhiker();
        $sourceState = $this->collectionState()->addSourceDescription($source);
        $this->assertAttributeEquals(HttpStatus::CREATED, "statusCode", $sourceState->getResponse() );
        $this->queueForDelete($sourceState);

        $sourceRef = $testSubject->addSourceReferenceState($sourceState);
        $this->assertAttributeEquals(HttpStatus::CREATED, "statusCode", $sourceRef->getResponse() );

        //  Now test it
        $testSubject->loadSourceReferences();

        $this->assertAttributeEquals(HttpStatus::OK, "statusCode", $testSubject->getResponse() );
        $this->assertNotEmpty($testSubject->getEntity()->getSourceDescriptions());
    }

    /**
     * @vcr SourcesTests/testReadPersonSources.json
     * @link https://familysearch.org/developers/docs/api/tree/Read_Person_Sources_usecase
     */
    public function testReadPersonSources()
    {
        $factory = new FamilyTreeStateFactory();
        $this->collectionState($factory);
        $this->assertNotNull($this->collectionState());
        $this->assertTrue($this->collectionState()->isAuthenticated());
        $this->assertNotNull($this->collectionState()->getClient());
        $client = $this->collectionState()->getClient();
        $this->assertNotEmpty($this->collectionState()->getAccessToken());
        $token = $this->collectionState()->getAccessToken();
        /** @var FamilyTreePersonState $person */
        $person = $this->createPerson();
        $this->assertEquals(HttpStatus::CREATED, $person->getStatus());
        $person = $person->get();
        $this->assertEquals(HttpStatus::OK, $person->getStatus());
        $sds = $this->collectionState()->addSourceDescription(SourceBuilder::hitchhiker());
        $this->queueForDelete($sds);

        $person->addSourceReferenceState($sds);
        $state = $person->readSources();
        $this->queueForDelete($state);

        $this->assertNotNull($state->ifSuccessful());
        $this->assertEquals(HttpStatus::OK, $state->getStatus());
        $this->assertNotNull($state->getSourceDescription());
        $this->assertNotNull($state->getEntity());
        $this->assertNotNull($state->getEntity()->getPersons());
    }

    /**
     * @vcr SourcesTests/testReadSourceDescription.json
     * @link https://familysearch.org/developers/docs/api/sources/Read_Source_Description_usecase
     * @throws \Gedcomx\Rs\Client\Exception\GedcomxApplicationException
     */
    public function testReadSourceDescription()
    {
        $this->collectionState(new FamilyTreeStateFactory());
        $sd = SourceBuilder::newSource();
        /** @var SourceDescriptionState $description */
        $description = $this->collectionState()->addSourceDescription($sd);
        $this->assertEquals(HttpStatus::CREATED, $description->getStatus());
        $this->queueForDelete($description);

        $description = $description->get();
        $this->assertNotNull($description->ifSuccessful());
        $this->assertEquals(HttpStatus::OK, $description->getStatus());
        $this->assertNotNull($description->getSourceDescription());
    }

    /**
     * @vcr SourcesTests/testReadSourceReferences.json
     * @link https://familysearch.org/developers/docs/api/tree/Read_Source_References_usecase
     */
    public function testReadSourceReferences()
    {
        $factory = new FamilyTreeStateFactory();
        $this->collectionState($factory);
        $sd = SourceBuilder::hitchhiker();
        /** @var SourceDescriptionState $source */
        $source = $this->collectionState()->addSourceDescription($sd)->get();
        $this->queueForDelete($source);

        /** @var FamilyTreePersonState $person */
        $person = $this->createPerson();
        $sourceRef = new SourceReference();
        $sourceRef->setAttribution( new Attribution( array(
            "changeMessage" => TestBuilder::faker()->sentence(6)
        )));
        $sourceRef->setDescriptionRef($source->getSelfUri());
        $person->addSourceReferenceObj($sourceRef);
        $state = $source->queryAttachedReferences();

        $this->assertNotNull($state->ifSuccessful());
        $this->assertEquals(HttpStatus::OK, $state->getStatus());
        $this->assertNotNull($state->getEntity());
        $this->assertNotNull($state->getEntity()->getPersons());
        $this->assertGreaterThan(0, count($state->getEntity()->getPersons()));
    }

    /**
     * testReadChildAndParentsRelationshipSourceReferences
     * @link https://familysearch.org/developers/docs/api/tree/Read_Child-and-Parents_Relationship_Source_References_usecase
     * @see SourcesTests::testCreateChildAndParentsRelationshipSourceReferences
     */

    /**
     * @vcr SourcesTests/testReadChildAndParentsRelationshipSources.json
     * @link https://familysearch.org/developers/docs/api/tree/Read_Child-and-Parents_Relationship_Sources_usecase
     */
    public function testReadChildAndParentsRelationshipSources()
    {
        $factory = new FamilyTreeStateFactory();
        $this->collectionState($factory);
        $this->assertNotNull($this->collectionState());
        $this->assertTrue($this->collectionState()->isAuthenticated());
        $this->assertNotNull($this->collectionState()->getClient());
        $client = $this->collectionState()->getClient();
        $this->assertNotEmpty($this->collectionState()->getAccessToken());
        $token = $this->collectionState()->getAccessToken();
        /** @var FamilyTreePersonState $father */
        $father = $this->createPerson('male');
        $this->assertEquals(HttpStatus::CREATED, $father->getStatus());
        $father = $father->get();
        $this->assertEquals(HttpStatus::OK, $father->getStatus());
        $child = $this->createPerson();
        $this->assertEquals(HttpStatus::CREATED, $child->getStatus());
        $chapr = new ChildAndParentsRelationship();
        $this->assertNotEmpty($father->getResourceReference());
        $chapr->setFather($father->getResourceReference());
        $this->assertnotnull($child->getResourceReference());
        $chapr->setChild($child->getResourceReference());
        /** @var ChildAndParentsRelationshipState $relation */
        $relation = $this->collectionState()->addChildAndParentsRelationship($chapr);
        $this->queueForDelete($relation);
        $this->assertEquals(HttpStatus::CREATED, $relation->getStatus());
        $relation = $relation->get();
        $this->assertEquals(HttpStatus::OK, $relation->getStatus());

        /** @var SourceDescriptionState $sds */
        $sds = $this->collectionState()->addSourceDescription(SourceBuilder::hitchhiker());
        $this->queueForDelete($sds);
        $this->assertEquals(HttpStatus::CREATED, $sds->getStatus());
        $sds = $sds->get();
        $this->assertEquals(HttpStatus::OK, $sds->getStatus());
        $relation->addSourceReferenceState($sds);
        $relationships = $father->get()->getChildAndParentsRelationships();
        $this->assertNotEmpty($relationships);
        $relationship = array_shift($relationships);
        $link1 = $relationship->getLink(Rel::RELATIONSHIP);
        $link2 = $relationship->getLink(Rel::SELF);
        $this->assertTrue($link1 != null || $link2 != null);
        $relation = $father->readChildAndParentsRelationship($relationship);
        $state = $relation->readSources();

        $this->assertNotNull($state->ifSuccessful());
        $this->assertEquals(HttpStatus::OK, $state->getStatus());
        $this->assertNotNull($state->getSourceDescription());
    }

    /**
     * @vcr SourcesTests/testReadCoupleRelationshipSourceReferences.json
     * @link https://familysearch.org/developers/docs/api/tree/Read_Couple_Relationship_Sources_usecase
     * @link https://familysearch.org/developers/docs/api/tree/Read_Couple_Relationship_Source_References_usecase
     */
    public function testReadCoupleRelationshipSourceReferences()
    {
        $factory = new FamilyTreeStateFactory();
        $this->collectionState($factory);

        $person1 = $this->createPerson('male')->get();
        $person2 = $this->createPerson('female')->get();

        /* Create Relationship */
        /** @var $relation RelationshipState */
        $relation = $this->collectionState()->addSpouseRelationship($person1, $person2)->get();
        $this->queueForDelete($relation);
        $this->assertAttributeEquals(HttpStatus::OK, "statusCode", $relation->getResponse(), $this->buildFailMessage(__METHOD__."(addSpouse)", $relation));

        /* Create source */
        $sourceState = $this->createSource();
        $this->assertAttributeEquals(HttpStatus::CREATED, "statusCode", $sourceState->getResponse(), $this->buildFailMessage(__METHOD__."(createSource)", $sourceState));

        $reference = new SourceReference();
        $reference->setDescriptionRef($sourceState->getSelfUri());
        $reference->setAttribution( new Attribution( array(
                                                         "changeMessage" => TestBuilder::faker()->sentence(6)
                                                     )));

        /* CREATE the source reference on the relationship */
        $updated = $relation->addSourceReference($reference);
        $this->assertAttributeEquals(HttpStatus::CREATED, "statusCode", $updated->getResponse(), $this->buildFailMessage(__METHOD__."(addReference)", $updated));

        /* READ the source references back */
        $relation = $relation->get();
        $relation->loadSourceReferences();
        $this->assertNotEmpty($relation->getRelationship()->getSources(), "loadForRead");
    }

    /**
     * @vcr SourcesTests/testReadCoupleRelationshipSources.json
     * @link https://familysearch.org/developers/docs/api/tree/Read_Couple_Relationship_Sources_usecase
     */
    public function testReadCoupleRelationshipSources()
    {
        $factory = new FamilyTreeStateFactory();
        $this->collectionState($factory);
        $this->assertTrue($this->collectionState()->isAuthenticated());
        $this->assertNotNull($this->collectionState()->getClient());
        $client = $this->collectionState()->getClient();
        $this->assertNotNull($this->collectionState()->getAccessToken());
        $token = $this->collectionState()->getAccessToken();
        /** @var FamilyTreePersonState $husband */
        $husband = $this->createPerson('male');
        $this->assertEquals(HttpStatus::CREATED, $husband->getStatus());
        $husband = $husband->get();
        $this->assertEquals(HttpStatus::OK, $husband->getStatus());
        $wife = $this->createPerson('female');
        $this->assertEquals(HttpStatus::CREATED, $wife->getStatus());
        /** @var RelationshipState $relation */
        $relation = $husband->addSpouse($wife);
        $this->queueForDelete($relation);
        $this->assertEquals(HttpStatus::CREATED, $relation->getStatus());

        $sds = $this->collectionState()->addSourceDescription(SourceBuilder::hitchhiker());
        $this->queueForDelete($sds);
        $this->assertEquals(HttpStatus::CREATED, $sds->getStatus());

        $relation->addSourceDescriptionState($sds);
        $relationships = $husband->get();
        $this->assertEquals(HttpStatus::OK, $relationships->getStatus());
        $relations = $relationships->getRelationships();
        $this->assertNotEmpty($relations);
        $relationship = array_shift($relations);
        $relation = $husband->readRelationship($relationship);
        $state = $relation->readSources();

        $this->assertNotNull($state->ifSuccessful());
        $this->assertEquals(HttpStatus::OK, $state->getStatus());
        $this->assertNotNull($state->getSourceDescription());
        $this->assertNotEmpty($state->getEntity()->getRelationships());
    }

    /**
     * @vcr SourcesTests/testUpdatePersonSourceReference.json
     * @link https://familysearch.org/developers/docs/api/tree/Update_Person_Source_Reference_usecase
     */
    public function testUpdatePersonSourceReference()
    {
        $factory = new StateFactory();
        $this->collectionState($factory);

        /** @var PersonState $personState */
        $personState = $this->createPerson();
        $this->assertEquals(
            HttpStatus::CREATED,
            $personState->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $personState)
        );
        $personState = $personState->get();
        $this->assertEquals(
            HttpStatus::OK,
            $personState->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $personState)
        );

        $sourceState = $this->createSource();
        $this->assertEquals(
            HttpStatus::CREATED,
            $sourceState->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $sourceState)
        );

        $reference = new SourceReference();
        $reference->setDescriptionRef($sourceState->getSelfUri());
        $reference->setAttribution( new Attribution( array(
                                                         "changeMessage" => TestBuilder::faker()->sentence(6)
                                                     )));

        $newState = $personState->addSourceReferenceObj($reference);
        $this->assertEquals(
            HttpStatus::CREATED,
            $newState->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $newState)
        );

        $personState->loadSourceReferences();

        $this->assertNotNull($personState->getEntity());
        $persons = $personState->getEntity()->getPersons();
        $newerState = $personState->updateSourceReferences($persons[0]);
        $this->assertEquals(
            HttpStatus::NO_CONTENT,
            $newerState->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $newerState)
        );
    }

    /**
     * @vcr SourcesTests/testUpdateSourceDescription.json
     * @link https://familysearch.org/developers/docs/api/sources/Update_Source_Description_usecase
     * @throws \Gedcomx\Rs\Client\Exception\GedcomxApplicationException
     */
    public function testUpdateSourceDescription()
    {
        $this->collectionState(new FamilyTreeStateFactory());
        $sd = SourceBuilder::newSource();
        /** @var SourceDescriptionState $description */
        $description = $this->collectionState()->addSourceDescription($sd);
        $this->assertEquals(HttpStatus::CREATED, $description->getStatus());
        $this->queueForDelete($description);

        $description = $description->get();
        $this->assertEquals(HttpStatus::OK, $description->getStatus());

        $state = $description->update($description->getSourceDescription());
        $this->assertNotNull($state->ifSuccessful());
        $this->assertEquals(HttpStatus::NO_CONTENT, $state->getStatus());
    }

    /**
     * @vcr SourcesTests/testDeletePersonSourceReference.json
     * @link https://familysearch.org/developers/docs/api/tree/Delete_Person_Source_Reference_usecase
     */
    public function testDeletePersonSourceReference()
    {
        $factory = new StateFactory();
        $this->collectionState($factory);

        /** @var PersonState $personState */
        $personState = $this->createPerson();
        $this->assertEquals(
            HttpStatus::CREATED,
            $personState->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $personState)
        );
        $personState = $personState->get();
        $this->assertEquals(
            HttpStatus::OK,
            $personState->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $personState)
        );

        $sourceState = $this->createSource();
        $this->assertEquals(
            HttpStatus::CREATED,
            $sourceState->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $personState)
        );

        $reference = new SourceReference();
        $reference->setDescriptionRef($sourceState->getSelfUri());

        $added = $personState->addSourceReferenceObj($reference);
        $this->assertEquals(
            HttpStatus::CREATED,
            $added->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $added)
        );
        $personState = $personState->get();
        $personState->loadSourceReferences();

        /** @var \Gedcomx\Conclusion\Person[] $persons */
        $persons = $personState->getEntity()->getPersons();
        $references = $persons[0]->getSources();
        $newerState = $personState->deleteSourceReference($references[0]);
        $this->assertEquals(
            HttpStatus::NO_CONTENT,
            $newerState->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $newerState)
        );
    }

    /**
     * @vcr SourcesTests/testDeleteSourceDescription.json
     * @link https://familysearch.org/developers/docs/api/sources/Delete_Source_Description_usecase
     */
    public function testDeleteSourceDescription()
    {
        $this->collectionState(new FamilyTreeStateFactory());
        $sd = SourceBuilder::newSource();

        /** @var SourceDescriptionState $description */
        $description = $this->collectionState()->addSourceDescription($sd);
        $this->assertEquals(HttpStatus::CREATED, $description->getStatus());
        $this->queueForDelete($description);

        $description = $description->get();
        $this->assertEquals(HttpStatus::OK, $description->getStatus());

        $state = $description->delete();
        $this->assertNotNull($state->ifSuccessful());
        $this->assertEquals(HttpStatus::NO_CONTENT, $state->getStatus());
    }

    /**
     * @vcr SourcesTests/testDeleteChildAndParentsRelationshipSourceReference.json
     * @link https://familysearch.org/developers/docs/api/tree/Delete_Child-and-Parents_Relationship_Source_Reference_usecase
     */
    public function testDeleteChildAndParentsRelationshipSourceReference()
    {
        $factory = new FamilyTreeStateFactory();
        /** @var FamilyTreeCollectionState $collection */
        $this->collectionState($factory);

        /** @var ChildAndParentsRelationshipState $relation */
        $relation = $this->createRelationship();
        $relation = $relation->get();
        $this->assertEquals(
            HttpStatus::OK,
            $relation->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $relation)
        );
        $sourceState = $this->createSource();
        $this->assertEquals(
            HttpStatus::CREATED,
            $sourceState->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $sourceState)
        );
        $this->queueForDelete($sourceState);

        $reference = new SourceReference();
        $reference->setDescriptionRef($sourceState->getSelfUri());
        $reference->setAttribution( new Attribution( array(
                                                         "changeMessage" => TestBuilder::faker()->sentence(6)
                                                     )));
        $newState = $relation->addSourceReference($reference);
        $this->assertEquals(
            HttpStatus::CREATED,
            $newState->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $newState)
        );

        $relation = $relation->get();
        $relation->loadSourceReferences();
        $this->assertNotEmpty($relation->getRelationship()->getSources());

        $sources = $relation->getRelationship()->getSources();
        $deleted = $relation->deleteSourceReference($sources[0]);
        $this->assertEquals(
            HttpStatus::NO_CONTENT,
            $deleted->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $deleted)
        );

        $relation = $relation->get($this->createCacheBreakerQueryParam());
        $relation->loadSourceReferences();
        $this->assertEmpty($relation->getRelationship()->getSources());
    }

    /**
     * @vcr SourcesTests/testDeleteCoupleRelationshipSourceReference.json
     * @link https://familysearch.org/developers/docs/api/tree/Delete_Couple_Relationship_Source_Reference_usecase
     */
    public function testDeleteCoupleRelationshipSourceReference()
    {
        $factory = new FamilyTreeStateFactory();
        $this->collectionState($factory);

        /** @var FamilyTreePersonState $husband */
        $husband = $this->createPerson('male');
        $this->assertEquals(
            HttpStatus::CREATED,
            $husband->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $husband)
        );
        $husband = $husband->get();
        $this->assertEquals(
            HttpStatus::OK,
            $husband->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $husband)
        );

        /** @var FamilyTreePersonState $wife */
        $wife = $this->createPerson('female');
        $this->assertEquals(
            HttpStatus::CREATED,
            $wife->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $wife)
        );

        /** @var FamilyTreeRelationshipState $relationship */
        $relationship = $husband->addSpouse($wife);
        $this->assertEquals(
            HttpStatus::CREATED,
            $relationship->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $relationship)
        );
        $this->queueForDelete($relationship);
        $relationship = $relationship->get();
        $this->assertEquals(
            HttpStatus::OK,
            $relationship->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $relationship)
        );

        $sourceState = $this->createSource();
        $reference = new SourceReference();
        $reference->setDescriptionRef($sourceState->getSelfUri());
        $reference->setAttribution( new Attribution( array(
            "changeMessage" => TestBuilder::faker()->sentence(6)
        )));
        $sourceRef = $relationship->addSourceReference($reference);
        $this->assertEquals(
            HttpStatus::CREATED,
            $sourceRef->getStatus(),
            $this->buildFailMessage(__METHOD__.':'.__LINE__, $sourceRef)
        );

        $relationship = $relationship->get();
        $relationship->loadSourceReferences();
        $this->assertNotEmpty($relationship->getRelationship()->getSources());

        $state = $relationship->deleteSourceReference($relationship->getSourceReference());
        $this->AssertNotNull($state->ifSuccessful());
        $this->assertEquals(HttpStatus::NO_CONTENT, $state->getStatus());

        $relationship = $relationship->get($this->createCacheBreakerQueryParam());
        $relationship->loadSourceReferences();
        $this->assertEmpty($relationship->getRelationship()->getSources());
    }


}
