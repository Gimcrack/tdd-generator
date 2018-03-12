export default {

    data() {
        return {
            busy : false,
            //models : [],
            refresh_btn_text : 'Refresh',
            search : null,
            orderBy : 'name',
            asc : true,
            timeouts : {}
        }
    },

    mounted() {
        this.listen();
        //this.fetch();
    },

    computed : {
        filtered() {
            let reject = ( _.isEmpty(this.params.reject) ) ? { placeholder : 'gibberish-value' } : this.params.reject,
                models = _(this.models)
                    .filter( this.searchModel )
                    .filter( this.params.where )
                    .reject( reject )
                    .sortBy(this.orderBy);

            return (this.asc) ? models.value() : models.reverse().value();
        },

        modelType() {
            return this.params.type;
        },

        properType() {
            return this.modelType.$ucfirst();
        }
    },

    methods : {

        selectedIds() {
            return this.toggled.map( o => o.model.id );
        },

        selectedNames() {
            return this.toggled.map( o => o.model.name );
        },

        fetch() {
            if ( !! this.preFetch )
                this.preFetch();

            if ( !! this.timeouts.fetch )
                clearTimeout(this.timeouts.fetch)

            this.timeouts.fetch = setTimeout( this.performFetch, 1000 );
        },

        performFetch() {
            Api.get( this.params.endpoint )
                .then( this.success, this.error )
        },

        success(response) {
            let data_key = this.params.data_key;
            this.models = (!! data_key) ? response.data[data_key] : response.data;

            if ( !! this.postSuccess )
                this.postSuccess();
        },

        done(response) {
            this.busy = false;
        },

        error(error) {
            flash.error('There was an error performing the operation. See the console for more params');
            console.error(error);

            if ( !! this.postError )
                this.postError();
        },

        findModelById(id) {
            return this.models.findIndex( (model) => {
                return model.id === id;
            });
        },

        remove(model) {
            let index = this.findModelById(model.entity.id);
            if ( index > -1 ) this.models.$remove(index);

            flash.warning(`${model.type} Removed: ${model.name}`)
        },

        add( model ) {
            let index = this.findModelById(model.entity.id);

            // if the model exists, replace it
            if ( index > -1 ) {
                // console.log('Updating model');
                return this.models[index] = model.entity;
            }
            else {
                // console.log('New model');
                this.models.push(model.entity);
                flash.success(`New ${model.type.$title_case()}: ${model.name}`);
            }
        },

        model( event ) {
            let entity = event[this.modelType],
                friendly = this.params.model_friendly || 'name';

            return {
                entity,
                type : this.properType,
                name : entity[friendly]
            }
        },

        sortBy(key) {
            if ( key == this.orderBy ) {
                this.asc = ! this.asc;
            }
            this.orderBy = key;
        },

        listen() {
            Echo.channel(this.params.events.channel)
                .listen( this.params.events.created, (event) => {
                    // console.log(event);
                    this.add( this.model(event) );

                    if ( !! this.postCreated )
                        this.postCreated(event);
                })
                .listen( this.params.events.destroyed, (event) => {
                    this.remove( this.model(event) );

                    if ( !! this.postDeleted )
                        this.postDeleted(event);
                });

            let other = this.params.events.other;
            if ( !! other ) {
                for( let type in other ) {
                    Echo.channel(this.params.events.channel)
                        .listen( type, (event) => { other[type](event) } );
                }
            }

            let g = this.params.events.global;
            if ( !! g ) {
                for( let type in g ) {

                    if ( typeof g[type] === 'function' )
                    {
                        Bus.$on(type, (event) => { g[type](event) });
                    }
                    else
                    {
                        Bus.$on(type, (event) => { this[g[type]](event) });
                    }

                }
            }
        },

        searchModel( model ) {
            if ( ! this.search ) return true;

            for ( let prop in model )
            {
                if ( typeof model[prop] === "string" )
                {
                    if ( model[prop].toLowerCase().indexOf( this.search.toLowerCase() ) !== -1 ) return true;
                }
                else
                {
                    if ( this.searchModel( model[prop] ) ) return true;
                }
            }

            return false;
        },

        // override these on the instance if you want to customize the behavior

        preFetch() {
            this.busy = true;
            this.refresh_btn_text = 'Refreshing';
        },

        postSuccess() {
            this.refresh_btn_text = 'Refreshed';

            sleep(1000).then( () => {
                this.refresh_btn_text = 'Refresh';
                this.busy = false;
            })
        },

        postError() {
            this.busy = false;
            this.refresh_btn_text = 'Refresh';
        },
    }
}
