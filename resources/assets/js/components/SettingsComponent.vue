<template>
    <div>
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
                <li><a href="/">Home</a></li>
                <li class="is-active"><a href="#" aria-current="page">Instellingen</a></li>
            </ul>
        </nav>
        <hr>

        <div class="columns is-gapless">
            <div class="column">
                <h4 class="title is-4">Module Aan Ruimte Toevoegen</h4>
                <hr>
                <template v-if="!allAdded() && modules">
                    <form @submit.prevent="addModuleToRoom" @keydown="addModule.errors.clear($event.target.name)">
                        <div>
                            <div>
                                Module:
                                <select v-model='addModule.index.module' v-if="modules.length > 0">
                                    <option v-for="(modul, key) in modules" :value="key" v-if="modul.room_id == null" >{{modul.moduleName}}</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <div>
                                Ruimte:
                                <select v-model='addModule.index.room' v-if="rooms.length > 0">
                                    <option v-for="(room, key) in rooms" :value="key" >{{room.roomName}}</option>
                                </select>
                                <p class="help" v-if="rooms.length > 0"><strong>Beschrijving:</strong> {{ rooms[addModule.index.room].roomDescription }}</p>
                            </div>
                        </div>
                        <input value="Toevoegen" type="submit">
                    </form>
                </template>
                <template v-else-if="allAdded() && modules">
                    Er zijn geen los modules beschikbaar
                </template>
                <template v-else-if="!modules">
                    ff wachten
                </template>
            </div>
            <div class="column">
                <h4 class="title is-4">Module Van Ruimte Verwijderen</h4>
                <hr>
                <template v-if="modules && rooms">
                    <div>
                        <div>
                            Ruimte:
                            <select v-model='deleteModule.room_id.index' v-if="rooms.length > 0">
                                <option v-for="(room, key) in rooms" :value="key" >{{room.roomName}}</option>
                            </select>
                            <p class="help" v-if="rooms.length > 0"><strong>Beschrijving:</strong> {{ rooms[deleteModule.room_id.index].roomDescription }}</p>
                        </div>
                    </div>
                    <div>
                        <div v-if="modules.length > 0 && hasModules() && deleteModule.room_id.index >= 0" >
                            Modules:
                            <div v-if="modul.room_id == rooms[deleteModule.room_id.index].id" v-for="modul in modules">
                                <p>{{modul.moduleName}}</p><button @click="deleteModuleChange(modul.id)">verwijder</button>
                            </div>
                        </div>
                        <div v-if="!hasModules()">
                            <p>er zijn geen modules beschikbaar voor deze ruimte</p>
                        </div>

                    </div>
                </template>
            </div>
            <div class="column">
                <h4 class="title is-4">Ruimte Toevoegen</h4>
                <hr>
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
            </div>
            <div class="column" v-if="rooms">
                <h4 class="title is-4">Ruimte Aanpassen/verwijderen</h4>
                <hr>
                Ruimtes:
                <select v-model='room.room_id.index' v-if="rooms.length > 0">
                    <option v-for="(room1, key) in rooms" :value="key" >{{room1.roomName}}</option>
                </select>
                <p class="help" v-if="rooms.length > 0  && room.room_id.index >= 0 && !isOpen"><strong>Beschrijving:</strong> {{ rooms[room.room_id.index].roomDescription }}</p>
                <div v-if="isOpen">
                     <form @submit.prevent="addModuleToRoom" @keydown="addModule.errors.clear($event.target.name)">
                        <div>
                            <div>
                                Naam: 
                                <input type="text" name="name" v-model="room.data.roomName">
                            </div>
                        </div>
                        <div>
                            <div>
                                Beschrijving:
                                <textarea type="text" name="name" v-model="room.data.roomDescription"></textarea>
                            </div>
                        </div>
                    </form>

                </div>
                <button @click='updateRoom()'>Wijzigen</button> 
                <button @click='deleteRoom()'>Verwijderen</button> 
                <div v-if="rooms.length == 0">
                    <p>er zijn geen ruimtes beschikbaar</p>
                </div>

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
                addRoom: {
                    data: {
                        roomName: '',
                        roomDescription: '',
                    },
                    errors: new Errors(),
                },
                addModule: {
                    index: {
                        module: '',
                        room: '',
                    },
                    errors: new Errors(),
                },
                deleteModule: {
                    data: {
                        id: '',
                    },
                    room_id: {
                        index: '',
                    },
                    errors: new Errors(),
                },
                room: {
                    data: {
                        roomName: '',
                        roomDescription: '',
                    },
                    room_id:{
                        index: '',
                    },
                    id: '',
                },
                room_id: false,
                sensor_modules_id: 0,
                sensor_id: 0,
                changeAddRoom: false,
                add_room_id: false,
                rooms: false,
                addRoomToUser: false,
                noRoomsToAdd: true,
                expanded: false,
                modules: false,
                isOpen: false,
            }
        },

        mounted() {
        },

        created(){
            this.getModules();
            this.getRooms();
        },

        methods: {
            updateRoom(){
                let id = this.rooms[this.room.room_id.index].id
                if(this.isOpen){
                    axios.put('api/room/' + id, this.$data.room.data)
                    .then(response => {
                        //set rooms
                        this.rooms = response.data;
                    })
                    .catch(response => {
                        console.log(response);
                    })
                } else {
                    this.room.data.roomName = this.rooms[this.room.room_id.index].roomName;
                    this.room.data.roomDescription = this.rooms[this.room.room_id.index].roomDescription;
                }

                this.isOpen =  !this.isOpen;
            },
            deleteRoom(){
                let id = this.rooms[this.room.room_id.index].id
                axios.delete('api/room/' + id)
                .then(response => {
                    //set modules
                    this.modules = response.data.modules;
                    

                    //set rooms
                    this.rooms = response.data.rooms;
                    this.deleteModule.room_id.index = 0;
                    this.addModule.index.room = 0;
                    this.room.room_id.index = 0;

                    for(let index = 0; index < this.modules.length; index++){
                        if(this.modules[index].room_id == null){
                            this.addModule.index.module = index;
                            return;
                        }
                    }  
                })
                .catch(response => {
                    console.log(response);
                })
            },
            deleteModuleChange(id){
                let data = {
                    room_id: -1,
                };
                axios.put('/api/sensormodule/' + id, data)
                .then(response => {
                    this.modules = response.data;
                    
                    for(let index = 0; index < this.modules.length; index++){
                        if(this.modules[index].room_id == null){
                            this.addModule.index.module = index;
                            return;
                        }
                    }                    
                })
                .catch(error => {

                })
            },
            getModules(){
                axios.get('/api/sensormodule')
                .then(response => {
                    this.modules = response.data;
                    for(let index = 0; index < this.modules.length; index++){
                        if(this.modules[index].room_id == null){
                            this.addModule.index.module = index;
                            return;
                        }
                    }         
                })
                .catch(error => {

                })
            },
            getRooms(){
                axios.get('/api/room')
                .then(response => {
                    this.rooms = response.data;                    
                    this.deleteModule.room_id.index = 0;
                    this.addModule.index.room = 0;
                    this.room.room_id.index = 0;
                }).catch(e => {
                    console.log(e);
                });
            },
            addModuleToRoom(){
                let data = {
                   id: this.modules[this.addModule.index.module].id,
                   room_id: this.rooms[this.addModule.index.room].id,
                }
                axios.put('/api/sensormodule/' + this.modules[this.addModule.index.module].id, data)
                .then(response => {
                    this.modules = response.data;
                    
                    for(let index = 0; index < this.modules.length; index++){
                        if(this.modules[index].room_id == null){
                            this.addModule.index.module = index;
                            return;
                        }
                    }    
                }).catch(error => {
                    this.addModule.errors.record(error.response.data.errors)
                });
            },
            createRoom(){
                axios.post('/api/room', this.addRoom.data)
                .then(response => {
                    
                    this.rooms = response.data;
                    this.addRoom.data.roomName = '';
                    this.addRoom.data.roomDescription = '';
                }).catch(error => {
                    this.addRoom.errors.record(error.response.data.errors)
                });
            },
            allAdded(){
                for(var i = 0; i < this.modules.length; i++){
                    if(this.modules[i].room_id == null){
                        return false
                    }
                }
                return true
            },
            hasModules(){
                for(var i = 0; i < this.modules.length; i++){
                    if(this.modules[i].room_id == this.rooms[this.deleteModule.room_id.index].id){
                        return true
                    }
                }
                return false
            }

        }
    }
</script>
