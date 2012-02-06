delimiter //

DROP TRIGGER IF EXISTS insert_collectible//
CREATE TRIGGER insert_collectible AFTER INSERT ON collectible
  FOR EACH ROW BEGIN
    IF (@DISABLE_TRIGER <> 1 OR @DISABLE_TRIGER IS NULL) THEN
      UPDATE collection SET collection.num_items = collection.num_items + 1
       WHERE collection.id = NEW.collection_id;
    END IF;
  END;
//

DROP TRIGGER IF EXISTS delete_collectible//
CREATE TRIGGER delete_collectible BEFORE DELETE ON collectible
  FOR EACH ROW BEGIN
    IF (@DISABLE_TRIGER <> 1 OR @DISABLE_TRIGER IS NULL) THEN
      UPDATE collection SET collection.num_items = collection.num_items - 1
       WHERE collection.id = OLD.collection_id;
    END IF;
  END;
//

DROP TRIGGER IF EXISTS insert_comment//
CREATE TRIGGER insert_comment AFTER INSERT ON comment
  FOR EACH ROW BEGIN
    IF (@DISABLE_TRIGER <> 1 OR @DISABLE_TRIGER IS NULL) THEN
      UPDATE collection SET collection.num_comments = collection.num_comments + 1
       WHERE collection.id = NEW.collection_id;
      UPDATE collectible SET collectible.num_comments = collectible.num_comments + 1
       WHERE collectible.id = NEW.collectible_id;
    END IF;
  END;
//

DROP TRIGGER IF EXISTS delete_comment//
CREATE TRIGGER delete_comment BEFORE DELETE ON comment
  FOR EACH ROW BEGIN
    IF (@DISABLE_TRIGER <> 1 OR @DISABLE_TRIGER IS NULL) THEN
      UPDATE collection SET collection.num_comments = collection.num_comments - 1
       WHERE collection.id = OLD.collection_id;
      UPDATE collectible SET collectible.num_comments = collectible.num_comments - 1
       WHERE collectible.id = OLD.collectible_id;
    END IF;
  END;
//

delimiter ;
