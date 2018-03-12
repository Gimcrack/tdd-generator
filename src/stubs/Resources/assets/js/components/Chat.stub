<template>
    <div class="chat-container">
        <div v-if="mine" class="spacer"></div>
        <div class="chat" :class="{mine}">
            <small v-if="!mine">{{ name }} <br> </small>
            {{ model.message }}
        </div>
        <div v-if="! mine" class="spacer"></div>
    </div>
</template>

<script>
    export default {
        props : [
            'model'
        ],

        computed : {
            mine() {
                return this.model.user_id == this.$parent.user.id;
            },

            name() {
                return this.model.user.name.split(' ')[0];
            }
        }
    }
</script>

<style lang="less">
    .chat-container {
        display: flex;
        width: 100%;
        margin-bottom: 1.3em;

        .spacer {
            width: 50px;
        }

        .chat {
            width: 250px;
            padding: 6px;
            border-radius: 8px;
            font-size: 10px;
            background: darken(#3097D1, 10%);
            color: white;
            box-shadow: 1px 1px 5px rgba(0,0,0,0.3);

            small {
                font-size: 8px;
                font-style: italic;
            }

            &.mine {
                color: black;
                background: white;
                text-align: right;
            }
        }
    }
</style>