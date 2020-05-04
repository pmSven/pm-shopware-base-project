Ext.define('Shopware.apps.PmLogging.model.Entity', {
    extend: 'Shopware.data.Model',
    fields: [
        { name: 'entity', type: 'string' }
    ],
    configure: function() {
        return {
            controller: 'Entity'
        };
    }
});
