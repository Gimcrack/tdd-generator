<template>
    <th v-if="column != '__blank__'">
        {{ column_title }}
        <button :class="{active, 'btn-outline' : ! active}" class="btn btn-xs btn-primary " @click="$parent.sortBy(column_key)"> 
            <i class="fa fa-fw" :class="active_asc ? 'fa-sort-amount-asc' : 'fa-sort-amount-desc' "></i> 
        </button>
    </th>

    <th v-else>
        
    </th>
</template>

<script>
    export default {
        props : [
            'column',
            'asc',
            'orderBy'
        ],

        computed : {
            column_title() {
                if ( !! this.column.title )
                    return this.column.title;

                return this.column.$title_case();
            },

            column_key() {
                if ( !! this.column.key )
                    return this.column.key;

                return this.column;
            },

            active() {
                return this.orderBy == this.column_key;
            },

            active_asc() {
                return this.asc && this.active;
            }
        }
    }
</script>

<style lang="less">

</style>