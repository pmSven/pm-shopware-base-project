
Ext.define('Shopware.apps.PmLogging', {
    extend: 'Enlight.app.SubApplication',

    name:'Shopware.apps.PmLogging',

    loadPath: '{url action=load}',
    bulkLoad: true,

    controllers: [
        'Main'
    ],

    views: [
        'list.Window',
        'list.List',
        'list.extensions.Info',
        'list.extensions.Filter'
    ],

    models: [
        'Main',
        'Event',
        'Type',
        'Entity'
    ],

    stores: [
        'Main',
        'Event',
        'Type',
        'Entity'
    ],

    launch: function() {
        return this.getController('Main').mainWindow;
    }
});