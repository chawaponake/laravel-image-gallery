<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header font-weight-bold">Gallery</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="uploader"
                                    @click="trigger"
                                    @dragenter = "onDragEnter"
                                    @dragleave = "onDragLeave"
                                    @drop.prevent="onDrop"
                                    @dragover.prevent
                                    v-bind:class="{dragging : isDragging}">
                                    <i class="fas fa-cloud-upload-alt fa-5x text-secondary"></i>
                                    <p class="text-secondary font-weight-bold">Drop files here or click to choose files..</p>
                                </div>
                                <div class="wrapper">
                                    <input type="file" name="files[]" multiple="true" ref="fileInput" @change="onChange"/>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-md-4 mt-3" v-for="media in medias">
                                <div class="thumbnail-error text-center" v-if="!media.isImage ">
                                    <div class="overlay"></div>
                                    <i class="fas fa-exclamation-triangle text-danger fa-7x pt-4"></i>
                                    <p class="text-danger font-weight-bold">File type not supported. - {{media.name}}</p>
                                    <div class="thumb-error-button">
                                        <button type="button" class="btn btn-md btn-danger" @click="deleteMedia(media.id)"><i class="fas fa-trash-alt text-light"></i></button>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="thumbnail-error text-center" v-if="media.isMaxsize">
                                        <div class="overlay"></div>
                                        <i class="fas fa-exclamation-triangle text-danger fa-7x pt-4"></i>
                                        <p class="text-danger font-weight-bold">File size exceeded. - {{media.name}}</p>
                                        <div class="thumb-error-button">
                                            <button type="button" class="btn btn-md btn-danger" @click="deleteMedia(media.id)"><i class="fas fa-trash-alt text-light"></i></button>
                                        </div>
                                    </div>
                                    <div class="thumbnail" v-else>
                                        <div class="overlay"></div>
                                        <img :src="media.thumbnail" />
                                        <div class="thumb-button">
                                            <button type="button" class="btn btn-md btn-primary" @click="showPopup(media.url)"><i class="fas fa-search text-light"></i></button>
                                            <button type="button" class="btn btn-md btn-danger" @click="deleteMedia(media.id)"><i class="fas fa-trash-alt text-light"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="myModal" class="modal fade">
            <img class="modal-dialog" id="img-popup">
            <span class="close" data-dismiss="modal">&times;</span>
        </div>
    </div>


</template>

<script>
     export default {
        data: function(){
            return{
                medias : [],
                isDragging : false,
            }
        },
        created(){
            this.fetch();
        },
        methods: {
            fetch: function(){
                axios.get('gallery')
                    .then(response => {
                        this.medias = response.data.data;
                });
            },
            trigger: function(e){
                e.preventDefault();
                this.$refs.fileInput.click();
            },
            onDragEnter: function(e){
                e.preventDefault();
                this.isDragging = true;
            },
            onDragLeave: function(e){
                e.preventDefault();
                this.isDragging = false;
            },
            onDrop: function(e){
                e.preventDefault();
                this.isDragging = false;

                const files = e.dataTransfer.files;

                this.uploadFile(files);
            },
            onChange: function(e){
                e.preventDefault();

                const files = e.target.files;

                this.uploadFile(files);
            },
            uploadFile: function(files){
                const formData = new FormData();
                const config = { onUploadProgress: progressEvent => {
                                    let progress = progressEvent.loaded / progressEvent.total * 100;
                                    }
                               };

                Array.from(files).forEach(function (file) {
                    formData.append('files[]', file);
                });

                axios.post('gallery',formData,config)
                    .then(response => {
                        this.$refs.fileInput.value = null;
                        this.medias = this.medias.concat(response.data.data);
                        //console.log(response);
                });

            },
            showPopup: function(url){
                $('#myModal').modal('show');
                $('#img-popup').attr('src',url);
            },
            deleteMedia: function(id){
                let path = 'gallery/'+id;

                axios.delete(path)
                    .then(response => {
                        this.fetch();
                });
            }

        }
     }
</script>

<style lang="scss" scoped>
    .uploader {
        background: #ffffff;
        width: 100%;
        border: 3px dashed #d6d6d6;
        text-align: center;
        padding: 40px 40px;
        cursor: pointer;
    }
    .dragging {
        border: 3px dashed #6c757d;
    }
    .wrapper{
        display: none;
    }
    .thumbnail,
    .thumbnail-error{
        transition: .5s ease;
        height: 200px;
        width: 200px;

        img{
            width: 100%;
            height: auto;
        }
    }

    .thumbnail-error {
        transition: .5s ease;
        height: 200px;
        width: 200px;
    }

    .thumb-button,
    .thumb-error-button {
        transition: .5s ease;
        opacity: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .overlay{
        position: absolute;
        width: 200px;
        height: 200px;
        background: rgba(0, 0, 0, 0);
        transition: background 0.5s ease;
    }

    .thumbnail:hover .overlay,
    .thumbnail-error:hover .overlay{
        display: block;
        background: rgba(0, 0, 0, .5);
    }

    .thumbnail:hover .thumb-button,
    .thumbnail-error:hover .thumb-error-button{
        opacity: 1;
    }


    .modal-dialog {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        padding: 70px 0;
    }

    .close {
        position: absolute;
        top: 80%;
        right: 100px;
        color: #f1f1f1;
        font-size: 70px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

</style>