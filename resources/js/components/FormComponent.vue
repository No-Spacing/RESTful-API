
<template>
<div class="d-flex justify-center py-2 my-2">
    <v-card title="User" subtitle="User input" width="1080" :loading="loading">
        <v-container fluid>
            <v-card-text>
                <v-container fluid>
                    <v-form ref="refForm" @submit.prevent="create">
                        <v-text-field
                            v-model="form.name"
                            :error-messages="errorMessage.name"
                            label="Enter Name"
                            prepend-inner-icon="mdi-account-outline"
                            variant="outlined"
                            class="ma-1 pa-1"
                        ></v-text-field>

                        <v-text-field
                            v-model="form.email"
                            :error-messages="errorMessage.email"
                            label="Enter Email"
                            prepend-inner-icon="mdi-email-outline"
                            variant="outlined"
                            class="ma-1 pa-1"
                        ></v-text-field>

                        <v-text-field 
                            v-model="form.bdate"
                            :error-messages="errorMessage.bdate" 
                            type="date" 
                            label="Birthdate" 
                            prepend-inner-icon="mdi-calendar-outline"
                            variant="outlined"
                            class="ma-1 pa-1"
                        ></v-text-field>
                        <v-divider></v-divider>
                        <v-card-actions class="d-flex pt-5">
                            <v-btn 
                            block
                            type="submit" 
                            :loading="loading" 
                            variant="outlined">
                            Submit
                            </v-btn>
                        </v-card-actions>
                    </v-form>
                </v-container>
            </v-card-text>  
        </v-container>
    </v-card>
</div>
<div class="d-flex justify-center">
    <v-card title="Users" subtitle="List of users" width="1080">
        <v-container>
            <v-card-text>
                <v-text-field
                    v-model="search"
                    label="Search"
                    single-line
                    hide-details
                    class="pb-5"
                ></v-text-field>
                <v-table>
                    <thead>
                        <tr>
                            <th class="text-left">Name</th>
                            <th class="text-left">Email</th>
                            <th class="text-left">Birthdate</th>
                            <th class="text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in users.data" :key="item.id">
                            <td>{{ item.name }}</td>
                            <td>{{ item.email }}</td>
                            <td>{{ item.bdate }}</td>
                            <td>
                                <v-btn 
                                    icon="mdi-pencil" 
                                    color="yellow" 
                                    class="ma-2 pa-2"
                                    size="x-small"
                                    @click="editUser(item)"
                                ></v-btn>
                                <v-btn 
                                    icon="mdi-delete" 
                                    color="red" 
                                    class="ma-2 pa-2"
                                    size="x-small"
                                    @click="deleteUser(item.id)"
                                ></v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
                <v-pagination
                    v-model="users.current_page" 
                    :length="users.last_page" 
                    @update:modelValue="changePage()"
                ></v-pagination>
                <v-dialog v-model="dialog" width="520px">
                    <v-card>
                        <v-container fluid>
                                <v-card-title>
                                    Edit User
                                </v-card-title>
                                <v-card-subtitle>
                                    Edit user data
                                </v-card-subtitle>
                                <v-form @submit.prevent="update">
                                    <v-card-text>   
                                            <v-text-field 
                                                v-model="form.name" 
                                                :error-messages="errorDialogMessage.name"
                                                label="Name"
                                                variant="outlined"
                                            ></v-text-field>
                                            <v-text-field
                                                class="my-2 py-2"
                                                v-model="form.email"
                                                :error-messages="errorDialogMessage.email" 
                                                label="Email"
                                                variant="outlined"
                                            ></v-text-field>
                                            <v-text-field 
                                                v-model="form.bdate"
                                                :error-messages="errorDialogMessage.bdate"
                                                type="date" 
                                                label="Birthdate" 
                                                prepend-inner-icon="mdi-calendar-outline"
                                                variant="outlined"
                                            ></v-text-field>
                                    </v-card-text>
                                    <v-divider></v-divider>
                                    <v-card-actions class="justify-end pt-5">
                                        <v-btn variant="outlined" @click="dialog = false">Close</v-btn>
                                        <v-btn color="primary" variant="outlined" type="submit">Update</v-btn>
                                    </v-card-actions>
                                </v-form>
                        </v-container>
                    </v-card>
                </v-dialog>
            </v-card-text>  
        </v-container>
    </v-card>
</div>
<v-snackbar 
v-model="snackbar" 
color="success" 
location="top"
vertical
>
    <div class="text-subtitle-1 pb-2">Message</div>
    <p>{{ text }}</p>
</v-snackbar>


</template>

<script>
export default {
    created () {
        this.getUsers();
    },
    data() {
        return {
            dialog: false,
            snackbar: false,
            loading: false,
            search: '',
            users: [],
            errorMessage: [],
            errorDialogMessage: [],
            form: ({
                name: '',
                email: '',
                bdate: '',
            }),
            errorMessage: [],
        };
    },

    methods: {
        create(){
            this.loading = true
            axios.post('/api/create', this.form).then( (response) => {
                this.loading = false;
                this.text = response.statusText;
                this.snackbar = true;
                this.$refs.refForm.reset();
                this.getUsers();
            }).catch(error => {
                this.errorMessage = error.response.data;
                this.loading = false;
            });
        },

        getUsers () {
            axios.get('/api/users?page=' + this.users.current_page)
            .then((response) => {
                this.users = response.data;
            })
            .catch(
                error => console.log(error)
            )
        },

        changePage() {
            axios.get(this.users.links[this.users.current_page].url)
            .then((response) => {
                this.users = response.data;
            })
            .catch(
                error => console.log(error)
            )
        },

        searchUsers () {
            axios.get('/api/users',{
                params: {
                    search: this.search
                }
            })
            .then((response) => {
                this.users = response.data
            })
            .catch(error => {
                console.log(error)
            });
        },

        editUser(data) {
            this.form = Object.assign({}, data);
            this.dialog = true;
        },

        update(){
            axios.put('/api/update', this.form).then( (response) => {
                this.text = response.statusText;
                this.snackbar = true;
            }).catch(error => {
                this.errorDialogMessage = error.response.data.errors;
            })
            this.getUsers();
        },

        deleteUser(id) { 
            axios.delete('/api/delete/' + id).then((response) => {
                this.text ="User Deleted"
                this.snackbar = true;
                this.getUsers();
                console.log(response);
            });
        },
    },

    watch: {
        search(after, before) {
            this.searchUsers();
        }
    },

}
</script>

