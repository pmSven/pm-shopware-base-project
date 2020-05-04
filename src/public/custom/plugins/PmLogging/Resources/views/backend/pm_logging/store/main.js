
Ext.define('Shopware.apps.PmLogging.store.Main', {
    extend:'Shopware.store.Listing',

    configure: function() {
        return {
            controller: 'PmLogging'
        };
    },
    model: 'Shopware.apps.PmLogging.model.Main'
});