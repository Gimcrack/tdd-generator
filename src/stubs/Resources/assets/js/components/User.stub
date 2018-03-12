<template>
    <item 
        :id="model.id"
        :deleting="deleting"
        :updating="updating"
        :toggles="toggles"
        @view="view"
        @update="update"
        @destroy="destroy"
    >
        <td>{{ model.name }}</td>
        <td>{{ model.email }}</td>
        <td><span class="label" :class="[ model.admin_flag ? 'label-primary' : 'label-success']" v-text="model.admin_flag ? 'Admin' : 'User'"></span></td>

        <template slot="menu">
            <button v-show="! model.admin_flag" @click.prevent="toggleAdmin" :disabled="busy" class="btn btn-success btn-xs btn-outline" :class="{disabled : busy}"> 
                <i :class="[ model.admin_flag ? 'fa-arrow-down' : 'fa-arrow-up', {'fa-spin' : updating}]" class="fa fa-fw"></i> 
            </button>
        </template>
    </item>
</template>

<script>
    export default {
        mixins : [
            mixins.item
        ],

        computed : {
            updated() {
                return fromNow(this.model.updated_at);
            }
        },

        data() {
            return {
                item : {
                    key : 'id',
                    type : 'user',
                    endpoint : 'users',
                    channel : `users.${this.initial.id}`,
                    updated : 'UserWasUpdated',
                },

                toggles : {
                    update : false,
                    delete : ! this.initial.admin_flag,
                }
            }
        },

        methods : {
            postUpdated(event) {
                this.updating = false;
            },

            update() {

            },

            toggleAdmin() {
                if ( this.model.admin_flag ) 
                    return this.unpromote();

                return this.promote(); 
            },

            unpromote() {
                this.updating = true;

                Api.post(`users/${this.initial.id}/unpromote`)
                    .then(this.updateSuccess, this.error)
            },

            promote() {
                this.updating = true;

                Api.post(`users/${this.initial.id}/promote`)
                    .then(this.updateSuccess, this.error)
            },
        }
    }
</script>