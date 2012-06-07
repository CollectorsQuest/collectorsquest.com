
Ext.onReady(function()
{
  store = new Array();
  combo = new Array();

  store['collections'] = new Ext.data.JsonStore({
    url: '/ajax/json/collections-list.html',
    root: 'collections',
    fields: [{name: 'id', type: 'int'}, {name: 'collector_id', type: 'int'}, 'name']
  });

  store['collection-items'] = new Ext.data.JsonStore({
    url: '/ajax/json/collection-items-list.html',
    root: 'collection-items',
    fields: [{name: 'id', type: 'int'}, {name: 'collector_id', type: 'int'}, {name: 'collection_id', type: 'int'}, 'name']
  });

  combo['collection'] = new Ext.form.ComboBox({
    store: store['collections'],
    id: 'combo-collection',
    displayField: 'name',
    valueField: 'id',
    typeAhead: true,
    forceSelection: true,
    triggerAction: 'all',
    allowBlank: false,
    selectOnFocus: true,
    minChars: 2,
    applyTo: 'collection',
    listeners: {select:{fn:function(c, v)
      {
        id = c.getValue();

        if (typeof(store['collection-items']) != 'undefined' && typeof(combo['collection-item']) != 'undefined')
        {
          store['collection-items'].baseParams = {collection_id: id};
          store['collection-items'].reload();
          combo['collection-item'].enable();
          combo['collection-item'].clearValue();
        }
      }
    }}
  });

  combo['collection-item'] = new Ext.form.ComboBox({
    store: store['collection-items'],
    id: 'combo-collection-item',
    displayField: 'name',
    valueField: 'id',
    typeAhead: true,
    forceSelection: true,
    triggerAction: 'all',
    allowBlank: false,
    selectOnFocus: true,
    minChars: 2,
    disabled: true,
    applyTo: 'collection_item',
    listeners: {select:{fn:function(c, v)
      {
        id = c.getValue();

        new Ajax.Updater('collection_item_preview', '/cqAjax/collectionItemImage.html', {
          parameters: { id: id }
        });
      }
    }}
  });
});
