<template>
  <div class="container">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <h3>Image Uploads
      </h3>
        <ul class="image-uploads" v-if="renderComponent">
            <li v-for="item in imageItems" :key="item.ID">
                <img v-bind:src="item.ImagePath" v-if="item.ShowImage" /> 
            </li>
        </ul>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Vue from 'vue';
export default {
  name: 'UploadedImages',
  components: {

  },
  data() {
    return {
        renderComponent: true,
        parkId: this.$route.params.id,
        imageItems: [],
    };
  },
  methods:{
    getImages(idPassed){
        this.renderComponent = false;
        axios.get('api/v1/Doggo-Model-NewParkImageUpload.json')
            .then((response) => {
                console.log(response.data.items);
                this.imageItems = response.data.items.filter(item => item.ParkAssignedID == idPassed);
                
            })
            .catch((error) => {
                
                this.imageItems = [];
            });
            this.$nextTick(() => {
                // Add the component back in
                this.renderComponent = true;
            });
    }
  },
  mounted(){
      this.getImages(this.$route.params.id);
  },
  watch: {
    '$route.params.id': function (id) {    
      this.getImages(this.$route.params.id);
    }
  }
};
</script>