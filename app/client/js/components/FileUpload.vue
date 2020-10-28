<template>
  <div class="container">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <label>File
        <input type="file" id="file" ref="file"/>
      </label>
        <button v-on:click="uploadFile()">Upload</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'FileUpload',
  components: {

  },
  data() {
    return {
      file:''
    };
  },
  methods: {     
            uploadFile: function(){
                this.file = this.$refs.file.files[0];                  
                let formData = new FormData();
                formData.append('file', this.file);
                this.$refs.file.value = '';
                console.log(this.$route.params);
                let parkId = this.$route.params.id;
                axios.post('http://localhost/somar/dev/tasks/Doggo-Task-NewParksImageUpload?ParkID='+parkId, formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                })
                .then(function (response) {
                    if(!response.data){
                        alert('File not uploaded.');
                    }else{
                        alert('File uploaded successfully.');                        
                    }
                })
                .catch(function (error) {
                    console.log(error);
                 });
            }
    },
};
</script>