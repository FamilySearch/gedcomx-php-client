<?php

namespace Gedcomx\Rs\Client\Util;

/**
 * Static method for embedding (merging) Gedcomx objects
 * 
 * Class Embedding
 * 
 * @package Gedcomx\Rs\Client\Util
 */
class Embedding
{
    /**
     * Merge a Gedcomx object into another
     * 
     * @param \Gedcomx\Gedcomx $destination
     * @param \Gedcomx\Gedcomx $source
     */
    public static function embedGedcomx($destination, $source)
    {
        $links = $source->getLinks();
        if ($links != null) {
            foreach ($links as $link) {
                $found = false;
                if ($link->getRel() != null) {
                    if ($destination->getLinks() != null) {
                        foreach ($destination->getLinks() as $target) {
                            if ($link->getRel() == $target->getRel()) {
                                $found = true;
                                break;
                            }
                        }
                    }
                }
                if (!$found) {
                    $destination->addLink($link);
                }
            }
        }
        
        $persons = $source->getPersons();
        if ($persons != null) {
            foreach ($persons as $person) {
                $found = false;
                if ($person->getId() != null) {
                    if ($destination->getPersons() != null) {
                        foreach ($destination->getPersons() as $target) {
                            if ($person->getId() == $target->getId()) {
                                Embedding::embedPerson($target, $person);
                                $found = true;
                                break;
                            }
                        }
                    }
                }
                if (!$found) {
                    $destination->addPerson($person);
                }
            }
        }
        
        $relationships = $source->getRelationships();
        if ($relationships != null) {
            foreach ($relationships as $relationship) {
                $found = false;
                if ($relationship->getId() != null) {
                    if ($destination->getRelationships() != null) {
                        foreach ($destination->getRelationships() as $target) {
                            if ($relationship->getId() == $target->getId()) {
                                Embedding::embedRelationship($target, $relationship);
                                $found = true;
                                break;
                            }
                        }
                    }
                }
                if (! $found) {
                    $destination->addRelationship($relationship);
                }
            }
        }
        
        $sourceDescriptions = $source->getSourceDescriptions();
        if ($sourceDescriptions != null) {
            foreach ($sourceDescriptions as $sourceDescription) {
                $found = false;
                if ($sourceDescription->getId() != null) {
                    if ($destination->getSourceDescriptions() != null) {
                        foreach ($destination->getSourceDescriptions() as $target) {
                            if ($sourceDescription->getId() == $target->getId()) {
                                Embedding::embedSourceDescription($target, $sourceDescription);
                                $found = true;
                                break;
                            }
                        }
                  }
                }
                if (! $found) {
                    $destination->addSourceDescription($sourceDescription);
                }
            }
        }
        
        $agents = $source->getAgents();
        if ($agents != null) {
            foreach ($agents as $agent) {
                $found = false;
                if ($agent->getId() != null) {
                    if ($destination->getAgents() != null) {
                        foreach ($destination->getAgents() as $target) {
                            if ($agent->getId() == $target->getId()) {
                                Embedding::embedAgent($target, $agent);
                                $found = true;
                                break;
                            }
                        }
                    }
                }
                if (! $found) {
                    $destination->addAgent($agent);
                }
            }
        }
        
        $events = $source->getEvents();
        if ($events != null) {
            foreach ($events as $event) {
                $found = false;
                if ($event->getId() != null) {
                    if ($destination->getEvents() != null) {
                        foreach ($destination->getEvents() as $target) {
                            if ($event->getId() == $target->getId()) {
                                Embedding::embedEvent($target, $event);
                                $found = true;
                                break;
                            }
                        }
                    }
                }
                if (! $found) {
                    $destination->addEvent(event);
                }
            }
        }
        
        $placeDescriptions = $source->getPlaces();
        if ($placeDescriptions != null) {
            foreach ($placeDescriptions as $placeDescription) {
                $found = false;
                if ($placeDescription->getId() != null) {
                    if ($destination->getPlaces() != null) {
                        foreach ($destination->getPlaces() as $target) {
                            if ($placeDescription->getId() == $target->getId()) {
                                Embedding::embedPlaceDescription($target, $placeDescription);
                                $found = true;
                                break;
                            }
                        }
                    }
                }
                if (! $found) {
                    $destination->addPlace($placeDescription);
                }
            }
        }
        
        $documents = $source->getDocuments();
        if ($documents != null) {
            foreach ($documents  as $document) {
                $found = false;
                if ($document->getId() != null) {
                    if ($destination->getDocuments() != null) {
                        foreach ($destination->getDocuments() as $target) {
                            if ($document.getId() == $target->getId()) {
                                Embedding::embedDocument($target, $document);
                                $found = true;
                                break;
                            }
                        }
                    }
                }
                if (!$found) {
                    $destination->addDocument($document);
                }
            }
        }
        
        $collections = $source->getCollections();
        if ($collections != null) {
            foreach ($collections as $collection) {
                $found = false;
                if ($collection->getId() != null) {
                    if ($destination->getCollections() != null) {
                        foreach ($destination->getCollections() as $target) {
                            if ($collection->getId() == $target->getId()) {
                                Embedding::embedCollection($target, $collection);
                                $found = true;
                                break;
                            }
                        }
                    }
                }
                if (! $found) {
                    $destination->addCollection($collection);
                }
            }
        }
        
        $fields = $source->getFields();
        if ($fields != null) {
            foreach ($fields as $field) {
                $found = false;
                if ($field->getId() != null) {
                    if ($destination->getFields() != null) {
                        foreach ($destination->getFields() as $target) {
                            if ($field->getId() == $target->getId()) {
                                $found = true;
                                break;
                            }
                        }
                    }
                }
                if (! $found) {
                    $destination->addField($field);
                }
          }
        }
        
        $recordDescriptors = $source->getRecordDescriptors();
        if ($recordDescriptors != null) {
            foreach ($recordDescriptors as $recordDescriptor) {
                $found = false;
                if ($recordDescriptor->getId() != null) {
                    if ($destination->getRecordDescriptors() != null) {
                        foreach ($destination->getRecordDescriptors() as $target) {
                            if ($recordDescriptor.getId() == $target->getId()) {
                                Embedding::embedRecordDescriptor($target, $recordDescriptor);
                                $found = true;
                                break;
                            }
                        }
                    }
                }
                if (! $found) {
                    $destination->addRecordDescriptor(recordDescriptor);
                }
            }
        }
    }
    
    /**
     * Merge one person into another
     * 
     * @param \Gedcomx\Conclusion\Person $destination
     * @param \Gedcomx\Conclusion\Person $source
     */
    public static function embedPerson($destination, $source)
    {
        if( $destination->isPrivate() == null ){
            $destination->setPrivate($source->isPrivate());
        }
        $destination->setLiving($destination->isLiving() == null ? $source->isLiving() : $destination->isLiving());
        $destination->setPrincipal($destination->getPrincipal() == null ? $source->getPrincipal() : $destination->getPrincipal());
        $destination->setGender($destination->getGender() == null ? $source->getGender() : $destination->getGender());
        if ($destination->getDisplayExtension() != null && $source->getDisplayExtension() != null) {
            Embedding::embedDisplayProperties($destination->getDisplayExtension(), $source->getDisplayExtension());
        }
        else if ($source->getDisplayExtension() != null) {
            $destination->setDisplayExtension($source->getDisplayExtension());
        }
        if ($source->getNames() != null) {
            if( $destination->getNames() == null ){
                $destination->setNames(array());
            }
            $destination->setNames(array_merge($destination->getNames(), $source->getNames()));
        }
        if ($source->getFacts() != null) {
            if( $destination->getFacts() == null ){
                $destination->setFacts(array());
            }
            $destination->setFacts(array_merge($destination->getFacts(), $source->getFacts()));
        }
        if ($source->getFields() != null) {
            if( $destination->getFields() == null ){
                $destination->setFields(array());
            }
            $destination->setFields(array_merge($destination->getFields(), $source->getFields()));
        }
        Embedding::embedSubject($destination, $source);
    }
    
    public static function embedRelationship($destination, $source)
    {
        Embedding::embedSubject($destination, $source);
    }
    
    public static function embedSourceDescription($destination, $source)
    {
        Embedding::embedHypermediaEnabledData($destination, $source);
    }
    
    public static function embedHypermediaEnabledData($destination, $source)
    {
        Embedding::embedExtensibleData($destination, $source);
    }
    
    public static function embedExtensibleData($destination, $source)
    {
        if ($source->getExtensionElements() != null) {
            $destination->setExtensionElements($destination->getExtensionElements() == null ? array() : $destination->getExtensionElements());
            $destination->setExtensionElements(array_merge($destination->getExtensionElements(), $source->getExtensionElements()));
        }
    }
    
    public static function embedSubject($destination, $source)
    {
        $destination->setExtracted($destination->getExtracted() == null ? $source->getExtracted() : $destination->getExtracted());
        if ($source->getIdentifiers() != null) {
            if( $destination->getIdentifiers() == null ) {
                $destination->setIdentifiers(array());
            }
            $destination->setIdentifiers(array_merge($destination->getIdentifiers(), $source->getIdentifiers()));
        }
        if ($source->getMedia() != null) {
            if( $destination->getMedia() == null ) {
                $destination->setMedia(array());
            }
            $destination->setMedia(array_merge($destination->getMedia(), $source->getMedia()));
        }
        if ($source->getEvidence() != null) {
            if( $destination->getEvidence() == null ) {
                $destination->setEvidence(array());
            }
            $destination->setEvidence(array_merge($destination->getEvidence(), $source->getEvidence()));
        }
        Embedding::embedConclusion($destination, $source);
    }
    
    public static function embedDisplayProperties($destination, $source)
    {
        Embedding::embedExtensibleData($destination, $source);
    }
    
    public static function embedAgent($destination, $source)
    {
        Embedding::embedHypermediaEnabledData($destination, $source);
    }
    
    public static function embedEvent($destination, $source)
    {
        Embedding::embedConclusion($destination, $source);
    }
    
    public static function embedConclusion($destination, $source)
    {
        if( $destination->getLang() != null ){
            $destination->setLang($source->getLang());
        }
        if( $destination->getConfidence() != null ){
            $destination->setConfidence($source->getConfidence());
        }
        if( $destination->getAttribution() != null ){
            $destination->setAttribution($source->getAttribution());
        }
        if( $destination->getAnalysis() != null ){
            $destination->setAnalysis($source->getAnalysis());
        }
        if ($source->getNotes() != null) {
            if( $destination->getNotes() != null ){
                $destination->setNotes(array());
            }
            $destination->setNotes(array_merge($destination->getNotes(), $source->getNotes()));
        }
        if ($source->getSources() != null) {
            if( $destination->getSources() == null ){
                $destination->setSources(array());
            }
            $destination->setSources(array_merge($destination->getSources(), $source->getSources()));
        }
        Embedding::embedHypermediaEnabledData ($destination, $source);
    }
    
    public static function embedPlaceDescription($destination, $source)
    {
        Embedding::embedSubject($destination, $source);
    }
    
    public static function embedDocument($destination, $source)
    {
        Embedding::embedConclusion($destination, $source);
    }
    
    public static function embedCollection($destination, $source)
    {
        Embedding::embedHypermediaEnabledData($destination, $source);
    }
    
    public static function embedRecordDescriptor($destination, $source)
    {
        Embedding::embedHypermediaEnabledData($destination, $source);
    }
    
    public static function embedFamilySearchPlatform($destination, $source)
    {
        $childRelationships = $source->getChildAndParentsRelationships();
        if ($childRelationships != null) {
            foreach ($childRelationships as $chr) {
                $found = false;
                if ($chr->getId() != null) {
                    if ($destination->getChildAndParentsRelationships() != null) {
                        foreach ($destination->getChildAndParentsRelationships() as $target) {
                            if ($chr->getId() == $target->getId()) {
                                Embedding::embedChildAndParentsRelationship($target, $chr);
                                $found = true;
                                break;
                            }
                        }
                    }
                }
                if (!$found) {
                    $destination->addChildAndParentsRelationship($chr);
                }
            }
        }
        
        $discussions = $source->getDiscussions();
        if ($discussions != null) {
            foreach ($discussions as $d) {
                $found = false;
                if ($d->getId() != null) {
                    if ($destination->getDiscussions() != null) {
                        foreach ($destination->getDiscussions() as $target) {
                            if ($d->getId() == $target->getId()) {
                                Embedding::embedDiscussion($target, $d);
                                $found = true;
                                break;
                            }
                        }
                    }
                }
                if (!$found) {
                    $destination->addDiscussion($d);
                }
            }
        }
        
        Embedding::embedGedcomx($destination, $source);
    }
    
    public static function embedChildAndParentsRelationship($destination, $source)
    {
        if ($source->getMotherFacts() != null) {
            if ($destination->getMotherFacts() == null) {
                $destination->setMotherFacts(array());
            }
            $destination->setMotherFacts(array_merge($destination->getMotherFacts(), $source->getMotherFacts()));
        }
        
        if ($source->getFatherFacts() != null) {
            if ($destination->getFatherFacts() == null) {
                $destination->setFatherFacts(array());
            }
            $destination->getFatherFacts(array_merge($destination->getFatherFacts(), $source->getFatherFacts()));
        }
        
        Embedding::embedSubject($destination, $source);
    }
    
    public static function embedDiscussion($destination, $source)
    {
        $comments = $source->getComments();
        if ($comments != null) {
            foreach ($comments as $comment) {
                $found = false;
                if ($comment->getId() != null) {
                    if ($destination->getComments() != null) {
                        foreach ($destination->getComments() as $target) {
                            if ($comment->getId() == $target->getId()) {
                                Embedding::embedComment($target, $comment);
                                $found = true;
                                break;
                            }
                        }
                    }
                }
                if (!$found) {
                    $this->addComment($comment);
                }
            }
        }
        
        Embedding::embedHypermediaEnabledData($destination, $source);
    }
    
    public static function embedComment($destination, $source)
    {
        Embedding::embedHypermediaEnabledData($destination, $source);
    }
}