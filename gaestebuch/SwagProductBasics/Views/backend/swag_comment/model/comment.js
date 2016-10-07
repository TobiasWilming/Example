
Ext.define('Shopware.apps.SwagComment.model.Comment', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            controller: 'SwagComment',
            detail: 'Shopware.apps.SwagComment.view.detail.Comment',
      };
    },

    proxy:{
        type:'ajax',
        /**
         * Configure the url mapping for the different
         * store operations based on
         * @object
         */
        api:{
            delete: '{url action="delete"}',
            update: '{url action="update"}',
            create: '{url action="create"}'

        },

        /**
         * Configure the data reader
         * @object
         */
        reader: {
            type: 'json',
            root: 'data',
            totalProperty: 'total'
        }

    },

    /**
     * Contains the model fields
     * @array
     */

    fields: [
        { name : 'id', type: 'int', useNull: true },
        { name : 'comment', type: 'string' },
        { name : 'active', type: 'boolean'}

    ]
});

