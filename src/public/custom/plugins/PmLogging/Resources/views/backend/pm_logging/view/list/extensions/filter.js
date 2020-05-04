Ext.define('Shopware.apps.PmLogging.view.list.extensions.Filter', {
    extend: 'Shopware.listing.FilterPanel',
    alias:  'widget.product-listing-filter-panel',
    width: 270,
    collapsible: true,
    collapsed: true,

    configure: function() {
        return {
            controller: 'PmLogging',
            model: 'Shopware.apps.PmLogging.model.Main',
            fields: {
                event: {
                    xtype: 'combobox',
                    displayField: 'event',
                    valueField: 'event',
                    store: Ext.create('Shopware.apps.PmLogging.store.Event'),
                    expression: '=',
                    fieldLabel: '{s name="column/event"}Event:{/s}'
                },
                entity: {
                    xtype: 'combobox',
                    displayField: 'entity',
                    valueField: 'entity',
                    store: Ext.create('Shopware.apps.PmLogging.store.Entity'),
                    expression: '=',
                    fieldLabel: '{s name="column/entity"}Entity:{/s}'
                },
                type: {
                    xtype: 'combobox',
                    displayField: 'type',
                    valueField: 'type',
                    store: Ext.create('Shopware.apps.PmLogging.store.Type'),
                    expression: '=',
                    fieldLabel: '{s name="column/type"}Type:{/s}'
                },
                time: this.createTimeStampFrom
            }
        };
    },

    createTimeStampFrom: function(_, formField) {
        formField.fieldLabel =  '{s name="column/time_from"}Aufgetreten seit:{/s}';
        formField.expression = '>=';
        return formField;
    },
});