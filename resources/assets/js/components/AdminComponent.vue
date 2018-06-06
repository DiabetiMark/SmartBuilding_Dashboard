<template>
    <div>
        <!-- WIP -->
        <div class="notification is-danger has-text-centered">
            <strong>WIP - Submitten werkt sowieso nog niet</strong>
        </div>

        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
                <li><a href="/">Home</a></li>
                <li class="is-active"><a href="#" aria-current="page">Gebruikers</a></li>
            </ul>
        </nav>
        <hr>

        <div class="columns is-gapless">
            <div class="column is-two-thirds">
                <h4 class="title is-4">Gebruiker toevoegen</h4>
                <div class="steps" id="stepsDemo">
                    <div class="step-item is-active is-success">
                        <div class="step-marker">1</div>
                        <div class="step-details">
                            <p class="step-title">Account</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-marker">2</div>
                        <div class="step-details">
                            <p class="step-title">Rollen</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-marker">3</div>
                        <div class="step-details">
                            <p class="step-title">Ruimtes</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-marker">4</div>
                        <div class="step-details">
                            <p class="step-title">Klaar</p>
                        </div>
                    </div>
                    <div class="steps-content">
                        <div class="step-content has-text-centered is-active">
                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Naam</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input" name="name" id="name" type="text" placeholder="Bijv. Piet" data-validate="require">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Gebruikersnaam</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input" name="username" id="username" type="text" placeholder="Bijv. Piet" data-validate="require">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Email</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control has-icon has-icon-right">
                                            <input class="input" type="email" name="email" id="email" placeholder="Bijv. piet@aareon.nl" data-validate="require">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step-content has-text-centered">
                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Rol</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control has-icons-left">
                                            <div class="select">
                                                <select v-model='addModule.data.room_id' v-if="rooms.length > 0">
                                                    <option value="" selected disabled hidden>Kies een rol</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                </select>
                                                <span class="icon is-large is-left">
                                                <i class="fas fa-user"></i>
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step-content has-text-centered">
                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Ruimtes</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <div class="select is-multiple">
                                                <select multiple size="8">
                                                    <option :for="room.id" v-for="room in this.rooms" :value="room.id" v-model="addUser.data.rooms">
                                                        {{room.roomName}}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step-content has-text-centered">
                            <h1 class="title is-5">Het account is aangemaakt en een email is verstuurd!</h1>
                        </div>
                    </div>
                    <div class="steps-actions">
                        <div class="steps-action">
                            <a href="#" data-nav="previous" class="button is-light">Terug</a>
                        </div>
                        <div class="steps-action">
                            <a href="#" data-nav="next" class="button is-light">Volgende</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <button @click="changeAdd(0)">Voeg kamer aan gebruiker toe</button>
        <button @click="changeAdd(1)">Voeg gebruiker toe</button>
        <button @click="changeAdd(2)">Voeg kamer toe</button>
        <button @click="changeAdd(3)">Voeg module toe</button>
        <button @click="changeAdd(4)">Voeg sensor toe</button>
        <template v-if="this.open[0]">
            <br>
            Gebruiker:
            <select v-model='user_id' @change="userChanged" v-if="users">
                <option v-for="user in users" :value="user.id" >{{user.username}}</option>
            </select>
            <br>
            <template v-if="userInfo && userInfo.rooms.length > 0">
                <br>
                Kamer:
                <select v-model='room_id' @change="roomChanged">
                    <option v-for="(room, key) in userInfo.rooms" :value="key" >{{room.roomName}}</option>
                </select>
                <br>
                <p>Kamer beschrijving: {{ userInfo.rooms[room_id].roomDescription }}</p>
                
                <template v-if="userInfo.rooms[room_id].sensor_modules.length > 0">
                    <br>
                    Sensormodule:
                    <select v-model='sensor_modules_id' @change="sensor_moduleChanged">
                        <option v-for="(sensor_module, key) in userInfo.rooms[room_id].sensor_modules" :value="key" >{{sensor_module.moduleName}}</option>
                    </select>
                    <template v-if="userInfo.rooms[room_id].sensor_modules[sensor_modules_id].sensors.length>0">
                        <br>
                        <div v-for="sensor in userInfo.rooms[room_id].sensor_modules[sensor_modules_id].sensors" >
                            <p>{{sensor.name}}</p>
                        </div>
                    </template>
                    <template v-else>
                        <p>Er zijn geen sensoren beschikbaar voor deze module</p>
                    </template>
                </template>
                <template v-else>
                    <p>Er zijn geen modules beschikbaar in deze kamer</p>
                </template>
            </template>
            <template v-else>
                <p>Er zijn geen ruimtes beschikbaar voor deze gebruiker</p>
            </template>

            <div>
            <br>
            <template v-if="!noRoomsToAdd">
                <button @click="changeAddRoom = !changeAddRoom">Voeg ruimte toe</button>
                <div v-if="changeAddRoom">
                    <select v-model='add_room_id' v-if="rooms.length > 0">
                        <option v-for="(room, key) in rooms" :value="key" v-if="notYetAdded(room.id)">{{room.roomName}}</option>
                    </select>
                    <p>{{ rooms[add_room_id].roomDescription }}</p>
                    <button @click="AddRoom">Voeg kamer toe</button>
                </div>
            </template>
            <template v-else>
                <p>Voor deze gebruiker zijn er geen kamers meer om toe tevoegen.</p>
            </template>
            </div>
        </template>
        <template v-if="this.open[1]">
            <form @submit.prevent="createUser" @keydown="addUser.errors.clear($event.target.name)">
                <div>
                    <div>
                        <input name="naam" type="text" placeholder="Naam" v-model='addUser.data.name' required>
                    </div>
                </div>
                <div>
                    <div>
                        <input name="gebruikersnaam" type="text" placeholder="Gebruikersnaam" v-model='addUser.data.username' required>
                    </div>
                </div>
                <div>
                    <div>
                        <input name="email" type="email" placeholder="Email" v-model='addUser.data.email' required>
                    </div>
                </div>
                <div>
                    <div>
                        <select v-model='addModule.data.room_id' v-if="rooms.length > 0">
                            <option value="1" >1</option>
                            <option value="2" >2</option>
                            <option value="3" >3</option>
                        </select>
                    </div>
                </div>
                <div>
                    <div>
                        <div class="multiselect">
                            <div class="selectBox" @click="showCheckboxes">
                                <select>
                                    <option>Select an option</option>
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
                </div>

                <input value="Toevoegen" type="submit">
            </form>
        </template>
        <template v-if="this.open[2]">
            <form @submit.prevent="createRoom" @keydown="addRoom.errors.clear($event.target.name)">
                <div>
                    <div>
                        <input name="naam" type="text" placeholder="Ruimte naam" v-model='addRoom.data.roomName' autofocus required>
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <input  name="omschrijving" type="text" placeholder="Ruimte omschrijving" v-model='addRoom.data.roomDescription' required>
                    </div>
                </div>
                <input value="Toevoegen" type="submit">
            </form>
        </template>
        <template v-if="this.open[3]">
            <template v-if="!allAdded()">
                <form @submit.prevent="createModule" @keydown="addModule.errors.clear($event.target.name)">
                    <div>
                        <div>
                            Module: 
                            <select v-model='addModule.id' v-if="modules.length > 0">
                                <option v-for="modul in modules" :value="modul.id" v-if="modul.room_id == null" >{{modul.moduleName}}</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div>
                            Ruimte: 
                            <select v-model='addModule.data.room_id' v-if="rooms.length > 0">
                                <option v-for="room in rooms" :value="room.id" >{{room.roomName}}</option>
                            </select>
                        </div>
                    </div>
                    <input value="Toevoegen" type="submit">
                </form>
            </template>
            <template v-else>
                Er zijn geen los modules beschikbaar
            </template>
        </template>
        <template v-if="this.open[4]">
            <p>ff kijken hoe we dit gaan doen...</p>
        </template>
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
    import axios from 'axios';

    export default {
        props: ['admin'],

        data(){
            return{
                addRoom: {
                    data: {
                        roomName: '',
                        roomDescription: '',
                    },
                    errors: new Errors(),
                },
                addModule: {
                    id: '',
                    data: {
                        room_id: '',
                        user_id: '',
                    },
                    errors: new Errors(),
                },
                addSensor: {
                    data: {
                        moduleName: '',
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
                open: [
                    false,
                    false,
                    false,
                    false,
                    false,
                ],
                users: false,
                userInfo: false,
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
                    this.userInfo = response.data.allValues;
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
                    for(var j=0; j < this.userInfo.rooms.length && !foundID; j++){
                        if( this.rooms[i].id == this.userInfo.rooms[j].id){
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
                axios.get('api/user/' + this.user_id + '/getAll')
                .then(response => {
                    this.userInfo = response.data;
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
                    this.userInfo = response.data;
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
                for(var i=0; i < this.userInfo.rooms.length; i++){
                    if( this.userInfo.rooms[i].id == id){
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
