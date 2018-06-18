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
                <form @submit.prevent="createUser" @keydown="user.errors.clear($event.target.name)">
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
                                    <option value="" selected="selected" disabled hidden>Kies een rol</option>
                                    <option v-for="role in roles" :value="role.id">{{role.role}}</option>
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
                                <label :for="room.id" v-for="room in rooms">
                                    <input type="checkbox" :id="room.id" v-model="user.data.rooms" :value="room.id"/>{{room.roomName}}
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
                <template v-if="rooms && users">
                    <div class="field">
                        <label class="label">Gebruiker</label>
                        <div class="control has-icons-left">
                            <div class="select">
                                <select v-model='user.index' @change="checkRooms" v-if="users">
                                    <option v-for="(user, key) in users" :value="key" >{{user.username}}</option>
                                </select>
                                <span class="icon is-small is-left">
                                <i class="fas fa-user"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <template v-if="!noRoomsToAdd && users && rooms.length > 0">
                        <div class="field">
                            <label class="label">Kamer</label>
                            <div class="control has-icons-left">
                                <div class="select">
                                    <select v-model='room.index' v-if="rooms.length > 0" @change="changedRoom">
                                        <option v-for="(room, key) in rooms" :value="key" v-if="notYetAdded(room.id)">{{room.roomName}}</option>
                                    </select>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-home"></i>
                                    </span>
                                </div>
                                <p class="help" v-if="rooms"><strong>Beschrijving:</strong> {{ rooms[room.index].roomDescription }}</p>
                            </div>
                        </div>

                        <template v-if="checkIfSensorModule">
                            <div class="field">
                                <label class="label">Sensormodule</label>
                                <div class="control has-icons-left">
                                    <div class="select">
                                        <select v-model='sensormodule.index'>
                                            <option v-for="(sensorModule, key) in sensorModules" v-bind:key="key" :value="key" v-if="sensorModule.room_id == rooms[room.index].id">{{sensorModule.moduleName}}</option>
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
                    <template v-if="!noRoomsToAdd">
                        <button class="button is-info" @click="AddRoom">Voeg kamer toe</button>
                    </template>
                    <template v-else>
                        <p class="help is-danger">Voor deze gebruiker zijn er geen kamers meer om toe tevoegen.</p>
                    </template>
                </template>
                <template v-else>
                    Laden
                </template>
            </div>
        </div>

    </div>
</template>
<style> 
    .multiselect { 
        width: 200px; 
    } 
    .selectBox { 
        position: relative; 
    } 
    .selectBox select{ 
        width: 100%; 
    } 
    .overSelect { 
        position: absolute; 
        left: 0; 
        right: 0; 
        top: 0; 
        bottom: 0; 
    } 
    #checkboxes{ 
        display: none; 
        border: 1px #dadada solid; 
    } 
    #checkboxes label{ 
        display: block; 
    } 
    #checkboxes label:hover { 
        background-color: #1e90ff; 
    } 
</style> 
<script>

    let promises = [];

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
                user: {
                    data: {
                        username: '',
                        email: '',
                        name: '',
                        role_id: '1',
                        rooms: [

                        ],
                    },
                    index: 0,
                    errors: new Errors(),
                },
                room: {
                    index: '',
                },
                sensormodule:{
                    index: '',
                },
                users: false,
                rooms: false,
                roles: false,
                roomUser: false,
                sensorModules: false,
                sensors: false,
                noRoomsToAdd: false,
                checkIfSensorModule: false,
                newRoomLink: {
                    data: {
                        room_id: '',
                        user_id: '',
                    },
                    errors: new Errors(),
                },
                roomUser: '',
            }
        },

        mounted() {

        },

        created(){
            this.getData();
            this.getRoles();
        },

        methods: {
            changedRoom(){
                this.sensormodule.index = this.checkIfSensorModuleIsYeah();
            },
            checkIfSensorModuleIsYeah(){
                for(let idx = 0 ; idx < this.sensorModules.length; idx++){
                    if(this.sensorModules[idx].room_id == this.rooms[this.room.index].id){
                        this.checkIfSensorModule = true;
                        
                        return idx;
                    }
                }
                this.checkIfSensorModule = false;
            },
            getData(){
                promises.push(axios.get('/api/room'));
                promises.push(axios.get('/api/user'));
                promises.push(axios.get('/api/sensormodule'));
                promises.push(axios.get('/api/sensor'));
                axios.all(promises)
                .then(response => {
                    this.rooms = response[0].data;
                    this.users = response[1].data.users;
                    this.roomUser = response[1].data.room_user;
                    this.sensorModules = response[2].data;
                    this.sensors = response[3].data;

                    this.user.index = 0;

                    for(let index = 0; index < this.rooms.length; index++){
                        if(this.notYetAdded(this.rooms[index].id)){
                            this.room.index = index;
                        }
                    }
                    this.checkRooms();
                    this.changedRoom();
                    this.checkIfSensorModuleIsYeah();
                })
                .catch(error => {
                    console.log(error);
                    
                })
            },
            getRoles(){
                axios.get('/api/role')
                .then(response => {
                    this.roles = response.data;
                })
                .catch(error => {
                    console.log(error);
                    
                })
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
                axios.post('/api/user', this.user.data)
                .then(response => {
                    this.users = response.data.users;
                    this.roomUser = response.data.room_user;
                    this.user_id = response.data[0].id;
                    this.checkRooms();
                }).catch(error => {
                    this.user.errors.record(error.response.data.errors)
                });
            },
            checkRooms(){
                let foundID = false;
                this.noRoomsToAdd = false;
                let rooms = [];
                for(let idx = 0; idx < this.roomUser.length; idx++){
                    if(this.roomUser[idx].user_id == this.user.id){
                        rooms.push(roomUser[idx].room_id);
                    }
                }
                for(let idx = 0; idx < this.rooms.length; idx++){
                    if(rooms.indexOf(this.rooms[idx].id) == -1){
                        this.room.index = idx;
                        return;
                    }
                }
                this.noRoomsToAdd = true;
            },
            AddRoom(){
                this.newRoomLink.data.user_id = this.users[this.user.index].id;
                this.newRoomLink.data.room_id = this.rooms[this.room.index].id;

                axios.post('api/room_user', this.newRoomLink.data)
                .then(response => {
                    this.users = response.data.users;
                    this.roomUser = response.data.room_user;
                    this.checkRooms();
                }).catch(error => {
                    this.newRoomLink.errors.record(error.response.data.errors)
                });
            },
            notYetAdded(id){   
                let count = 0;
                for(let idx = 0; idx < this.roomUser.length; idx++){
                    console.log(this.user.index);
                    if(this.roomUser[idx].user_id ==this.users[this.user.index].id){
                        console.log(this.roomUser[idx].room_id, id);
                        if(this.roomUser[idx].room_id == id){
                            return true;
                        }
                    }
                }
                return false;
            },
        }
    }
</script>
