<template>
    <div class="container">
        <div class="content">
            <div class="emails">
                <label for="emailList">Please add your email addresses below, one address per line:</label>
                <textarea class="form-control" id="emailList" v-model.trim="emailList"></textarea>
                <button @click="sendList" type="button" class="btn btn-primary">Send</button>
            </div>
            <div v-if="error">
                {{ error }}
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            emailList: [],
            error: null
        };
    },
    methods: {
        async sendList() {
            this.error = null;
            this.validateList();
            await axios.get('/api/sendEmail', this.emailList)
        },
        validateList() {    // Basic validation
            if (this.emailList.length === 0) {
                this.error = 'Email list can not be empty';
            } else {
                let lines = this.emailList.split('\n');
                for (let i = 0; i < lines.length; i++) {
                    if (lines[i] === '' || lines[i].length < 5 || lines[i].length > 100 || !lines[i].includes("@")) {
                        this.error = 'Email list is not valid. Please check your input.';
                    }
                }
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
}
</style>
