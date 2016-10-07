
Ext.define('Shopware.apps.SwagProduct.model.Product', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            controller: 'SwagProduct',
            detail: 'Shopware.apps.SwagProduct.view.detail.Product',
            
        };
    },

    proxy: {
        type: 'ajax',

        /**
         * Configure the url mapping for the different
         * store operations based on
         * @object
         */
        api:{
            create: '{url controller="SwagProduct" action="create"}',
            update: '{url controller="SwagProduct" action="update"}',
            delete: '{url controller="SwagProduct" action="delete"}'
        },

        /**
         * Configure the data reader
         * @object
         */
        reader: {
            type: 'json',
            root: 'data',
            totalProperty: 'total'
        },
        writer:{
            root: 'data'

        }
    },
    /**
     * Contains the model fields
     * @array
     */

    fields: [
        { name : 'id', type: 'int', useNull: true },
        { name : 'username', type: 'string' },
        { name : 'newpassword', type: 'string'},
        { name : 'rights', type: 'string' },
        { name : 'Lastname', type:'string'},
        { name : 'Firstname', type: 'string'},
        { name : 'salutation', type:'string'},
        { name : 'street', type:'string'},
        { name : 'zipcode', type:'string'},
        { name : 'city', type:'string'}

    ]
});

