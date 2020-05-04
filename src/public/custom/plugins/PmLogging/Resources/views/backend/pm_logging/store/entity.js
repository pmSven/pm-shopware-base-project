Ext.define('Shopware.apps.PmLogging.store.Entity', {
    extend: 'Shopware.store.Listing',
    model: 'Shopware.apps.PmLogging.model.Entity',
    configure: function() {
        return {
            controller: 'Entity'
        };
    }
});
