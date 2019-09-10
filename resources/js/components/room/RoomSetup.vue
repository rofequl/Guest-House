<template>
    <div class="main-content-container container-fluid px-4">
        <!-- Page Header -->
        <div class="page-header row no-gutters py-4">
            <div class="col-12 col-sm-4 text-center text-sm-left mb-4 mb-sm-0">
                <span class="text-uppercase page-subtitle">Room Setup</span>
                <h3 class="page-title">Room Information</h3>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Default Light Table -->
        <div class="row">
            <div class="col">
                <div class="card card-small mb-4">
                    <div class="card-header border-bottom">
                        <div class="input-group col-md-6 col-lg-4 ml-auto">
                            <button class="btn btn-primary ml-auto" @click="NewModal"><i class="fas fa-plus mr-2"></i>Add
                                new room
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0 pb-3 text-center">
                        <perfect-scrollbar>
                            <table class="table table-bordered mb-0">
                                <thead class="bg-light">
                                <tr role="row">
                                    <th scope="col" class="border-0">#</th>
                                    <th scope="col" class="border-0">Picture</th>
                                    <th scope="col" class="border-0">Room Type</th>
                                    <th scope="col" class="border-0">Room Rent Per Night</th>
                                    <th scope="col" class="border-0">Room Qty</th>
                                    <th scope="col" class="border-0">Details</th>
                                    <th scope="col" class="border-0">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="room in rooms.data" v-bind:key="room.id">
                                    <td>{{room.id}}</td>
                                    <td>
                                        <img :src="getProfilePhoto(room.image)" class="img-thumbnail" width="80px">
                                    </td>
                                    <td>{{room.room_type.room_type}}</td>
                                    <td>{{room.rent}}</td>
                                    <td>{{room.qty}}</td>
                                    <td>{{room.details}}</td>
                                    <td>
                                        <div class="btn-group d-inline-flex mx-auto" role="group"
                                             aria-label="Basic example">
                                            <button type="button" @click="NewModalUpdate(room)"
                                                    class="btn btn-sm btn-white"><i
                                                class="fas fa-edit mr-1"></i>
                                            </button>
                                            <button type="button" @click="deleteRoom(room.id)"
                                                    class="btn btn-sm btn-white"><i
                                                class="fas fa-trash mr-1"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </perfect-scrollbar>
                    </div>
                    <div class="card-footer">
                        <pagination :data="rooms" @pagination-change-page="getResults">
                            <span slot="prev-nav">&lt; Previous</span>
                            <span slot="next-nav">Next &gt;</span>
                        </pagination>
                    </div>
                </div>
            </div>


        </div>
        <!-- End Default Light Table -->

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateRoom() : createRoom()"
                          @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="form-row" v-if="newRoomType">
                                <div class="form-group col-md-8">
                                    <input v-model="form.room_type" type="text" class="form-control"
                                           :class="{ 'is-invalid': form.errors.has('room_type') }"
                                           placeholder="Room type name" id="inputCity">
                                    <has-error :form="form" field="room_type"></has-error>
                                </div>
                                <div class="form-group col-md-4">
                                    <button type="button" @click="createRoomType" class="btn btn-primary">Save</button>
                                    <button type="button" @click="cancelRoomType" class="btn btn-secondary">Cancel
                                    </button>
                                </div>
                                <hr class="w-100 mx-2">
                            </div>

                            <div class="input-group mb-3">
                                <select class="form-control" v-model="form.room_type_id" name="room_type_id"
                                        :class="{ 'is-invalid': form.errors.has('room_type_id') }">
                                    <option value="" selected="" disabled>Choose Room Type</option>
                                    <option v-for="roomType in roomTypes"
                                            v-bind:value="roomType.id">{{roomType.room_type}}
                                    </option>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-salmon" @click="addRoomType" type="button">Add Room Type
                                    </button>
                                </div>
                                <has-error :form="form" field="room_type_id"></has-error>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <div class="input-group input-group-seamless">
                                        <input type="number" v-model.number="form.rent"
                                               name="rent" min="1"
                                               class="form-control" id="rent"
                                               placeholder="Room Rent"
                                               :class="{ 'is-invalid': form.errors.has('rent') }">
                                        <span class="input-group-append">
                                          <span class="input-group-text">&#2547;</span>
                                        </span>
                                        <has-error :form="form" field="rent"></has-error>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="input-group">
                                        <input type="number" v-model="form.qty"
                                               name="qty" min="1"
                                               class="form-control" id="qty"
                                               placeholder="Total Room"
                                               :class="{ 'is-invalid': form.errors.has('qty') }">
                                        <has-error :form="form" field="qty" class="d-block"></has-error>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <textarea style="min-height: 125px;" id="userBio" name="details"
                                              v-model="form.details"
                                              placeholder="Room Details" class="form-control"
                                              :class="{ 'is-invalid': form.errors.has('details') }"></textarea>
                                    <has-error :form="form" field="details"></has-error>
                                </div>
                                <div class="form-group col-md-4">
                                    <image-uploader :image="form.image" @update-parent="updateparent"></image-uploader>
                                    <input type="hidden" :class="{ 'is-invalid': form.errors.has('image') }">
                                    <has-error :form="form" field="image"></has-error>
                                </div>
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" v-if="!editMode" class="btn btn-primary">Create</button>
                            <button type="submit" v-if="editMode" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import ImageUploader from "./ImageUploader";

    export default {
        components: {ImageUploader},
        data() {
            return {
                editMode: false,
                newRoomType: false,
                roomTypes: {},
                rooms: {},
                form: new Form({
                    id: '',
                    room_type: '',
                    room_type_id: '',
                    rent: '',
                    qty: '',
                    details: '',
                    image: '',
                })
            }
        },
        methods: {
            getResults(page = 1) {
                axios.get('api/room-setup?page=' + page)
                    .then(response => {
                        this.rooms = response.data;
                    });
            },
            getProfilePhoto(e){
                return "image/room/"+ e;
            },
            NewModal() {
                this.form.reset();
                this.form.clear();
                this.editMode = false;
                $('#addNew').modal('show');
                $('#addNewLabel').html('Room information insert');
            },
            loadRoomType() {
                axios.get('api/room-type').then(({data}) => (this.roomTypes = data));
            },
            updateparent(variable) {
                this.form.image = variable;
            },
            addRoomType() {
                this.form.clear('room_type');
                this.newRoomType = true;
            },
            cancelRoomType() {
                this.newRoomType = false;
            },
            createRoomType() {
                this.$Progress.start();
                this.form.post('api/room-type-setup')
                    .then((data) => {
                        this.loadRoomType();
                        this.form.room_type = '';
                        this.newRoomType = false;
                        this.form.room_type_id = data.data.id;
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$Progress.fail()
                    })
            },
            createRoom() {
                this.$Progress.start()
                this.form.post('api/room-setup')
                    .then(() => {
                        toast.fire({
                            type: 'success',
                            title: 'Room information add successfully'
                        })
                        this.form.reset()
                        $('#addNew').modal('hide');
                        Fire.$emit('AfterCreate')
                        this.$Progress.finish()
                    })
                    .catch(error => {
                        let data = error.response;
                        console.log(data);
                        if (data.status === 422 && data.data.errors.image) {
                            swal.fire({
                                title: 'Image',
                                html: 'Select one image',
                                type: 'Error'
                            });
                        }
                        this.$Progress.fail()
                    })
            },
            loadRoom() {
                axios.get('api/room-setup').then(({data}) => (this.rooms = data));
            },
            deleteRoom(id) {
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        this.$Progress.start()
                        this.form.delete('api/room-setup/' + id)
                            .then((data) => {
                                Fire.$emit('AfterCreate');
                                swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                )
                                this.$Progress.finish()
                            })
                            .catch(() => {
                                this.$Progress.fail()
                                swal("Failed!", 'There was something wrong.', 'warning')
                            });
                    }
                })
            },
            NewModalUpdate(data) {
                this.form.reset();
                this.form.clear();
                this.form.fill(data);
                this.form.image =  "image/room/"+ data.image;
                this.editMode = true;
                $('#addNew').modal('show');
                $('#addNewLabel').html('Update Room Information');
            },
            updateRoom() {
                this.$Progress.start()
                this.form.put('api/room-setup/' + this.form.id)
                    .then(() => {
                        toast.fire({
                            type: 'success',
                            title: 'Room information update successfully'
                        })
                        this.form.reset()
                        Fire.$emit('AfterCreate')
                        this.$Progress.finish()
                        $('#addNew').modal('hide');
                    })
                    .catch(() => {
                        this.$Progress.fail()
                        swal("Failed!", 'There was something wrong.', 'warning')
                    })
            },
        },
        created() {
            this.loadRoomType();
            this.loadRoom();
            Fire.$on('AfterCreate', () => {
                this.loadRoom();
            });

        }
    }
</script>

<style scoped>

</style>
