<template>
    <v-layout row>
        <v-flex xs12 sm6 offset-sm3>
            <v-card class="mb-4" color="purple">
                <v-list>
                    <v-subheader>Group Chat</v-subheader>
                    <v-divider></v-divider>
                    <v-list-tile class="p-3" v-for="(item, index) in allMessages" :key="index">
                        <v-layout :align-end="(index%2==0)" column>
                            <v-flex>
                                <v-layout column>
                                    <v-flex>
                                        <span class="small font-italic">Ethan Hawk</span>
                                    </v-flex>
                                    <v-flex>
                                        <v-chip :color="(index%2==0)?'red':'green'" text-color="white">
                                            <v-list-tile-content>
                                                {{ item.message }}
                                            </v-list-tile-content>
                                        </v-chip>
                                    </v-flex>
                                    <v-flex class="caption font-italic">2019-2-3</v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-list-tile>
                </v-list>
            </v-card>
        </v-flex>

        <v-footer class="vfooter" height="auto" xs8 offset-xs2 fixed >
            <v-layout row>
                <v-flex xs6 offset-xs3 justify-center align-center>
                    <v-text-field v-model="message" @keyup.enter="sendMessage" rows=2 label="Enter Message" single-line></v-text-field>
                </v-flex>

                <v-flex xs2> 
                    <v-btn @click="sendMessage" dark class="mt-3 ml-2 white-text" small color="green">send</v-btn>
                </v-flex>
            </v-layout>
        </v-footer>

    </v-layout>
</template>

<script>
    export default {
        data () {
            return {
                message:null,
                allMessages:[]
            }
        },
        methods:{
            sendMessage(){
                //check if there message
                if(!this.message){
                    return alert('Please enter message');
                }

                // this.allMessages.push(this.message);

                // send post request
                axios.post('/messages', {message: this.message}).then(response =>{
                    console.log(response.data);
                });
            },
            fetchMessages(){
                axios.get('/messages').then(response => {
                    this.allMessages = response.data;
                });
            }
        }
    }
</script>