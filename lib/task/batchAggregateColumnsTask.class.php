<?php

class batchAggregateColumnsTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
    ));

    $this->namespace = 'batch';
    $this->name      = 'aggregate-columns';
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);

    /** @var $connection PropelPDO */
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    /**
     * Number of Collectibles
     */
    $sql = sprintf(
      'UPDATE %s SET %s = (SELECT COUNT(*) FROM %s WHERE %s = %s)',
      CollectorCollectionPeer::TABLE_NAME, CollectorCollectionPeer::NUM_ITEMS,
      CollectionCollectiblePeer::TABLE_NAME, CollectionCollectiblePeer::COLLECTION_ID, CollectorCollectionPeer::ID
    );
    $connection->exec($sql);

    $sql = sprintf(
      'UPDATE %s SET %s = (SELECT COUNT(*) FROM %s WHERE %s = %s)',
      CollectionPeer::TABLE_NAME, CollectionPeer::NUM_ITEMS,
      CollectionCollectiblePeer::TABLE_NAME, CollectionCollectiblePeer::COLLECTION_ID, CollectionPeer::ID
    );
    $connection->exec($sql);


    /**
     * Number of Comments
     */
    $sql = sprintf(
      'UPDATE %s SET %s = (SELECT COUNT(*) FROM %s WHERE %s = %s)',
      CollectorCollectionPeer::TABLE_NAME, CollectorCollectionPeer::NUM_COMMENTS,
      CommentPeer::TABLE_NAME, CommentPeer::COLLECTION_ID, CollectorCollectionPeer::ID
    );
    $connection->exec($sql);

    $sql = sprintf(
      'UPDATE %s SET %s = (SELECT COUNT(*) FROM %s WHERE %s = %s)',
      CollectionPeer::TABLE_NAME, CollectionPeer::NUM_COMMENTS,
      CommentPeer::TABLE_NAME, CommentPeer::COLLECTION_ID, CollectionPeer::ID
    );
    $connection->exec($sql);

    $sql = sprintf(
      'UPDATE %s SET %s = (SELECT COUNT(*) FROM %s WHERE %s = %s)',
      CollectiblePeer::TABLE_NAME, CollectiblePeer::NUM_COMMENTS,
      CommentPeer::TABLE_NAME, CommentPeer::COLLECTION_ID, CollectiblePeer::ID
    );
    $connection->exec($sql);
  }
}
