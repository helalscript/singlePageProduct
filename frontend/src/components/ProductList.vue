<script>
import axios from "axios";

export default {
    data() {
        return {
            url: this.$store.state.base.url + "admin/products",
            imageLink: this.$store.state.base.imageLink,
            id: "",
            name: "",
            price: "",
            description: "",
            path: [],
            productlist: [],
            imagePreview: [],
            active: false,
            isDragging: false,
        };
    },
    methods: {
        // =================get all product list  ==============
        getProductList() {
            axios.get(this.url).then((response) => {
                //console.log(response.data.data);
                const product = response.data.data;
                this.productlist = product;
            });
        },
        onDragLeave(event) {
            this.isDragging = false;
        },
        onDragOver(event) {
            this.isDragging = true;
        },
        onDrop(event) {
            const files = event.dataTransfer.files;
            if (files && files.length > 0) {
                for (const file of files) {
                    if (file.type.startsWith("image/")) {
                        this.createImagePreview(file);
                        this.path.push(file);
                    }
                }
            }
        },
        onFileSelected(event) {
            // this.path = event.target.files[0]
            const files = event.target.files;
            if (files && files.length > 0) {
                for (const file of files) {
                    if (file.type.startsWith("image/")) {
                        this.createImagePreview(file);
                        this.path.push(file);
                    }
                }
            }
            // console.log(files);
        },
        deleteImg(index) {
            this.imagePreview.splice(index, 1)
            this.path.splice(index, 1)
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
            formData.append("path", this.path);
            const alldata = {
                name: this.name,
                price: this.price,
                description: this.description,
                path: this.path,
            };
            axios
                .post(this.url, alldata, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    (this.id = ""), (this.name = "");
                    this.price = "";
                    this.description = "";
                    this.path = [];
                    this.imagePreview = [];
                    this.getProductList();
                });
        },
        // ==================== product delete with all photo===========================
        deleteProduct(id) {
            axios.delete(`${this.url}/${id}`).then((response) => {
                this.getProductList();
            });
        },
        // ==================== edit to view product ====================
        editProduct(id) {
            axios.get(`${this.url}/${id}`).then((response) => {
                console.log(response.data.data.photo);
                this.id = response.data.data.id;
                this.name = response.data.data.name;
                this.price = response.data.data.price;
                this.description = response.data.data.description;
                this.path = response.data.data.photo;
                this.imagePreview = response.data.data.photo;
            });
        },
        // =====================cancle editing=====================
        back() {
            (this.id = ""), (this.name = "");
            this.price = "";
            this.description = "";
            this.path = [];
            this.imagePreview = [];
        },
    },
    mounted() {
        this.getProductList();
    },
};
</script>

<template>
    <div class="container-fluid">
        <div class="container">
            <div class="justify-content-center row">
                <h1 class="card text-center bg-dark text-light">
                    Multiple Image upload app
                </h1>

                <div class="col-md-6">
                    <div>
                        <!-- <div>status_msg</div> -->
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Name</label>
                            <input type="text" class="form-control" placeholder="Product name" required
                                v-model="name" />
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Price</label>
                            <input type="text" class="form-control" placeholder="Product price" required
                                v-model="price" />
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Details</label>
                            <textarea class="form-control" rows="3" required v-model="description"></textarea>
                        </div>
                        <!-- ---------------------product edit mood-------------------- -->
                        <div v-if="id != ''">
                            <div class="row">
                                <div class="col-4" v-for="(preview, index) in imagePreview" :key="index">
                                    <img :src="`http://127.0.0.1:8000/photos/products/${preview.path}`" width="150rem"
                                        height="175rem" class="border border-primary" />
                                </div>
                            </div>
                        </div>
                        <!-- ---------------------product create mood-------------------- -->
                        <div class="form-group" v-else>
                            <label for="imageUpload">Image Upload</label>
                            <div class="dropzone" @dragenter="onDragOver" @dragleave.prevent="onDragLeave"
                                @dragover.prevent="onDragOver" @drop.prevent="onDrop">
                                <span>Drag or Drop File</span>
                                <span>OR</span>
                                <label for="dropzoneFile">Select File</label>
                                <input type="file" id="dropzoneFile" class="dropzoneFile" @change="onFileSelected"
                                    multiple accept=".jpg,.jpeg,.png" />
                            </div>
                            <div class="row">
                                <div class="col-4" v-for="(preview, index) in imagePreview" :key="index">
                                    <span class="delete" @click="deleteImg(index)">&times;</span>
                                    <img :src="preview" alt="Image Preview" width="150rem" height="175rem"
                                        class="border border-primary" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-success" @click="update()" v-if="id != ''">
                            Update Product
                        </button>
                        <button type="button" class="btn btn-warning" @click="back()" v-if="id != ''">
                            Cancle Update
                        </button>
                        <button type="button" class="btn btn-success" @click="save()" v-else="(id = '')">
                            Create Product
                        </button>
                    </div>
                </div>
                <div class="col-md-6 bg-success-subtle">
                    <div class="row">
                        <div class="col-md-6" v-for="(data, i) in productlist" :key="i">
                            <div class="card mt-4">
                                <img class="card-img-top"
                                    :src="`http://127.0.0.1:8000/photos/products/${data.photo[0].path}`" width="200rem"
                                    height="250rem" />
                                <div class="card-body">
                                    <p class="card-text">
                                        <strong> {{ data.name }} </strong> <br />
                                        {{
                                            data.description.length <= 10 ? data.description : data.description.substr(0,
                                                20) + "..." }} </p>
                                </div>
                                <div class="">
                                    <button class="btn btn-success btn-sm col-4">View</button>
                                    <button class="btn btn-warning btn-sm col-4" @click="editProduct(data.id)">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm col-4" @click="deleteProduct(data.id)">
                                        Delete
                                    </button>
                                </div>
                            </div>
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

.delete {
    /* position: absolute;
        left:200px;
        margin-top: 0px; */
    position: absolute;
    font-size: 35px;
    z-index: 9999;
    color: #fe0000;
    cursor: pointer;
    margin-top: -20px;
    margin-left: 135px;
}
</style>