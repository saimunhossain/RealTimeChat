<template>
    <v-layout row>
        <v-flex class="online-users" fixed xs3>
            <v-list>    
                <v-list-tile v-for="user in users" :key="user.id" @click="">
                    <v-list-tile-action>
                        <v-icon color="green">account_circle</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>{{ user.name }}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-flex>

        <v-flex class="messages mb-5" xs9>
            <v-list>
                <v-list-tile class="p-3" v-for="(message, index) in allMessages" :key="index">
                    <v-layout :align-end="(user.id!==message.user.id)" column>
                        <v-flex>
                            <v-layout column>
                                <v-flex>
                                    <span class="font-italic">{{ message.user.name }}</span>
                                </v-flex>
                                <v-flex>
                                    <v-chip :color="(user.id!==message.user.id)?'deep-purple':'light-blue'" text-color="white">
                                        <v-list-tile-content>
                                            {{ message.message }}
                                        </v-list-tile-content>
                                    </v-chip>
                                </v-flex>
                                <v-flex class="small font-italic">{{message.created_at | mydate}}</v-flex>
                            </v-layout>
                        </v-flex>
                    </v-layout>
                </v-list-tile>
            </v-list>
        </v-flex>

        <v-footer class="vfooter" height="auto" xs8 offset-xs2 fixed >
            <v-layout row>
                <v-flex xs6 offset-xs3 justify-center align-center>
                    <v-text-field v-model="message" @keyup.enter="sendMessage" rows=2 label="Enter Message" single-line></v-text-field>
                </v-flex>

                <v-flex xs2> 
                    <v-btn @click="sendMessage" 
                    dark class="mt-3 ml-2 white-text" small color="green">send</v-btn>
                </v-flex>
            </v-layout>
        </v-footer>
    </v-layout>
</template>

<script>
    export default {
    props:['user'],
    
    data () {
      return {
        message:null,
        allMessages:[],
        users:[]
      }
    },
    methods:{
      sendMessage(){
        //check if there message
        if(!this.message){
          return alert('Please enter message');
        }
          axios.post('/messages', {message: this.message}).then(response => {
                    this.message=null;
                    this.allMessages.push(response.data.message)
                    setTimeout(this.scrollToEnd,100);
          });
      },
      fetchMessages() {
            axios.get('/messages').then(response => {
                this.allMessages = response.data;
            });
        },
       fetchUsers() {
            axios.get('/users').then(response => {
                this.users = response.data;
            });
        }, 
      scrollToEnd(){
        window.scrollTo(0,99999);
      }
    
    },
    mounted(){
    },
    created(){
      this.fetchMessages();
      this.fetchUsers();
      
      Echo.private('RealTimeChat')
      .listen('MessageSent',(e)=>{
          this.allMessages.push(e.message)
          setTimeout(this.scrollToEnd,100);
      });
    }
    
  }
</script>

<style scoped>
/* .theme--light.v-list {
    background: #fff;
    color: #000;
    margin-bottom: 68px;
    margin-top: 58px;
} */

.online-users,.messages{
  overflow-y:scroll;
  height:100vh;
  position: sticky;
}
</style>