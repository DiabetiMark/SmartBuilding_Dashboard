<template>
    <div>
        <div class="column is-half">
            <label class="label">Zoek een ruimte</label>
            <div class="control has-icons-left has-icons-right">
                <input v-model="roomSearch" class="input" type="email" placeholder="Bijv. kantoor-boven">
                <span class="icon is-small is-left">
                  <i class="fas fa-search"></i>
                </span>
            </div>
        </div>
        <hr>
        <table class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
            <tr>
                <th>ID</th>
                <th>Naam</th>
                <th>Beschrijving</th>
                <th>Laatste update</th>
                <th>Gemaakt op</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="room in rooms" v-if="room.isVisible">
                <td>{{ room.id }}</td>
                <td>{{ room.roomName }}</td>
                <td>{{ room.roomDescription.substr(0, 50) + '..' }} (<a href="#">Lees meer</a>)</td>
                <td>{{ room.created_at }}</td>
                <td>{{ room.updated_at }}</td>
                <td>
                    <div class="field is-grouped">
                        <p class="control">
                            <a class="button is-small is-link">
                                Bekijk
                            </a>
                        </p>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        mounted() {
            console.log('RoomOverviewComponent mounted')
        },

        data() {
            return {
                roomSearch: '',
                rooms: []
            }
        },

        methods: {
            getRooms(){
                axios.get('/api/room').then(response => {
                    this.rooms = response.data;
                    this.rooms.forEach((room) => {
                       room.isVisible = true;
                    });
                    console.log(response.data);
                }).catch(e => {
                    console.log(e);
                });
            }
        },

        created(){
            this.getRooms();
        },

        watch: {
            roomSearch: function(value){
                this.rooms.forEach((room) => {
                    if(!room.roomName.startsWith(value)){
                        return room.isVisible = false;
                    }
                    room.isVisible = true;
                });
            }
        }

    }
</script>