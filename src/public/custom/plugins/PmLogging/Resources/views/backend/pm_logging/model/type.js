Ext.define('Shopware.apps.PmLogging.model.Type', {
    extend: 'Shopware.data.Model',
    fields: [
        { name: 'type', type: 'string' }
    ],
    configure: function() {
        return {
            controller: 'Type'
        };
    }
});
