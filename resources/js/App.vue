<template>
    <div class="container">
        <div class="content">
            <div class="emails">
                <label> Please add your email addresses below, one address per line:</label>
                <textarea class="form-control" id="emailList" rows="10" v-model.trim="emailList"></textarea>
                <button @click="sendJokes" type="button" class="btn btn-primary" :disabled="isLoading">{{ isLoading ? 'Sending please wait...' : 'Send' }}</button>
            </div>
            <div v-if="errorMessage">
                {{ errorMessage }}
            </div>
            <div v-if="responseData && !errorMessage">
                <p>Joke was successfully scheduled for sending to following emails (sorted by domain name/name part):</p>
                <ul v-for="email in finalEmailList">
                    <li>{{ email }}</li>
                </ul>
                <p>Joke: <i>{{ joke }}</i></p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            emailList: [],
            finalEmailList: [],
            listIsValid: true,
            errorMessage: null,
            payload: [],
            isLoading: false,
            responseData: null,
            joke: ''
        };
    },
    methods: {
        async sendJokes() {
            this.resetBeforeRequest();
            this.validateList();        // basic validation
            if(!this.listIsValid) {
                return;
            }
            this.isLoading = true;

            try {
                this.responseData = await axios.post('/api/sendEmail', {emails: this.payload});
                this.errorMessage = null;
                this.isLoading = false;
            } catch (error) {
                this.errorMessage = 'Contacting server failed! Please try again later.';
                this.isLoading = false;
            }

            this.formatData();
            this.resetAfterRequest();
        },
        validateList() {
            this.listIsValid = true;
            if (this.emailList.length === 0) {
                this.errorMessage = 'Email list can not be empty!';
                this.listIsValid = false;
            } else {
                let lines = this.emailList.split('\n');
                for (let i = 0; i < lines.length; i++) {
                    if (lines[i] === '' || lines[i].length < 5 || lines[i].length > 100 || !lines[i].includes("@")) {
                        this.errorMessage = 'Email list is not valid! Please check your input.';
                        this.listIsValid = false;
                    } else {
                        this.listIsValid = true;
                        this.payload.push(lines[i]);
                    }
                }
            }
        },
        resetBeforeRequest() {
            this.responseData = null;
            this.payload = [];
            this.finalEmailList = [];
        },
        resetAfterRequest() {
            this.emailList = [];
        },
        formatData() {
            let jsonObj = JSON.parse(this.responseData.data);
            if (jsonObj.emailList && jsonObj.joke) {      // check if we get expected format
                this.finalEmailList = jsonObj.emailList;
                this.joke = jsonObj.joke;
            } else {
                this.errorMessage = 'Wrong data received from server! Please try again later.'
            }
        }
    }
}
</script>

<style scoped>
.container {
    padding: 5rem;
    max-width: 50%;
}
.btn-primary {
    margin-top: 1rem;
    margin-bottom: 1rem;
}
</style>
