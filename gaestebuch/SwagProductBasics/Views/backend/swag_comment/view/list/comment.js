

Ext.define('Shopware.apps.SwagComment.view.list.Comment', {
    extend: 'Shopware.grid.Panel',
    alias:  'widget.comment-listing-grid',
    region: 'center',

    configure: function() {
        return {
            detailWindow: 'Shopware.apps.SwagComment.view.detail.Window',
            columns: {
                comment: 'Kommentar',
                active: 'Freigegeben'
            }
        };
    }
});
