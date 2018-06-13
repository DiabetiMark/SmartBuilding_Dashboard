<template>
    <div>
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
                <li><a href="/">Home</a></li>
                <li class="is-active"><a href="#" aria-current="page">Gebruikers</a></li>
            </ul>
        </nav>
        <hr>

        <div class="columns is-gapless">
            <div class="column">
                <h4 class="title is-4">Gebruiker Toevoegen</h4>
                <hr>
                <form @submit.prevent="createUser" @keydown="addUser.errors.clear($event.target.name)">
                    <div class="field">
                        <label class="label">Naam</label>
                        <div class="control has-icons-left">
                            <input style="width:75%;" class="input" name="naam" type="text" placeholder="Naam" v-model='user.data.name' required>
                            <span class="icon is-small is-left">
                              <i class="fas fa-user"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Gebruikersnaam</label>
                        <div class="control has-icons-left">
                            <input style="width:75%;" class="input" name="gebruikersnaam" type="text" placeholder="Gebruikersnaam" v-model='user.data.username' required>
                            <span class="icon is-small is-left">
                              <i class="fas fa-user"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Gebruikersnaam</label>
                        <div class="control has-icons-left">
                            <input style="width:75%;" class="input" name="email" type="email" placeholder="Email" v-model='user.data.email' required>
                            <span class="icon is-small is-left">
                              <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Rol</label>
                        <div class="control">
                            <div class="select">
                                <select v-model='user.data.role_id'>
                                    <option value="" selected disabled hidden>Kies een rol</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Ruimte(s) Selecteren</label>
                        <div class="control">
                            <div class="select" @click="showCheckboxes">
                                <select v-model='addModule.data.room_id' v-if="rooms.length > 0">
                                    <option value="" selected disabled hidden>Kies een ruimte</option>
                                </select>
                                <div class="overSelect"></div>
                            </div>
                            <div id="checkboxes">
                                <label :for="room.id" v-for="room in this.rooms">
                                    <input type="checkbox" :id="room.id" v-model="addUser.data.rooms" :value="room.id"/>{{room.roomName}}
                                </label>
                            </div>
                        </div>
                    </div>
                    <input class="button is-info" value="Toevoegen" type="submit">
                </form>
            </div>
            <div class="column">
                <h4 class="title is-4">Ruimte Toevoegen Aan Gebruiker</h4>
                <hr>
                <div class="field">
                    <label class="label">Gebruiker</label>
                    <div class="control has-icons-left">
                        <div class="select">
                            <select v-model='user_id' @change="userChanged" v-if="users">
                                <option v-for="user in users" :value="user.id" >{{user.username}}</option>
                            </select>
                            <span class="icon is-small is-left">
                              <i class="fas fa-user"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <template v-if="usersInfo && usersInfo.rooms.length > 0">
                    <div class="field">
                        <label class="label">Kamer</label>
                        <div class="control has-icons-left">
                            <div class="select">
                                <select v-model='add_room_id' v-if="rooms.length > 0">
                                    <option v-for="(room, key) in rooms" :value="key" v-if="notYetAdded(room.id)">{{room.roomName}}</option>
                                </select>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-home"></i>
                                </span>
                            </div>
                            <p class="help" v-if="rooms"><strong>Beschrijving:</strong> {{ rooms[add_room_id].roomDescription }}</p>
                        </div>
                    </div>

                    <template v-if="usersInfo.rooms[room_id].sensor_modules.length > 0">
                        <div class="field">
                            <label class="label">Sensormodule</label>
                            <div class="control has-icons-left">
                                <div class="select">
                                    <select v-model='sensor_modules_id' @change="sensor_moduleChanged">
                                        <option v-for="(sensor_module, key) in usersInfo.rooms[room_id].sensor_modules" :value="key" >{{sensor_module.moduleName}}</option>
                                    </select>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-microchip"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <p class="help is-danger">Er zijn geen modules beschikbaar voor deze module.</p>
                    </template>
                </template>
                <template v-else>
                    <p class="help is-danger">Er zijn geen ruimtes beschikbaar voor deze gebruiker.</p>
                </template>
                <template v-if="!noRoomsToAdd">
                    <button class="button is-info" @click="AddRoom">Voeg kamer toe</button>
                </template>
                <template v-else>
                    <p>Voor deze gebruiker zijn er geen kamers meer om toe tevoegen.</p>
                </template>
            </div>
        </div>

    </div>
</template>
<script>
    import axios from 'axios';

    export default {
        props: ['admin'],

        data(){
            return{
                addModule: {
                    id: '',
                    data: {
                        room_id: '',
                        user_id: '',
                    },
                    errors: new Errors(),
                },
                addUser: {
                    data: {
                        username: '',
                        email: '',
                        name: '',
                        role: '',
                        rooms: [

                        ],
                    },
                    errors: new Errors(),
                },
                users: false,
                usersInfo: false,
                user_id: false,
                room_id: false,
                sensor_modules_id: 0,
                sensor_id: 0,
                changeAddRoom: false,
                add_room_id: false,
                rooms: false,
                newRoomLink: {
                    data: {
                        room_id: '',
                        user_id: '',
                    },
                    errors: new Errors(),
                },
                addRoomToUser: false,
                noRoomsToAdd: true,
                expanded: false,
                modules: false,
            }
        },

        mounted() {
        },

        created(){
            this.getData();
            this.getModules();
        },

        methods: {
            getModules(){
                axios.get('/api/sensormodule')
                    .then(response => {
                        this.modules = response.data;
                    })
                    .catch(error => {

                    })
            },
            addModules(){

            },
            showCheckboxes(){
                var checkboxes = document.getElementById('checkboxes');
                if(!this.expanded){
                    checkboxes.style.display = "block";
                } else {
                    checkboxes.style.display = "none";
                }
                this.expanded = !this.expanded;
            },
            createUser(){
                axios.post('/api/user', this.addUser.data)
                .then(response => {
                    this.users = response.data;
                    this.user_id = response.data[0].id;
                    this.userChanged();
                }).catch(error => {
                    this.addUser.errors.record(error.response.data.errors)
                });
            },
            changeAdd(index){
                for(var i = 0; i < Object.keys(this.open).length; i++){
                    if(i == index){
                        Vue.set(this.open, i, !this.open[i]);
                    } else {
                        Vue.set(this.open, i, false);
                    }
                }
            },
            getData(){
                axios.get('/api/user')
                    .then(response => {
                        this.users = response.data;
                        this.user_id = response.data[0].id;
                        this.userChanged();
                    }).catch(e => {
                    console.log(e);
                });
            },
            getRooms(){
                axios.get('/api/room')
                    .then(response => {
                        this.rooms = response.data;
                        this.checkRooms();

                    }).catch(e => {
                    console.log(e);
                });
            },
            createRoom(){
                axios.post('/api/room', this.addRoom.data)
                    .then(response => {
                        this.rooms = response.data;
                        this.checkRooms();
                    }).catch(error => {
                    this.addRoom.errors.record(error.response.data.errors)
                });
            },
            createModule(){
                this.addModule.data.user_id = this.user_id;
                axios.put('/api/sensormodule/' + this.addModule.id, this.addModule.data)
                    .then(response => {
                        this.usersInfo = response.data.allValues;
                        this.modules = response.data.modules;
                        this.room_id = 0;
                        this.checkRooms();
                    }).catch(error => {
                    this.addModule.errors.record(error.response.data.errors)
                });
            },
            checkRooms(){
                var foundID = false;
                this.noRoomsToAdd = false;
                for(var i=0; i < this.rooms.length; i++){
                    foundID = false;
                    for(var j=0; j < this.usersInfo.rooms.length && !foundID; j++){
                        if( this.rooms[i].id == this.usersInfo.rooms[j].id){
                            foundID = true;
                        }
                    }
                    if(!foundID){
                        this.add_room_id = i;
                        return;
                    }
                }
                this.noRoomsToAdd = true;
            },
            userChanged(){
                axios.get('api/user/getAll')
                    .then(response => {
                        this.usersInfo = response.data;
                        this.user_id = 0;
                        this.room_id = 0;
                        this.checkRooms();

                        if(!this.rooms){
                            this.getRooms();
                        }
                    }).catch(e => {
                    console.log(e);
                });
            },
            AddRoom(){
                this.newRoomLink.data.user_id = this.user_id;
                this.newRoomLink.data.room_id = this.rooms[this.add_room_id].id;

                axios.post('api/room_user', this.newRoomLink.data)
                    .then(response => {
                        this.usersInfo = response.data;
                        this.checkRooms();
                    }).catch(error => {
                    this.newRoomLink.errors.record(error.response.data.errors)
                });
            },
            roomChanged(){
                this.sensor_modules_id = 0;
            },
            sensor_moduleChanged(){
            },
            notYetAdded(id){
                for(var i=0; i < this.usersInfo.rooms.length; i++){
                    if( this.usersInfo.rooms[i].id == id){
                        return false
                    }
                }
                return true
            },
            allAdded(){
                for(var i = 0; i < this.modules.length; i++){
                    if(this.modules[i].room_id == null){
                        return false
                    }
                }
                return true
            }
        }
    }
</script>
