<template>
    <page
        :params="details"
        :toggles="toggles"
        @new="create"
        @created="created"
        @deleted="deleted"
    ></page>
</template>

<script>
    export default {
        data() {
            return {
                toggles : {
                    new : true
                },

                details : {
                    columns : [
                        'id',
                        'name',
                        'email',
                        {
                            title : 'User Type',
                            key : 'admin_flag',
                        }
                    ],
                    type : 'user',
                    heading : 'Users',
                    endpoint : 'users',
                    help : 'System Users',
                    events : {
                        channel : 'users',
                        created : 'UserWasCreated',
                        destroyed : 'UserWasDestroyed',
                    }
                },

                tempUser : {
                    name : null,
                    email : null,
                    password : null
                }
            }
        },

        methods : {

            create() {
                return swal({
                    title: "New User",
                    text : "What is the user's name?",
                    inputPlaceholder: "John Doe",
                    input: "text",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    animation: "slide-from-top",
                })
                .then( this.getEmail, this.page.ignore );
            },

            getEmail(name) {
                this.tempUser.name = name;

                return swal({
                    title: "New User",
                    text : "What is the user's email?",
                    inputPlaceholder: "john.doe@example.com",
                    input: "text",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    animation: "slide-from-top",
                })
                .then( this.getPassword, this.page.ignore );
            },

            getPassword(email) {
                this.tempUser.email = email;

                return swal({
                    title: "New User",
                    text : "What is the user's password?",
                    inputPlaceholder: "********",
                    input: "password",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    animation: "slide-from-top",
                })
                .then( this.store, this.page.ignore );
            },

            store(password) {
                this.tempUser.password = password;

                this.page.busy = true;
                Api.post('users', this.tempUser)
                    .then(this.created, this.page.error);
            },

            created(event) {
                this.tempUser = {
                    name : null,
                    email : null,
                    password : null
                }

                this.page.busy = false;
            },

            deleted(event) {
            },
        },
    }
</script>