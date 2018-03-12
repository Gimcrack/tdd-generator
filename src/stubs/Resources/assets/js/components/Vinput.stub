<template>
    <div class="vinput">
        <input :value="value" @keyup.enter="$emit('keyupEnter')" @input="$emit('input', $event.target.value)" type="text" class="form-control vinput__input" :placeholder="placeholder" />
        <div class="vinput__icon">
            <i class="fa fa-fw fa-2x" :class="this.icon"></i>
        </div>
        <button @click.prevent.stop="reset" v-show="value" class="vinput__reset btn btn-sm btn-danger btn-outline">
            <i class="fa fa-fw fa-times"></i>
        </button>    
    </div>
    
</template>

<script>
    export default {
        props : [
            'placeholder',
            'icon',
            'value',
            'small',
            'square'
        ],

        methods : {
            reset() {
                this.$el.querySelector('input').value = '';
                this.$emit('input','');
                this.$emit('clear');
            }
        }
    }
</script>

<style lang="less">
    .vinput {
        position: relative;
        display: flex;
        flex: 1;

        .vinput__input {
            text-indent: 34px;
            font-size: 20px;
            // height:40px; 
            // border-style: solid;
            // border: 1px solid #ccd0d2;
            // //border-bottom:2px solid #CCC;
            // background-color: lighten(#F5F8FA,1%);
        }

        .vinput__icon {
            position: absolute;
            top: 4px;
            left: 0px;
            color: #ccc;
            font-size: inherit;
        }

        .vinput__reset {
            position: absolute;
            top: 3px;
            right: 3px;
            z-index: 2;
            font-size: 12px;
        }
    }
</style>