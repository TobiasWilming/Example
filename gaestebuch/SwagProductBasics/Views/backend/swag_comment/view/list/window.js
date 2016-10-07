
Ext.define('Shopware.apps.SwagComment.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.comment-list-window',
    height: 450,
    title : 'Guestboookentries',

    configure: function() {
        return {
            listingGrid: 'Shopware.apps.SwagComment.view.list.Comment',
            listingStore: 'Shopware.apps.SwagComment.store.Comment'
        };
    }
});