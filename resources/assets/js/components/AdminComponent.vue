<template>
    <div>
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
                    <template v-if="userInfo.rooms[room_id].sensor_modules[sensor_modules_id].data_registers.length>0">
                        <div v-for="data_register in userInfo.rooms[room_id].sensor_modules[sensor_modules_id].data_registers" >
                            <p>{{data_register.field.name}}</p>
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
        <template v-if="this.open[2]">
            <form @submit.prevent="createRoom" @keydown="user.errors.clear($event.target.name)">
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
            <form @submit.prevent="createModule" @keydown="user.errors.clear($event.target.name)">
                <div>
                    <div>
                        <input name="naam" type="text" placeholder="Module naam" v-model='addModule.data.moduleName' autofocus required>
                    </div>
                </div>
                <div>
                    <div>Ruimte: 
                        <select v-model='addModule.data.room_id' v-if="rooms.length > 0">
                            <option v-for="room in rooms" :value="room.id" >{{room.roomName}}</option>
                        </select>
                    </div>
                </div>
                <input value="Toevoegen" type="submit">
            </form>
        </template>
        <template v-if="this.open[4]">
            <p>ff kijken hoe we dit gaan doen...</p>
        </template>
        <template v-if="this.open[1]">
            <form @submit.prevent="createUser" @keydown="user.errors.clear($event.target.name)">
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

                <input value="Toevoegen" type="submit">
            </form>
        </template>
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
                    }
                },
                addModule: {
                    data: {
                        moduleName: '',
                        room_id: '',
                        user_id: '',
                    }
                },
                addSensor: {
                    data: {
                        moduleName: '',
                        room_id: '',
                        user_id: '',
                    }
                },
                addUser: {
                    data: {
                        username: '',
                        email: '',
                        name: '',
                        role: '',
                    }
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
                changeAddRoom: false,
                add_room_id: false,
                rooms: false,
                newRoomLink: {
                    room_id: '',
                    user_id: '',
                },
                addRoomToUser: false,
                noRoomsToAdd: true,

            }
        },

        mounted() {
        },

        created(){
            this.getData();
        },

        methods: {
            createUser(){
                axios.post('/api/user', this.addUser.data)
                .then(response => {
                    this.users = response.data;
                    this.user_id = response.data[0].id;
                    this.userChanged();
                }).catch(e => {
                    console.log(e);
                });
            },
            changeAdd(index){
                for(var i = 0; i < Object.keys(this.open).length; i++){
                    console.log(i + "\t" + index);
                    
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
                }).catch(e => {
                    console.log(e);
                });
            },
            createModule(){
                this.addModule.data.user_id = this.user_id;
                axios.post('/api/sensormodule', this.addModule.data)
                .then(response => {
                    this.userInfo = response.data;
                    this.room_id = 0;
                    this.checkRooms();
                }).catch(e => {
                    console.log(e);
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
                this.newRoomLink.user_id = this.user_id;
                this.newRoomLink.room_id = this.rooms[this.add_room_id].id;

                axios.post('api/room_user', this.newRoomLink)
                .then(response => {
                    this.userInfo = response.data;
                    this.checkRooms();
                }).catch(e => {
                    console.log(e);
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
            }
        }
    }
</script>
