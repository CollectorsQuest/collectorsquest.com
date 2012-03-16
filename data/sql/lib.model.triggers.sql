delimiter //

DROP TRIGGER IF EXISTS collection_collectible_after_update//
CREATE TRIGGER collection_collectible_after_update AFTER UPDATE ON collection_collectible
  FOR EACH ROW BEGIN
    IF (@DISABLE_TRIGGER <> 1 OR @DISABLE_TRIGGER IS NULL) THEN
      UPDATE collector_collection
      SET collector_collection.num_items = (SELECT COUNT(id) FROM collection_collectible WHERE collection_id = collector_collection.id)
      WHERE collector_collection.id = NEW.collection_id;
    END IF;
  END
;//

DROP TRIGGER IF EXISTS collection_collectible_after_delete//
CREATE TRIGGER collection_collectible_after_delete AFTER DELETE ON collection_collectible
  FOR EACH ROW BEGIN
    IF (@DISABLE_TRIGGER <> 1 OR @DISABLE_TRIGGER IS NULL) THEN
      UPDATE collector_collection
      SET collector_collection.num_items = (SELECT COUNT(id) FROM collection_collectible WHERE collection_id = collector_collection.id)
      WHERE collector_collection.id = OLD.collection_id;
    END IF;
  END
;//

DELIMITER ;
