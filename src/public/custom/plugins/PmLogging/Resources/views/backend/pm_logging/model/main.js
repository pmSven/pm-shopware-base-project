
Ext.define('Shopware.apps.PmLogging.model.Main', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            controller: 'PmLogging'
        };
    },

    fields: [
        { name : 'id', type: 'int', useNull: true },
        { name : 'type', type: 'string', useNull: false },
        { name : 'caller', type: 'string', useNull: false },
        { name : 'referenceId', type: 'int', useNull: true },
        { name : 'entity', type: 'string', useNull: false },
        { name : 'event', type: 'string', useNull: false },
        { name : 'msg', type: 'string', useNull: false },
        { name : 'data', type: 'string', useNull: true },
        { name : 'time', type: 'date', useNull: false }
    ]
});

