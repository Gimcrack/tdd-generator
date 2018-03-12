<template>
    <div class="chats relative" :class="{active:show}">
        <div class="panel panel-primary relative">
            <div class="panel-heading">
                <button @click="toggle" class="btn btn-xs btn-default pull-right">
                    <i class="fa fa-fw" :class="[show ? 'fa-window-minimize' : 'fa-window-maximize']"></i>
                </button>
                <i class="fa fa-fw fa-comment"></i> Chat
            </div>
            <div v-if="busy" class="spinner">
                    <i class="fa fa-fw fa-refresh fa-spin fa-5x"></i>
                </div>
            <div ref="body" class="panel-body">

                <template v-if="models.length">
                    <chat v-for="chat in sorted_models" :key="chat.id" :model="chat"></chat>

                    <div v-if="other_typing_name.length" class="chat-container">
                        <div class="chat">
                            <small>{{ other_typing_name.join(', ') }} typing <br> </small>
                            ...
                        </div>
                        <div  class="spacer"></div>
                    </div>
                </template>
            </div>
            <div class="panel-footer">
                <textarea ref="input" @keyup="type" @keydown.enter.prevent="send" class="form-control" placeholder="Say something intelligent..." v-model="message" rows="3"></textarea>
            </div>
        </div>

        <div class="here panel panel-primary panel-body" v-if="here.length">
            <ul class="fa-ul">
              <li v-for="here_user in here" :key="here_user.id"><i class="fa-li fa fa-circle text-success"></i> {{ (here_user.name != user.name) ? here_user.name : 'Me' }}</li>
            </ul>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash';

    export default {

        props : ['user'],

        mounted() {
            this.listen();
            this.scroll();
        },

        computed : {
            sorted_models() {
                return _(this.models).orderBy(o => o.id).value()
            },
        },

        data() {
            return {
                models : this.getInitialState(),
                busy : false,
                message : '',
                show : Store.$ls.get('viewChat',true),
                other_typing_name : [],
                channel : null,
                here: []
            }
        },

        methods: {
            listen() {
                this.channel = Echo.join('chat');

                this.channel
                    .here( (users) => {
                        this.here = users;
                    })
                    .joining( (user) => {
                        //flash.success(`${user.name} has signed in.`);
                        this.here.push(user);
                    })
                    .leaving( (user) => {
                        //flash.success(`${user.name} has signed out.`);
                        this.here.$remove(user);
                    })
                    .listen('ChatMessageReceived', (event) => {
                        this.models.push(event.chat);

                        if (event.chat.user.id != this.user.id) {
                            if ( ! this.show ) {
                                flash.notify(event.chat.user.name + ' : ' + event.chat.message);
                            }
                        }

                        this.scroll();
                    })
                    .listenForWhisper('typing', (event) => {
                        if ( event.user.id != this.user.id ) {
                            this.other_typing_name.$add(event.user.name.split(' ')[0]);
                            this.scroll();
                        }
                    })
                    .listenForWhisper('done_typing', (event) => {
                        if ( event.user.id != this.user.id ) {
                            this.other_typing_name.$remove(event.user.name.split(' ')[0]);
                        }
                    })
            },

            getInitialState() {
                let key = 'chats',
                    state = INITIAL_STATE[key] || [];

                return state;
            },

            type() {
                this.whisperTyping();
            },

            whisperTyping() {
                let evnt = ( !! this.message.trim().length ) ? 'typing' : 'done_typing';

                if ( this.message.trim().length > 3) return false;

                this.channel.whisper(evnt, {user : this.user});
            },

            scroll() {
                sleep(100).then( () => {
                    this.$refs.body.scrollTop = this.$refs.body.scrollHeight;
                } )
            },

            fetch() {
                this.busy = true;

                Api.get( 'chat' )
                    .then( this.success, this.error )
            },

            send() {
                if ( ! this.message.trim() ) return false;

                let message = this.message;

                this.busy = true;
                this.message = '';
                this.whisperTyping();

                Api.post( 'chat', { message })
                    .then( this.sendSuccess, (error) => this.error(error, message) )
            },

            success({data}) {
                this.busy = false;
                sleep(3000).then( () => {
                    this.$refs.input.focus();
                });
                this.models = data;
                this.scroll();


            },

            sendSuccess() {
                this.busy = false;
            },

            error(error, message) {
                this.message = message;
                this.whisperTyping();
                console.error(error);
            },

            toggle() {
                this.show = ! this.show;
                Store.$ls.set('viewChat',this.show);
            }
        }
    }
</script>

<style lang="scss">
    .chats {
        position: fixed;

        height: 45px;
        width: 150px;
        bottom: 15px;
        left: 15px;
        display: flex;

        .here {
            display: none;
        }

        &.active {
            height: 300px;
            width: 300px;

            .panel-body {
                display: block;
            }

            .panel-footer {
                display: block;
            }

            .here {
                display: block;
                position: absolute;
                font-size: 10px;
                font-weight: bold;
                left: 0px;
                bottom: 315px;
                height: auto;
                width: 100%;
                margin: 0;
                padding: 0.5em;

                ul {
                    margin: 0 0 0 16px;
                }
            }
        }

        .panel {
            height: 100%;
            width: 100%;
            border-radius: 0;
            box-shadow: 3px 3px 6px rgba(0,0,0,0.4);
            display: flex;
            flex-direction: column;
        }

        .panel-heading {
            border-radius: 0;
        }

        .panel-body {
            display: none;
            flex: 1;
            background: lighten(#3097D1, 45%);
            overflow: auto;
        }

        .panel-footer {
            display: none;
            padding: 0;

            textarea {
                margin: 0px;
                width: 100%;
                border: none;
                height: 78px;
            }
        }

        .spinner {
            position: absolute;

            left: 0;
            top: 43px;
            right: 0;
            height: 176px;
            z-index: 10;
            background: rgba(0,0,0,0.1);
            color: rgba(0,0,0,0.2);
            display: flex;
            justify-content: center;
            align-items: center;
        }
    }
</style>
