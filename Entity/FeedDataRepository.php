<?php
// plugins/HelloWorldBundle/Entity/FeedDataRepository.php

namespace MauticPlugin\FeedManBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

/**
 * FeedRepository
 */
class FeedDataRepository extends CommonRepository
{

    public function getEntities($args = array())
    {
        $q = $this
            ->createQueryBuilder('w');

        $args['qb'] = $q;

        return parent::getEntities($args);
    }
	/**
     * {@inheritdoc}
     */
    public function getEntity($id = 0)
    {
        $entity = parent::getEntity($id);
	
        return $entity;
    }
	 /**
     * Delete an entity
     *
     * @param object $entity
     *
     * @return void
     */
    public function deleteEntities($ids)
    {	
         return parent::deleteEntities($ids);
    }
}
