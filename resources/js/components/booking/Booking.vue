<template>
    <div class="main-content-container container-fluid px-4 mb-4">
        <!-- Page Header -->
        <div class="page-header row no-gutters py-4">
            <div class="col-12 col-sm-4 text-center text-sm-left mb-4 mb-sm-0">
                <span class="text-uppercase page-subtitle">Dashboard</span>
                <h3 class="page-title">Transaction History</h3>
            </div>
            <div class="offset-sm-4 col-4 col-12 col-sm-4">
                <router-link to="/new-booking" class="btn bg-secondary rounded text-white text-center p-2 float-right"
                             style="box-shadow: inset 0 0 5px rgba(0,0,0,.2);">
                    <i class="fas fa-plus"></i> Guest Room Booking
                </router-link>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Transaction History Table -->
        <div class="row">
            <div class="col">
                <div class="card card-small mb-4">
                    <div class="card-header border-bottom">
                        <div class="row justify-content-end">
                            <div class="col-md-4">
                                <div id="transaction-history-date-range"
                                     class="input-daterange input-group ml-auto">
                                    <date-picker placeholder="Booking from date"
                                                 :class="{ 'is-invalid': form.errors.has('booking_from') }"
                                                 name="booking_from" v-model="form.booking_from" autocomplete="off"
                                                 :config="options"></date-picker>
                                    <date-picker placeholder="Booking to date"
                                                 :class="{ 'is-invalid': form.errors.has('booking_from') }"
                                                 name="booking_from" v-model="form.booking_from" autocomplete="off"
                                                 :config="options"></date-picker>
                                    <span class="input-group-append">
                    <span class="input-group-text">
                      <i class="material-icons">&#xE916;</i>
                    </span>
                  </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0 pb-3 text-center">
                        <perfect-scrollbar>
                            <table class="table table-bordered mb-0">
                                <thead class="bg-light">
                                <tr role="row">
                                    <th scope="col" class="border-0">#</th>
                                    <th scope="col" class="border-0">Member Id</th>
                                    <th scope="col" class="border-0">Booking No</th>
                                    <th scope="col" class="border-0">Room Type</th>
                                    <th scope="col" class="border-0">Booking From</th>
                                    <th scope="col" class="border-0">Booking To</th>
                                    <th scope="col" class="border-0">Qty</th>
                                    <th scope="col" class="border-0">Amount</th>
                                    <th scope="col" class="border-0">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="booking in bookings.data" v-bind:key="booking.id">
                                    <td>{{booking.id}}</td>
                                    <td>{{booking.member_id}}</td>
                                    <td>{{booking.booking_no}}</td>
                                    <td>{{booking.room_type.room_type}}</td>
                                    <td>{{booking.booking_from}}</td>
                                    <td>{{booking.booking_to}}</td>
                                    <td>{{booking.room_qty}}</td>
                                    <td>{{booking.total_amount}}</td>
                                    <td>
                                        <div class="btn-group d-inline-flex mx-auto" role="group"
                                             aria-label="Basic example">
                                            <button type="button" @click="deleteBooking(booking.id)"
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
                        <pagination :data="bookings" @pagination-change-page="getResults">
                            <span slot="prev-nav">&lt; Previous</span>
                            <span slot="next-nav">Next &gt;</span>
                        </pagination>
                    </div>
                </div>
            </div>
            <!-- End Transaction History Table -->
        </div>
    </div>
</template>

<script>
    export default {
        name: "Booking",
        data() {
            return {
                roomTypes: {},
                bookings: {},
                form: new Form({
                    booking_from: '',
                }),
                options: {
                    format: 'DD/MM/YYYY',
                    useCurrent: false,
                }
            }
        },
        methods: {
            loadBookings() {
                axios.get('api/booking').then(({data}) => (this.bookings = data));
            },
            getResults(page = 1) {
                axios.get('api/booking?page=' + page)
                    .then(response => {
                        this.bookings = response.data;
                    });
            },
            deleteBooking(id) {
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
                        this.form.delete('api/booking/' + id)
                            .then((data) => {
                                Fire.$emit('loadBookings');
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
            bookingView(id){
                this.$router.push('booking-view/'+id);
            }
        },
        created() {
            this.loadBookings();
            Fire.$on('loadBookings', () => {
                this.loadBookings();
            });
        }
    }
</script>

<style scoped>

</style>
