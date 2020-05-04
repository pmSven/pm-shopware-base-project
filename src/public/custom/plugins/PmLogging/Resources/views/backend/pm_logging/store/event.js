Ext.define('Shopware.apps.PmLogging.store.Event', {
    extend: 'Shopware.store.Listing',
    model: 'Shopware.apps.PmLogging.model.Event',
    configure: function() {
        return {
            controller: 'Event'
        };
    }
});
