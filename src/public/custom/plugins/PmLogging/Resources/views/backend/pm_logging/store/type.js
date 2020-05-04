Ext.define('Shopware.apps.PmLogging.store.Type', {
    extend: 'Shopware.store.Listing',
    model: 'Shopware.apps.PmLogging.model.Type',
    configure: function() {
        return {
            controller: 'Type'
        };
    }
});
