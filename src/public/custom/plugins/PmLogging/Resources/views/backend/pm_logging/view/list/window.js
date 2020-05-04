
Ext.define('Shopware.apps.PmLogging.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.pm-logging-list-window',
    height: 750,
    width: 1700,
    title : '{s name=window_title}Log Übersicht{/s}',

    configure: function() {
        return {
            listingGrid: 'Shopware.apps.PmLogging.view.list.List',
            listingStore: 'Shopware.apps.PmLogging.store.Main',
            extensions: [
                { xtype: 'product-listing-info-panel' },
                { xtype: 'product-listing-filter-panel' }
            ]
        };
    }
});