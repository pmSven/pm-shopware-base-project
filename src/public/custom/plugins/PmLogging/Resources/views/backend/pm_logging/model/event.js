Ext.define('Shopware.apps.PmLogging.model.Event', {
    extend: 'Shopware.data.Model',
    fields: [
        { name: 'event', type: 'string' }
    ],
    configure: function() {
        return {
            controller: 'Event'
        };
    }
});
