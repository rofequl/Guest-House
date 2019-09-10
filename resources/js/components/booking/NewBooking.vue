<template>
    <div class="main-content-container container-fluid px-4 mb-4">
        <!-- Page Header -->
        <div class="page-header row no-gutters py-4">
            <div class="col-12 col-sm-4 text-center text-sm-left mb-4 mb-sm-0">
                <span class="text-uppercase page-subtitle">Booking</span>
                <h3 class="page-title">Add New Booking</h3>
            </div>
            <div class="offset-sm-4 col-4 col-12 col-sm-4">
                <router-link to="/booking" class="btn bg-secondary rounded text-white text-center p-2 float-right"
                             style="box-shadow: inset 0 0 5px rgba(0,0,0,.2);">
                    <i class="fas fa-hand-point-left"></i> Back to Booking Transaction List
                </router-link>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Default Light Table -->
        <div class="row">
            <div class="col-lg-4">
                <div v-if="!roomTypesInfo" class="card card-small mb-4 pt-3">
                    <div class="card-header border-bottom text-center">
                        <h4 class="mb-0">Room Information</h4>
                        <i id="roomTypeDetailsError" class="material-icons d-none" style="font-size:53px">
                            error_outline
                        </i>
                        <span id="roomTypeDetails"
                              class="text-muted d-block mb-2">Select room type see the information.</span>
                        <router-link to="/room-setup" class="mb-2 btn btn-sm btn-pill btn-outline-primary mr-2"><i
                            class="material-icons">&#xE917;</i> Room Setup
                        </router-link>
                    </div>
                </div>
                <div v-if="roomTypesInfo" class="card card-small mb-4 pt-3">
                    <div class="card-header border-bottom text-center">
                        <div class="mb-3 mx-auto">
                            <img class="rounded" :src="'image/room/'+roomInfo.image" alt="Image" width="100%">
                        </div>
                        <h4 class="mb-0">{{roomInfo.room_type.room_type}}</h4>
                        <span class="text-muted d-block mb-2">Rent: {{roomInfo.rent}}</span>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-4">
                            <div class="progress-wrapper">
                                <strong class="text-muted d-block mb-2">Room Qty</strong>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="0"
                                         aria-valuemin="0" aria-valuemax="0" style="width: 20%;">
                                        <span class="progress-value"></span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item p-0">
                            <div class="card card-small mb-0">
                                <div class="card-header border-bottom">
                                    <h6 class="m-0">Booking Information</h6>
                                </div>
                                <div class="card-body p-0 pb-3 text-center">
                                    <perfect-scrollbar>
                                        <table class="table mb-0" style="font-size:10px">
                                            <thead class="bg-light">
                                            <tr>
                                                <th scope="col" class="border-0">From Date</th>
                                                <th scope="col" class="border-0">To Date</th>
                                                <th scope="col" class="border-0">Qty</th>
                                                <th scope="col" class="border-0">Booking Id</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="booking in bookings" v-bind:key="booking.id">
                                                <td>{{booking.booking_from | moment}}</td>
                                                <td>{{booking.booking_to | moment}}</td>
                                                <td>{{booking.room_qty}}</td>
                                                <td>{{booking.booking_no}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </perfect-scrollbar>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-small mb-4">
                    <div class="card-header border-bottom">
                        <h6 class="m-0">Booking Information</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item p-3">
                            <div class="row">
                                <div class="col">
                                    <form @submit.prevent="createBooking()"
                                          @keydown="form.onKeydown($event)" autocomplete="off">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <date-picker v-model="form.date" :config="options"></date-picker>
                                                <has-error :form="form" field="date"></has-error>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input type="text" v-model="form.booking_no" class="form-control"
                                                       id="booking_no"
                                                       :class="{ 'is-invalid': form.errors.has('booking_no')}"
                                                       placeholder="Booking No" readonly>
                                                <has-error :form="form" field="booking_no"></has-error>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-row">
                                            <div class="col mb-3">
                                                <p class="form-text text-muted m-0">Check Availability</p>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <select class="form-control" v-model="form.room_type_id"
                                                        name="room_type_id" @change="changeRoomType($event)"
                                                        :class="{ 'is-invalid': form.errors.has('room_type_id') }">
                                                    <option value="" selected="" disabled>Choose Room Type</option>
                                                    <option v-for="roomType in roomTypes"
                                                            v-bind:value="roomType.id">{{roomType.room_type}}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-row">
                                            <div class="col mb-3">
                                                <p class="form-text text-muted m-0">Apply for Booking
                                                    <span v-if="memberFound" class="text-black font-weight-bold ml-4"
                                                          style="font-size: 16px">
                                                        <i class="material-icons">
                                                            error_outline
                                                        </i>
                                                        No Member Found</span></p>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <input type="text" v-model="form.member_id" class="form-control"
                                                       id="member_id" v-on:keyup="getMember" name="member_id"
                                                       placeholder="Member Id"
                                                       :class="{ 'is-invalid': form.errors.has('member_id') }">
                                                <has-error :form="form" field="member_id"></has-error>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" id="name" v-model="form.name"
                                                       name="name"
                                                       placeholder="Member Name"
                                                       :class="{ 'is-invalid': form.errors.has('name') }">
                                                <has-error :form="form" field="name"></has-error>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" id="cell_no"
                                                       v-model="form.cell_no" name="cell_no"
                                                       placeholder="Member Cell No"
                                                       :class="{ 'is-invalid': form.errors.has('cell_no') }">
                                                <has-error :form="form" field="cell_no"></has-error>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" id="email" v-model="form.email"
                                                       name="email"
                                                       placeholder="Member Email"
                                                       :class="{ 'is-invalid': form.errors.has('email') }">
                                                <has-error :form="form" field="email"></has-error>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="address" v-model="form.address"
                                                   name="address"
                                                   placeholder="Member Address"
                                                   :class="{ 'is-invalid': form.errors.has('address') }">
                                            <has-error :form="form" field="address"></has-error>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <select class="form-control" v-model="form.purpose"
                                                        name="room_type_id"
                                                        :class="{ 'is-invalid': form.errors.has('purpose') }">
                                                    <option value="" selected="" disabled>Select Purpose</option>
                                                    <option value="Official">Official</option>
                                                    <option value="Personal">Personal</option>
                                                    <option value="Ceremony">Ceremony</option>
                                                </select>
                                                <has-error :form="form" field="purpose"></has-error>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input type="number" class="form-control" id="no_guest"
                                                       v-model="form.no_guest" name="no_guest"
                                                       placeholder="No of Guests"
                                                       :class="{ 'is-invalid': form.errors.has('no_guest') }">
                                                <has-error :form="form" field="no_guest"></has-error>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <date-picker @dp-change="dateDifference"
                                                             placeholder="Booking for Days, From"
                                                             :class="{ 'is-invalid': form.errors.has('booking_from') }"
                                                             name="booking_from" v-model="form.booking_from"
                                                             :config="options"></date-picker>
                                                <has-error :form="form" field="booking_from"></has-error>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <date-picker @dp-change="dateDifference"
                                                             placeholder="Booking for Days, To"
                                                             :class="{ 'is-invalid': form.errors.has('booking_to') }"
                                                             name="booking_to" v-model="form.booking_to"
                                                             :config="options"></date-picker>
                                                <has-error :form="form" field="booking_to"></has-error>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-row">
                                            <div class="col mb-3">
                                                <p class="form-text text-muted m-0">Payment</p>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <input type="number" class="form-control" id="room_rent"
                                                       v-model="form.room_rent" name="room_rent"
                                                       placeholder="Room Rent"
                                                       :class="{ 'is-invalid': form.errors.has('room_rent')}" readonly>
                                                <has-error :form="form" field="room_rent"></has-error>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input type="number" class="form-control" id="room_qty"
                                                       v-model="form.room_qty" name="room_qty" min="1"
                                                       placeholder="No of Room" v-on:keyup="keyTotal"
                                                       :class="{ 'is-invalid': form.errors.has('room_qty')}">
                                                <has-error :form="form" field="room_qty"></has-error>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <input type="number" class="form-control" id="total_amount"
                                                       v-model="form.total_amount" name="total_amount"
                                                       placeholder="Total Amount"
                                                       :class="{ 'is-invalid': form.errors.has('total_amount')}"
                                                       readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <select class="form-control"
                                                        v-model="form.payment_mood" name="payment_mood"
                                                        :class="{ 'is-invalid': form.errors.has('payment_mood') }">
                                                    <option value="" selected="" disabled>Choose Payment Method</option>
                                                    <option v-for="payment in payments"
                                                            v-bind:value="payment.id">{{payment.name}}
                                                    </option>
                                                </select>
                                                <has-error :form="form" field="payment_mood"></has-error>
                                            </div>
                                        </div>
                                        <button :disabled="form.busy" type="submit" class="btn btn-accent">Booking
                                            Submit
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Default Light Table -->
    </div>


    </div>
</template>

<script>
    export default {
        name: "NewBooking",
        data() {
            return {
                dateDifferences: 0,
                payments: {},
                roomTypes: {},
                members: {},
                roomTypesInfo: false,
                memberFound: false,
                roomImage: '',
                roomInfo: {},
                bookings: {},
                form: new Form({
                    date: moment().format('DD/MM/YYYY'),
                    booking_no: '',
                    room_type_id: '',
                    member_id: '',
                    name: '',
                    cell_no: '',
                    email: '',
                    address: '',
                    purpose: '',
                    no_guest: '',
                    booking_from: '',
                    booking_to: '',
                    room_rent: '',
                    room_qty: '',
                    total_amount: '',
                    payment_mood: '',
                }),
                date: new Date(),
                options: {
                    format: 'DD/MM/YYYY',
                    useCurrent: false,
                }
            }
        },
        methods: {
            loadRoomType() {
                axios.get('api/room-type').then(({data}) => (this.roomTypes = data));
            },
            loadMember() {
                this.$Progress.start()
                axios.get('http://gcms.issit.org/api/member_data').then((data) => {
                    this.members = data.data;
                    $('#member_id').prop("readonly", false);
                    this.$Progress.finish()
                });
            },
            loadPayment() {
                axios.get('api/payment-all').then(({data}) => (this.payments = data));
            },
            loadBooking() {
                axios.get('api/booking').then(({data}) => (this.form.booking_no = Math.floor(Math.random() * 899) + 100 + '' + data.total));
            },
            changeRoomType(event) {
                this.$Progress.start()
                axios.get('api/room-setup/' + event.target.value).then((data) => {
                    if (data.data == "") {
                        this.roomTypesInfo = false;
                        setTimeout(function () {
                            $('#roomTypeDetails').html('No search result found, you want to insert data click the below button.')
                                .addClass('text-danger my-3').removeClass('text-muted');
                            $('#roomTypeDetailsError').removeClass('d-none');
                        }, 1000);
                        this.$Progress.fail()
                        this.form.room_rent = '';
                        this.keyTotal();
                    } else {
                        this.$Progress.finish()
                        axios.get('api/room-type-booking/' + event.target.value).then(({data}) => (this.bookings = data));
                        this.roomInfo = data.data;
                        this.form.room_rent = data.data.rent;
                        this.roomTypesInfo = true;
                        this.keyTotal();
                    }
                });
            },
            getMember: function () {

                axios.get('api/member/'+this.form.member_id).then((data) => {
                    console.log(typeof data.data);
                    if (jQuery.isEmptyObject(data.data)) {
                        this.memberFound = true;
                        this.form.name = '';
                        this.form.cell_no = '';
                        this.form.email = '';
                        this.form.address = '';
                    } else {
                        this.memberFound = false;
                        this.form.name = data.data.full_name;
                        this.form.cell_no = data.data.mobile_number;
                        this.form.email = data.data.email;
                        this.form.address = data.data.present_address;
                    }
                });



                //
                // var myArray = this.members;
                // let data = myArray.find(x => x.membership_no === this.form.member_id);


            },
            keyTotal: function () {
                let number = isNaN(parseFloat(this.form.room_qty)) ? 1 : parseFloat(this.form.room_qty);
                let number2 = isNaN(parseFloat(this.form.room_rent)) ? 0 : parseFloat(this.form.room_rent);
                this.form.total_amount = (number * number2) * this.dateDifferences;
            },
            createBooking() {
                this.$Progress.start()
                this.form.post('api/booking')
                    .then(() => {
                        toast.fire({
                            type: 'success',
                            title: 'Booking entry successfully'
                        })
                        this.form.reset();
                        this.form.clear();
                        this.loadBooking();
                        this.$Progress.finish()
                    })
                    .catch(() => {
                        this.$Progress.fail()
                        swal("Failed!", 'There was something wrong.', 'warning')
                    })
            },
            dateDifference: function () {
                if (this.form.booking_from === '')
                    this.form.booking_from = this.form.booking_to;
                else if (this.form.booking_to === '')
                    this.form.booking_to = this.form.booking_from;

                this.dateDifferences = moment.duration(moment(this.form.booking_to, 'DD/MM/YYYY', true)
                    .diff(moment(this.form.booking_from, 'DD/MM/YYYY', true))).asDays() + 1;
                this.keyTotal();

            },
        },
        created() {
            this.loadRoomType();
            this.loadBooking();
            this.loadPayment();
        },
        filters: {
            moment: function (date) {
                return moment(date).format('D-MMM-YYYY');
            }
        }
    }
</script>

<style scoped>

</style>
