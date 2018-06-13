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
                                <select v-model='room.index' v-if="rooms.length > 0">
                                    <option v-for="(room, key) in rooms" :value="key" v-if="notYetAdded(room.id)">{{room.roomName}}</option>
                                </select>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-home"></i>
                                </span>
                            </div>
                            <p class="help" v-if="rooms"><strong>Beschrijving:</strong> {{ rooms[room.index].roomDescription }}</p>
                        </div>
                    </div>

                    <template v-if="rooms[room.index].sensor_modules.length > 0">
                        <div class="field">
                            <label class="label">Sensormodule</label>
                            <div class="control has-icons-left">
                                <div class="select">
                                    <select v-model='sensormodule.index'>
                                        <option v-for="(sensor_module, key) in rooms[room.index].sensor_modules" :value="key" >{{sensor_module.moduleName}}</option>
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
                    <p>Voor deze gebruiker zijn er geen kamers meer om toe tevoegen.</p>
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
                        username: 'ChielTimmermans',
                        email: 'chtm@aareon.com',
                        name: 'chiel',
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
                noRoomsToAdd: false,
                newRoomLink: {
                    data: {
                        room_id: '',
                        user_id: '',
                    },
                    errors: new Errors(),
                },
            }
        },

        mounted() {
        },

        created(){
            this.getData();
        },

        methods: {
            getData(){
                promises.push(axios.get('/api/room/getAll'));
                promises.push(axios.get('/api/user/getAll'));
                axios.all(promises)
                .then(response => {
                    this.rooms = response[0].data;
                    this.users = response[1].data;

                    this.user.index = 0;

                    for(let index = 0; index < this.rooms.length; index++){
                        if(this.notYetAdded(this.rooms[index].id)){
                            this.room.index = index;
                        }
                    }
                    
                    this.checkRooms();
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
                    this.users = response.data;
                    this.user_id = response.data[0].id;
                }).catch(error => {
                    this.user.errors.record(error.response.data.errors)
                });
            },
            checkRooms(){
                console.log("titom");
                
                var foundID = false;
                this.noRoomsToAdd = false;
                for(var i=0; i < this.rooms.length; i++){
                    foundID = false;

                    for(var j=0; j < this.users[this.user.index].rooms.length && !foundID; j++){
                        if( this.rooms[i].id == this.users[this.user.index].rooms[j].id){
                            foundID = true;
                        }
                    }
                    if(!foundID){
                        this.room.index = i;
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
                    this.users = response.data;
                    this.checkRooms();
                }).catch(error => {
                    this.newRoomLink.errors.record(error.response.data.errors)
                });
            },
            notYetAdded(id){   
                             
                for(var i=0; i < this.users[this.user.index].rooms.length; i++){
                    if( this.users[this.user.index].rooms[i].id == id){
                        return false
                    }
                }
                return true
            },
            // allAdded(){
            //     for(var i = 0; i < this.modules.length; i++){
            //         if(this.modules[i].room_id == null){
            //             return false
            //         }
            //     }
            //     return true
            // }
        }
    }
</script>
