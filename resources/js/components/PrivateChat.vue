<template>
  <v-layout row>
    <v-flex class="online-users" xs3>
      <v-list>
          <v-list-tile
            :color="(friend.id==activeFriend)?'green':''"
            v-for="friend in users"
            v-if="user.id !=friend.id"
            :key="friend.id"
            @click="activeFriend=friend.id"
          >
            <v-list-tile-action>
              <v-icon color="green">account_circle</v-icon>
            </v-list-tile-action>

            <v-list-tile-content>
              <v-list-tile-title>{{friend.name}}</v-list-tile-title>
            </v-list-tile-content>

            <!-- <v-list-tile-avatar>
              <img :src="item.avatar">
            </v-list-tile-avatar> -->
          </v-list-tile>
        </v-list>

    </v-flex>

    <v-flex id="privateMessageBox" class="messages mb-5" xs9>
       <v-list>
            <v-list-tile
              class="p-3"
              v-for="(message, index) in allMessages" 
              :key="index"
            >

           <v-layout 
           :align-end="(user.id!==message.user.id)" 
            column
           >
             <v-flex>
               <v-layout column>
                  <v-flex>
                    <span class="small font-italic">{{message.user.name}}</span>
                  </v-flex>

                  <v-flex>
                    <v-chip
                      :color="(user.id!==message.user.id)?'red':'green'"
                      text-color="white"
                    >

                      <v-list-tile-content >
                        {{message.message}}
                      </v-list-tile-content>
                    </v-chip>
                  </v-flex>

                  <v-flex class="caption font-italic">
                    {{message.created_at}}
                  </v-flex>
               </v-layout>
             </v-flex>
           </v-layout>


            </v-list-tile>
        </v-list>


      <v-footer

      height="auto"
      fixed
      color="grey"
      >
      <v-layout row >
        <v-flex xs6 offset-xs3 justify-center align-center>
            <v-text-field
              rows=2
              v-model="message"
              label="Enter Message"
              single-line
              @keyup.enter="sendMessage"
            ></v-text-field>
        </v-flex>

        <v-flex xs2> 
            <v-btn 
              @click="sendMessage"
             dark class="mt-3 ml-2 white--text" small color="green">send</v-btn>
        </v-flex>
      </v-layout>


    </v-footer>


    </v-flex>

  </v-layout>
</template>

<script>
  export default {
    props:['user'],
    
    data () {
      return {
        message:null,
        activeFriend:null,
        allMessages:[],
        users:[],
      }
    },
    watch:{
      activeFriend(val){
        this.fetchMessages();
      }
    },
    methods:{
      sendMessage(){
        //check if there message
        if(!this.message){
          return alert('Please enter message');
        }
        if(!this.activeFriend){
          return alert('Please select friend');
        }
          axios.post('/private-messages/'+this.activeFriend, {message: this.message}).then(response => {
                    this.message=null;
                    this.allMessages.push(response.data.message)
                    setTimeout(this.scrollToEnd,100);
          });
      },
      fetchMessages() {
         if(!this.activeFriend){
          return alert('Please select friend');
        }
            axios.get('/private-messages/'+this.activeFriend).then(response => {
                this.allMessages = response.data;
            });
        },
      fetchUsers() {
            axios.get('/users').then(response => {
                this.users = response.data;
            });
        },
      scrollToEnd(){
        document.getElementById('privateMessageBox').scrollTo(0,99999);
      }
    
    },
    mounted(){
    },
    created(){
              this.fetchUsers();
              Echo.private('privatechat.'+this.user.id)
              .listen('PrivateMessageSent',(e)=>{
                  this.activeFriend=e.message.user_id;
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