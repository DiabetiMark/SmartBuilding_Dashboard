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
                                <select v-model='addModule.data.id' v-if="modules.length > 0">
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
                <template v-if="modules">
                    <div>
                        <div>
                            Ruimte:
                            <select v-model='deleteModule.room_id' v-if="rooms.length > 0">
                                <option v-for="room in rooms" :value="room.id" >{{room.roomName}}</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div v-if="modules.length > 0 && hasModules()" >
                            Modules:
                            <div v-if="modul.room_id == deleteModule.room_id" v-for="modul in modules">
                                <p>{{modul.moduleName}}</p><button @click="deleteModuleChange(modul.id)">verwijder</button>
                            </div>
                        </div>
                        <div v-if="!hasModules()">
                            <p>er zijn geen modules beschikbaar voor deze ruimte</p>
                        </div>

                    </div>
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
                    data: {
                        room_id: '',
                        id: '',
                    },
                    errors: new Errors(),
                },
                deleteModule: {
                    data: {
                        id: '',
                    },
                    room_id: '',
                    errors: new Errors(),
                },
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
            this.getModules();
            this.getRooms();
        },

        methods: {
            deleteModuleChange(id){
                let data = {
                    room_id: -1,
                };
                axios.put('/api/sensormodule/' + id, data)
                .then(response => {
                    this.modules = response.data;
                    for(let index = 0; index < response.data.length; index++){
                        if(response.data[index].room_id == null){
                            this.addModule.data.id = response.data[index].id;
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
                    for(let index = 0; index < response.data.length; index++){
                        if(response.data[index].room_id == null){
                            this.addModule.data.id = response.data[index].id;
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
                    this.deleteModule.room_id = response.data[0].id;
                }).catch(e => {
                    console.log(e);
                });
            },
            addModuleToRoom(){
                axios.put('/api/sensormodule/' + this.addModule.data.id, this.addModule.data)
                .then(response => {
                    this.modules = response.data;
                    
                    for(let index = 0; index < this.modules.length; index++){
                        if(this.modules[index].room_id == null){
                            this.addModule.data.id = this.modules[index].id;
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
                    if(this.modules[i].room_id == this.deleteModule.room_id){
                        return true
                    }
                }
                return false
            }

        }
    }
</script>
