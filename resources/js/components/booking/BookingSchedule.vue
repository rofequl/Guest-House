<template>
    <div class="main-content-container container-fluid px-4 mb-4">
        <!-- Page Header -->
        <div class="page-header row no-gutters py-4">
            <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
                <span class="text-uppercase page-subtitle">Dashboard</span>
                <h3 class="page-title">Calendar</h3>
            </div>
            <div class="col-4 col-12 col-sm-6">
                <router-link to="/new-booking" class="btn bg-secondary rounded text-white text-center p-2 float-right"
                             style="box-shadow: inset 0 0 5px rgba(0,0,0,.2);">
                    <i class="fas fa-plus"></i> Guest Room Booking
                </router-link>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Calendar -->
        <div class="card">
            <div class="card-body py-4">
                <div class="form-group col-md-3 px-0">
                    <select class="form-control" v-model="form.room_type_id"
                            name="room_type_id" @change="changeRoomType()"
                            :class="{ 'is-invalid': form.errors.has('room_type_id') }">
                        <option value="" selected="" disabled>Choose Room Type</option>
                        <option v-for="roomType in roomTypes"
                                v-bind:value="roomType.id">{{roomType.room_type}}
                        </option>
                    </select>
                </div>
                <Fullcalendar :header='{left:"title",right:"prev,next"}' :plugins="calendarPlugins" :events="events"/>
            </div>
        </div>
        <!-- End Calendar -->
    </div>
</template>

<script>
    import Fullcalendar from "@fullcalendar/vue";
    import dayGridPlugin from "@fullcalendar/daygrid";
    import interactionPlugin from "@fullcalendar/interaction";
    import axios from "axios";

    export default {
        components: {
            Fullcalendar
        },
        data: function () {
            return {
                calendarPlugins: [dayGridPlugin, interactionPlugin],
                events: [],
                roomTypes: {},
                form: new Form({
                    room_type_id: '',
                }),
            };
        },
        created() {
            this.getEvents();
            this.loadRoomType();
        },
        methods: {
            getEvents() {
                var that = this;
                axios.get('api/booking-all-schedule').then(function (data) {
                    that.events = _.map(data.data, function (data) {
                        var pick = _.pick(data, 'booking_no', 'booking_from', 'booking_to', 'room_qty', 'room_type.room_type');
                        var object = {
                            title: pick.room_qty + ' ' + pick.room_type.room_type + ' room, Booking no- ' + pick.booking_no,
                            start: pick.booking_from,
                            end: pick.booking_to + "T01:00:00"
                        };
                        return object;
                    })
                });
            },
            loadRoomType() {
                axios.get('api/room-type').then(({data}) => (this.roomTypes = data));
            },
            changeRoomType() {
                this.$Progress.start()
                var that = this;
                this.form.post('api/booking-schedule-search')
                    .then(function (data) {
                        that.events = _.map(data.data, function (data) {
                            var pick = _.pick(data, 'booking_no', 'booking_from', 'booking_to', 'room_qty', 'room_type.room_type');
                            var object = {
                                title: pick.room_qty + ' ' + pick.room_type.room_type + ' room, Booking no- ' + pick.booking_no,
                                start: pick.booking_from,
                                end: pick.booking_to + "T23:59:00"
                            };
                            return object;
                        })
                    })
                    .catch(() => {
                        this.$Progress.fail()
                    })
            },
        }
    }

</script>

<style lang='scss'>

    @import "~@fullcalendar/core/main.css";
    @import "~@fullcalendar/daygrid/main.css";

    .fc-view-container {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    }
    .fc-time{
        display : none;
    }
</style>
