<?php

class TermPeer extends BaseTermPeer
{
  public static function addTerms($terms, $model)
  {
    if (!is_array($terms))
    {
    	$terms = array($terms);
    }
    if (!is_object($model))
    {
      return;
    }

    foreach ($terms as $name)
    {
      $c = new Criteria;
      $c->add(TermPeer::NAME, $name);
      if (!$term = TermPeer::doSelectOne($c))
      {
        $term = new Term;
        $term->setName($name);
        $term->save();
      }

      $c = new Criteria();
      $c->add(TermRelationshipPeer::TERM_ID, $term->getId());
      $c->add(TermRelationshipPeer::MODEL, get_class($model));
      $c->add(TermRelationshipPeer::MODEL_ID, $model->getId());

      if (!$term_relationship = TermRelationshipPeer::doSelectOne($c))
      {
        $term_relationship = new TermRelationship;
        $term_relationship->setTermId($term->getId());
        $term_relationship->setModel(get_class($model));
        $term_relationship->setModelId($model->getId());
        $term_relationship->save();
      }
    }
  }

  public static function getTermIds($object)
  {
  	if (!is_object($object)) 
    {
      return array();
    }

    $c = new Criteria;
    $c->addSelectColumn(TermRelationshipPeer::TERM_ID);
    $c->add(TermRelationshipPeer::MODEL_ID, $object->getId());
    $c->add(TermRelationshipPeer::MODEL, get_class($object));
    $c->addJoin(TermRelationshipPeer::TERM_ID, TermPeer::ID);
    $c->add(TermPeer::NAME, 'other', Criteria::NOT_EQUAL);
    $c->add(TermPeer::NAME, 'vintage', Criteria::NOT_EQUAL);

    $stmt = TermRelationshipPeer::doSelectStmt($c);

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }

  public static function getTerms($object)
  {
    if (!is_object($object)) return array();

    $c = new Criteria;
    $c->addSelectColumn(TermPeer::NAME);
    $c->addJoin(TermPeer::ID, TermRelationshipPeer::TERM_ID);
    $c->add(TermRelationshipPeer::MODEL_ID, $object->getId());
    $c->add(TermRelationshipPeer::MODEL, get_class($object));

    $stmt = TermPeer::doSelectStmt($c);

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }
}
