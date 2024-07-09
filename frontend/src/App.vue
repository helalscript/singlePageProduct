<script>
import axios from 'axios'
import { RouterLink, RouterView } from "vue-router";
import HelloWorld from "./components/HelloWorld.vue";
export default{
  data(){
    return{
      url: "http://127.0.0.1:8000/api/admin/products",
       name: "",
       price: "",
       description: "",
       path: [],
       productlist: [],
       imagePreview: [],
    }
  },
    methods: {
        // =================get all product list  ==============
        getProductList() {
            axios.get(this.url).then((response) => {
              console.log(response.data.data)
                const product = response.data.data;
                this.productlist = product;
                
            });
        },
 onFileSelected(event) {
      // this.path = event.target.files[0]
      const files = event.target.files;
      if (files && files.length > 0) {
        // Iterate through selected files
        for (const file of files) {
          if (file.type.startsWith('image/')) {
            this.createImagePreview(file);
            this.path.push(file);
          }
        }
        
      }
    },
    createImagePreview(file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        this.imagePreview.push(e.target.result); // Add image preview to the array
      };
      reader.readAsDataURL(file);
    },
 // ================= for create new product ==================
    save() {
            const formData = new FormData();
            formData.append('path', this.path);
        // this.imagePreview.forEach((preview) => {
        //   formData.append('images[]', preview);
        // });
            const alldata = {
                name: this.name,
                price: this.price,
                description: this.description,
                path: this.path
            }
            //console.log(alldata)
            axios.post(this.url, alldata, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                // this.name="";
                // this.price="";
                // this.description="";
                // this.path="";
                // this.imagePreview =[];
                // this.getProductList();
                console.log(response);
            });
        },
        },

    mounted() {
        this.getProductList();
    },
}
</script>

<template>
<div class="container-fluid">
   <div class="container">
    <div class="justify-content-center row">
<h1 class="card text-center bg-dark text-light">Multiple Image upload app</h1>
      <div class="col-md-6">
        <div>
          <!-- <div>status_msg</div> -->
            <div class="form-group">
              <label for="exampleFormControlInput1">Name</label>
              <input type="text" class="form-control" placeholder="Product name" required v-model="name"/>
            </div>
            <div class="form-group">
              <label for="exampleFormControlInput1">Price</label>
              <input type="text" class="form-control" placeholder="Product price" required v-model="price"/>
            </div>
            <div class="form-group">
              <label for="exampleFormControlTextarea1">Details</label>
              <textarea class="form-control" rows="3" required v-model="description"></textarea>
            </div>
            <div class="form-group">
              <label for="imageUpload">Image Upload</label>
              <div class="dropzone">
                <span>Drag or Drop File</span>
                <span>OR</span>
                <label for="dropzoneFile">Select File</label>
                <input type="file" id="dropzoneFile" class="dropzoneFile" @change="onFileSelected" multiple/>
              </div>
              <div class="row ">
                <!-- <div class="col-4">
                <img v-if="imagePreview" :src="imagePreview" width="150rem" height="175rem"/>
              </div> -->
              <div class="col-4 " v-for="(preview, index) in imagePreview" :key="index">
      <img :src="preview" alt="Image Preview" width="150rem" height="175rem" class="border border-primary"/>
    </div>
              </div>
            </div>
        </div>
        <div class="card-footer">
          <button type="button" class="btn btn-success" @click="save()">Create Product</button>
        </div>
      </div>
      <div class="col-md-6 bg-success-subtle">
        <div class="row">
          <div class="col-md-6">
            <div class="card mt-4">
              <img class="card-img-top" src="" />
              <div class="card-body">
                <p class="card-text">
                  <strong> post.title </strong> <br />
                  post.body
                </p>
              </div>
              <button class="btn btn-success m-2">View Post</button>
            </div>
          </div>
          <div width="40%">
            <span>
              <h3>currentPost.title</h3>
              <div class="row">
                <div class="col-md-6">
                  <img src="" class="img-thumbnail" alt="no image" />
                </div>
              </div>
              <hr />

            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
 
</template>

<style scoped>
.dropzone {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  row-gap: 16px;
  border: 2px dashed #41b883;
  background-color: #fff;
  transition: 0.3s ease all;

  label {
    padding: 8px 12px;
    color: #fff;
    background-color: #41b883;
    transition: 0.3s ease all;
  }

  input {
    display: none;
  }
}

.active-dropzone {
  color: #fff;
  border-color: #fff;
  background-color: #41b883;

  label {
    background-color: #fff;
    color: #41b883;
  }
}
</style>
